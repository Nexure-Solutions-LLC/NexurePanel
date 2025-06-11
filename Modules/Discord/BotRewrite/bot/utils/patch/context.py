
from __future__ import annotations
from ..paginator import embed_creator, Paginator, text_creator

from discord import (
    Color,
    Embed,
    HTTPException,
    Message
)

from discord.ext.commands import (
    CommandError,
    Context as DefaultContextC,
    Flag,
    FlagConverter
)

from datetime import datetime as Date
from discord.utils import cached_property as CachedProperty, find as discord_find
from functools import partial as PartialFunction
from io import StringIO
from munch import Munch

from typing import (
    Any,
    Dict,
    List,
    Optional,
    Tuple,
    Union
)


class Context(DefaultContextC):
    __slots__ = (
        "response",
        "send_success",
        "send_error"
    )
    
    def __init__(
        self: Context,
        *args: Tuple[Any], **kwargs: Dict[ str, Any ]
    ):
        super().__init__(*args, **kwargs)
        
        self.response = None
        self.send_success = PartialFunction(self.respond, color=Color.green())
        self.send_error = PartialFunction(self.respond, color=Color.red())
        
        
    @CachedProperty
    def default_embed(self: Context) -> Embed:
        return Embed(color=self.bot.config.colors.main, timestamp=Date.now()).set_author(
            name="Nexure Solutions", 
            icon_url=self.bot.user.display_avatar
        )
        
    
    async def send_or_reply(
        self: Context,
        *args: Tuple[Any], **kwargs: Dict[ str, Any ]
    ) -> Message:
        try:
            return await self.reply(*args, **kwargs)
        
        except HTTPException:
            return await self.send(*args, **kwargs)
        
        
    async def send(
        self: Context,
        *args: Tuple[Any], **kwargs: Dict[ str, Any ]
    ) -> Message:
        
        if kwargs.pop("cooldown", True) and await self.bot.redis.ratelimited(f"message:send:{self.channel.id}", 2, 4):
            return

        if (sem := self.bot.redis.get_semaphore(f"message:send:{self.channel.id}", 2)).locked():
            return
        
        async with sem:
            self.response = await super().send(*args, **kwargs)
            return self.response
        
        
    async def respond(
        self: Context,
        *args: Tuple[Any], **kwargs: Dict[ str, Any ]
    ) -> Message:
        if args and isinstance(args[0], str):
            kwargs["message"] = args[0]
        
        return await (self.response.edit if kwargs.pop("edit", False) else self.send_or_reply)(
            content=kwargs.pop("content", None),
            delete_after=kwargs.pop("delete_after", None),
            embed=Embed(
                color=kwargs.pop("color", self.bot.config.colors.main),
                title=kwargs.pop("title", None),
                description=f"> {kwargs.pop('message')}"
            ) if "message" in kwargs else None
        )
        

    async def paginate(
        self: Context,
        entries: Union[ Tuple[ Embed, List[str] ], List[Embed], str ],
        *, embed: Optional[Embed] = None,
        patch: Optional[Message] = None,
        per_page: int = 10,
        max_size: int = 1980,
        show_index: bool = True,
    ) -> Optional[Message]:
        if not entries or (isinstance(entries, tuple) and not entries[1]):
            raise CommandError("> A response was meant to be formatted but no entries were provided.")
        
        paginator = PartialFunction(
            Paginator,
            bot=self.bot,
            destination=self,
            patch=patch
        )
            
        if isinstance(entries, tuple) or (isinstance(entries, list) and entries and not isinstance(entries[0], str)):
            if isinstance(entries, list):
                return await paginator(pages=entries).start()
                
            embed, rows = entries
    
            if not isinstance(rows, list):
                raise TypeError
                
            pages = []
            for n in range(0, len(rows), per_page):
                page_rows = rows[n:n+per_page]
                page_embed = embed.copy()
                page_embed.description = "\n".join(f"{f'`{index}` ' if show_index else ''}{row}" for index, row in enumerate(page_rows, start=n+1))
                page_embed.set_footer(text=f"Page {n // per_page + 1} / {len(rows) // per_page + (1 if len(rows) % per_page else 0)}  ({len(rows)} {'entries' if len(rows) > 1 else 'entry'})")
                page_embed.set_author(name=self.author, icon_url=self.author.display_avatar)
                pages.append(page_embed)
    
            await paginator(pages=pages).start()
    
        elif isinstance(entries, list):
            return await paginator(
                pages=entries if not any(len(entry)>max_size for entry in entries) else [text_creator(entry, max_size) for entry in entries]
            ).start()
    
        elif isinstance(entries, str):
            return await self.paginate((
                embed or self.default_embed,
                list(text_creator(entries, max_size))
                ),
                per_page=per_page,
                max_size=max_size,
                show_index=show_index
            )
