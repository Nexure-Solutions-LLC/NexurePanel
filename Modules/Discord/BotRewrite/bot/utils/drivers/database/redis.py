# Author: Treyten
from __future__ import annotations

from asyncio import Semaphore

from asyncio import create_task, sleep
from cashews import Cache
from contextlib import asynccontextmanager
from redis.asyncio import Redis as DefaultRedisC
from redis.asyncio.lock import Lock, LockNotOwnedError
from xxhash import xxh32_hexdigest as x3hash
from typing import Any, Dict, Optional, Tuple
from typing_extensions import Self

__all__ = ("AutoRenewingLock", "Redis")


class AutoRenewingLock(Lock):
    __slots__ = ()

    async def renew(self) -> bool:
        try:
            await self.extend(additional_time=self.timeout)
        except LockNotOwnedError:
            return False
        return True


class Redis(DefaultRedisC):
    __slots__ = ("__semaphores",)
    
    
    def __init__(self, *args: Tuple[Any], **kwargs: Dict[ str, Any ]):
        super().__init__(decode_responses=True, *args, **kwargs)
        self.__semaphores = dict()

        
    async def __aenter__(self) -> Self:
        return await self.initialize()


    async def __aexit__(self, *_):
        await self.close()


    async def close(self):
        await self.aclose(close_connection_pool=True)
        
        
    async def initialize(self, *args: Tuple[Any], **kwargs: Dict[ str, Any ]) -> Self:
        await super().initialize(*args, **kwargs)
        
        __import__("redis").GLOBAL = self
        return self

    
    @asynccontextmanager
    async def limited(self, ident: str, limit: int, window: float = 60.0):
        if not await self.ratelimited(ident, limit, window):
            yield
        else:
            raise RuntimeError(f"The resource '{ident}' is being rate limited.")
    
    
    async def ratelimited(
        self, 
        resource_ident: str, request_limit: int, 
        timespan: float = 60.0, increment: bool = True
    ) -> bool:
        key = f"rl:{x3hash(resource_ident)}"
        
        if not increment and await self.get(key):
            if (int(await self.get(key)) >= request_limit) and ((ttl := await self.ttl(key)) > 0):
                return ttl
            return 0
        
        async with self.pipeline() as transaction:
            transaction.incr(key)
            transaction.expire(key, timespan)
            result, _ = await transaction.execute()

        return int(result) > request_limit


    def get_lock(
        self,
        resource_ident: str, timeout: float = 60.0
    ) -> Lock:
        return AutoRenewingLock(
            self, f"lock:{x3hash(resource_ident)}",
            timeout=timeout
        )


    def get_semaphore(
        self, 
        resource_ident: str, value: int
    ) -> Semaphore:
        key = f"sem:{x3hash(resource_ident)}"
        
        if key not in self.__semaphores or self.__semaphores[key]._value != value:
            self.__semaphores[key] = Semaphore(value)
        
        return self.__semaphores[key]


    @asynccontextmanager
    async def auto_lock(
        self,
        resource_ident: str, timeout: int = 10
    ):
        lock = AutoRenewingLock(self, f"lock:{x3hash(resource_ident)}", timeout=timeout)

        if not await lock.acquire(blocking=False):
            yield None
            return

        async def keep_alive():
            while True:
                await sleep(timeout / 2)
                if not await lock.renew():
                    break

        renew_task = create_task(keep_alive())

        try:
            yield lock
        finally:
            await lock.release()
            renew_task.cancel()
