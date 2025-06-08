# Author: Treyten
from __future__ import annotations
from ._essentials import create_model, HEADERS, normalize_dict, PROXIES

from aiohttp import (
    AsyncResolver,
    ClientRequest,
    ClientResponse,
    ClientResponseError,
    ClientSession as DefaultClientSession,
    ClientTimeout,
    CookieJar,
    TCPConnector
)

from asyncio import (
    Semaphore,
    sleep
)

from aiomisc.backoff import asyncretry as Retry
from bs4 import BeautifulSoup
from collections import defaultdict
from discord import CommandError
from functools import partial as PartialFunction
from pydantic import BaseModel
from random import choice
from socket import AF_INET
from typing import Any, Dict, Optional, Tuple

import orjson


class ClientSession(DefaultClientSession):
    def __init__(self: ClientSession, *args, **kwargs) -> None:
        super().__init__(
            timeout=ClientTimeout(total=60),
            cookie_jar=CookieJar(),
            raise_for_status=True,
            json_serialize=orjson.dumps,
            headers=kwargs.pop("headers", {"User-Agent": HEADERS.User_Agent}),
            connector=TCPConnector(
                family=AF_INET,
                resolver=AsyncResolver(),
                limit=0,
                local_addr=None
            ),
            **kwargs
        )
        
        self.__sems = defaultdict(Semaphore(3))


    @Retry(max_tries=2, pause=3)
    async def do_request(self: ClientSession, request: ClientRequest) -> ClientResponse:
        self.__sems[request.args[1]].acquire()
        
        if (response := await request()).status != 200:
            data = await response.json()
            
            if response.status == 429 and "retry_after" in data:
                await sleep(data["retry_after"]+2)
            
            self.__sems[0].release()
            return await request()
        
        self.__sems[0].release()
        return response
            

    @Retry(max_tries=2, pause=3)
    async def request(
        self: ClientSession,
        method: str, url: Optional[str] = None,
        *args: Tuple[Any], 
        **kwargs: Dict[ str, Any ]
    ) -> Optional[ClientResponse | dict]:
        if method and not url:
            method, url = "GET", method
            
        if kwargs.pop("proxy", False):
            kwargs["proxy"] = choice(PROXIES)
        
        try:
            return await self._process_response(
                parse_json=kwargs.pop("as_models", True),
                response=await self.do_request(
                    PartialFunction(
                        super().request, method, url, *args, raise_for_status=False, **kwargs
                    )
                )
            )
        
        except ClientResponseError as error:
            raise CommandError(f"HTTP Error: {error.status}: {error.message}") from error
            

    async def _process_response(
        self: ClientSession, 
        response: ClientResponse, parse_json: bool = True
    ) -> BaseModel | BeautifulSoup | bytes | ClientResponse:
        if response.content_type == "text/html":
            return BeautifulSoup(await response.text(), "lxml")
        
        if response.content_type == "text/plain":
            return await response.text()
            
        if response.content_type in ("application/json", "application/octet-stream", "text/javascript"):
            data = await response.json(content_type=None)
            
            if isinstance(data, list):
                if not isinstance(next(iter(data), None), dict):
                    return data
                    
                for item in data:
                    if isinstance(item, dict):
                        normalize_dict(item)
                
            else:
                normalize_dict(data)
                
            if parse_json:
                try:
                    return create_model(data)
                except Exception as exception:
                    raise ValueError from exception
                    
            return data
        
        if response.content_type.startswith(("image/", "video/", "audio/")):
            return await response.read()
        
        return response