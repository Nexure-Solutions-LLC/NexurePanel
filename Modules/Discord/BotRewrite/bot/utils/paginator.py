from __future__ import annotations

from discord import (
    ButtonStyle,
    Color,
    Embed,
    HTTPException,
    Interaction,
    Message,
    TextStyle
)

from asyncio import gather, TimeoutError
from contextlib import suppress
from discord.abc import GuildChannel
from discord.ext.commands import Context
from discord.utils import cached_property as CachedProperty, MISSING
from discord.ui import Button, Modal, TextInput, View
from typing import Any, Iterable, List, Optional, Union


class PaginatorModal(Modal, title="Advanced Pagination"):
    __slots__ = ("button", "page_number")

    def __init__(self, button: PaginatorButton) :
        super().__init__()

        self.button = button
        
        self.page_number = TextInput(
            label="Page Menu",
            placeholder="Type a number to navigate to its corresponding page",
            style=TextStyle.short,
            required=True
        )
        
        self.add_item(self.page_number)
        

    async def on_submit(self, interaction: Interaction) :
        await interaction.response.defer()
        
        view = self.button.view
        value = self.page_number.value

        if not (value.isdigit() and 0 < int(value) <= len(view.pages)):
            return await interaction.followup.send(ephemeral=True, embed=Embed(
                color=interaction.client.config.colors.main,
                description=f"> Please provide a **valid** number from `1` to `{len(view.pages)}`.",
            ))

        view.current_page = int(value)-1
        await view.update_page()


class PaginatorButton(Button):
    __slots__ = ()

    def __init__(
        self, 
        emoji: str, style: ButtonStyle, custom_id: str
    ) :
        super().__init__(
            emoji=emoji, 
            style=style, 
            custom_id=custom_id
        )
        
        self.disabled = False
        

    async def callback(self, interaction: Interaction) :

        if self.custom_id == "previous":
            self.view.current_page = (self.view.current_page - 1) % len(self.view.pages)
            
        elif self.custom_id == "next":
            self.view.current_page = (self.view.current_page + 1) % len(self.view.pages)
            
        elif self.custom_id == "navigate":
            return await interaction.response.send_modal(PaginatorModal(self))
            
        elif self.custom_id == "cancel":
            await interaction.response.defer()
            
            self.view.stop()
            return await self.view.message.delete()

        await interaction.response.defer()
        await self.view.update_page()


class Paginator(View):
    __slots__ = (
        "bot", "destination", "current_page",
        "pages", "patch", "message"
    )
    
    def __init__(
        self, 
        bot: Any, destination: Union[ Context, GuildChannel, Interaction ], 
        pages: List[Union[ str, Embed ]], patch: Optional[Message] = None
    ):
        super().__init__(timeout=60)
        
        self.bot = bot
        self.destination = destination
        self.current_page = 0
        self.pages = pages
        self.patch = patch
        self.message = None
        
        self.add_buttons()
        

    def add_buttons(self) :
        for emoji, style, custom_id in (
            ("⬅️", ButtonStyle.blurple, "previous"), ("➡️", ButtonStyle.blurple, "next"),
            ("🔢", ButtonStyle.grey, "navigate"), ("❌", ButtonStyle.red, "cancel")
        ):
            self.add_item(PaginatorButton(
                emoji=emoji, 
                style=style, 
                custom_id=custom_id
            ))
            

    @CachedProperty
    def type(self) -> str:
        return "embed" if isinstance(self.pages[0], Embed) else "text"
    

    async def start(self) :
        if len(self.pages) == 1:
            return await self.send_page(self.pages[0])
        
        return await self.send_page(self.pages[self.current_page], view=self)
            

    async def send_page(
        self, 
        content: Union[ str, Embed ], view: Optional[Paginator] = None
    ) :
        func = self.patch.edit if self.patch != None else self.destination.send if not isinstance(self.destination, Interaction) else (self.destination.followup.send if self.destination.followup else self.destination.channel)
        
        if self.type == "embed":
            self.message = await func(embed=content, view=(view if not isinstance(self.destination, Interaction) else (view or MISSING)))
            return
            
        self.message = await func(content=content, view=(view if not isinstance(self.destination, Interaction) else (view or MISSING)))
            

    async def interaction_check(self, interaction: Interaction) -> Union[ bool, Message ]:
        if interaction.user.id != (self.destination.author if not isinstance(self.destination, Interaction) else self.destination.user).id:
            return await interaction.response.defer()
        
        return True
    
    
    async def disable_buttons(self) :
        for child in self.children:
            child.disabled = True
            
        await self.message.edit(view=self)
        
        
    async def reset_buttons(self: Paginator) :
        for child in self.children:
            child.disabled = False
            
        await self.message.edit(view=self)
        

    async def update_page(self) :
        page = self.pages[self.current_page]
        
        if self.type == "embed":
            return await self.message.edit(embed=page, view=self)
            
        return await self.message.edit(content=page, view=self)
    
    
    async def on_timeout(self) :
        with suppress(HTTPException):
            await self.disable_buttons()
            
            
def chunk(_list: List[Any], n: int) -> Iterable[List[Any]]:
    for i in range(0, len(_list), n):
        yield _list[i:i+n]
            
            
def embed_creator(
    text: str, num: int = 1980, 
    /, *, 
    title: str = "", prefix: str = "", 
    suffix: str = "", color: int = 0xB1AAD8
) -> tuple:
    return tuple(
        Embed(
            title=title,
            description=prefix + text[i:i+num] + suffix,
            color=color if color != None else Color.dark_embed(),
        )
        for i in range(0, len(text), num)
    )
    

def text_creator(
    text: str, num: int = 1980, 
    /, *, 
    prefix: str = "", suffix: str = ""
) -> tuple:
    return tuple(
        prefix + text[i:i+num] + suffix
        for i in range(0, len(text), num)
    )
    
    
def field_creator(
    embed: Embed, fields: list, 
    footer: Optional[dict] = None, per_page: int = 5
) -> list:
    embeds = []

    for index, fields_chunk in enumerate(chunk(fields, per_page), start=1):
        _embed = embed.copy()
        _embed._fields = fields_chunk

        if footer:
            _embed.set_footer(
                text=footer["text"].format(index=index, total=int(len(fields)/per_page)+1),
                icon_url=footer.get("icon_url")
            )

        embeds.append(_embed)
        del fields_chunk

    return embeds