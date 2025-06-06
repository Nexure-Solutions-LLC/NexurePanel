from __future__ import annotations
from utils.constants import NexureConstants
from asyncmy import create_pool
from asyncio import Event
from functools import partial as PartialFunction
from munch import Munch
from pymysql import IntegrityError
from typing import Any, Dict, List, Tuple
from typing_extensions import Self

__all__ = (
    "GLOBAL_DATABASE",
    "MySQL",
    "UniqueViolationError"
)

GLOBAL_DATABASE: MySQL = None


class UniqueViolationError(IntegrityError):
    pass


class MySQL:
    def __init__(self: MySQL, bot: Bot):
        self.__bot = bot
        self.__pool = None
        self.__event = Event()
        
        self.errors = Munch(
            unique_violation=UniqueViolationError
        )
        
        self.fetch = PartialFunction(self.execute, as_list=True)
        self.fetchrow = PartialFunction(self.execute, one_row=True)
        self.fetchval = PartialFunction(self.execute, one_value=True)
        
        
    async def __aenter__(self: MySQL) -> Self:
        if self.__pool is None:
            await self.initialize()
        
        return self
    

    async def __aexit__(self: MySQL, *_):
        await self.close()
        
        
    async def close(self: MySQL) -> bool:
        if self.__pool:
            self.__pool.close()
            await self.__pool.wait_closed()
            
        GLOBAL_DATABASE = None
        return True
    

    async def initialize(self: MySQL):
        credentials = NexureConstants().sql_con()
        self.__pool = await create_pool(
            db=credentials["DATABASE"],
            host=credentials["HOST"],
            port=credentials["PORT"],
            user=credentials["USER"],
            password=credentials["PASSWORD"],
            maxsize=10, 
            autocommit=True, 
            echo=False
        )
        
        GLOBAL_DATABASE = self
        self.__event.set()
        
        
    async def execute(
        self: MySQL, query: str,
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


    async def reload_auth(self) -> List[int]:
        return list(map(
            int,
            await self.fetch(f"SELECT oAuthID FROM {NexureConstants().sql_users()} WHERE botAuth = 1;")
        ))