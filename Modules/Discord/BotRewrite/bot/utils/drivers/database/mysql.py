# Author: Treyten
from __future__ import annotations

from asyncmy import create_pool
from asyncio import Event
from functools import partial as PartialFunction
from munch import Munch
from os import environ as Env
from pymysql import IntegrityError
from typing import Any, Dict, List, Tuple, TYPE_CHECKING
from typing_extensions import Self

if TYPE_CHECKING:
    from bot import NexureClient

__all__ = (
    "MySQL",
    "UniqueViolationError"
)


class UniqueViolationError(IntegrityError):
    pass


class MySQL:
    __slots__ = (
        "__bot",
        "__pool",
        "__event",
        "errors",
        "fetch",
        "fetchrow",
        "fetchval"
    )
    
    def __init__(self, bot: NexureClient):
        self.__bot = bot
        self.__pool = None
        self.__event = Event()
        
        self.errors = Munch(
            unique_violation=UniqueViolationError
        )
        
        self.fetch = PartialFunction(self.execute, as_list=True)
        self.fetchrow = PartialFunction(self.execute, one_row=True)
        self.fetchval = PartialFunction(self.execute, one_value=True)
        
        
    async def __aenter__(self) -> Self:
        if self.__pool is None:
            await self.initialize()
        return self
    

    async def __aexit__(self, *_):
        await self.close()
        
        
    async def close(self) -> bool:
        if self.__pool:
            self.__pool.close()
            await self.__pool.wait_closed()
            
        __import__("asyncmy").GLOBAL = None
        return True
    

    async def initialize(self):
        self.__pool = await create_pool(
            db=Env["SQL_DATABASE"],
            host=Env["SQL_HOST"],
            port=int(Env["SQL_PORT"]),
            user=Env["SQL_USER"],
            password=Env["SQL_PASSWORD"],
            maxsize=10, autocommit=True, echo=False
        )
        
        __import__("asyncmy").GLOBAL = self
        self.__event.set()
        
        
    async def execute(
        self, query: str,
        *args: Tuple[Any], **kwargs: Dict[ str, Any ]
    ) -> Any:
        await self.__event.wait()
        
        async with self.__pool.acquire() as connection:
            async with connection.cursor() as cursor:
                try:
                    await cursor.execute(query, args)
                
                except IntegrityError as exception:
                    if exception.args[0] == 1062:
                        raise UniqueViolationError(exception.args)
                    
                    raise
                        
                data = await cursor.fetchall()
                
        if not data:
            return ()
        
        if kwargs.pop("one_value", False):
            return data[0][0]
        
        if kwargs.pop("one_row", False):
            return data[0]
        
        if kwargs.pop("as_list", False):
            return tuple(map(lambda row: row[0], data))
        
        return data