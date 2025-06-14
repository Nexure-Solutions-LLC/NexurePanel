# Author: Treyten
from __future__ import annotations

from asyncio import Event, Lock, sleep, timeout as Timeout, TimeoutError
from discord import ButtonStyle, Color, Embed, Interaction
from discord.ui import button as Button, View
from typing import TYPE_CHECKING

if TYPE_CHECKING:
    from bot.utils.patch import Context

__all__ = ("Confirmation")


class Confirmation(View):
    def __init__(self, context: Context, *, message: str = "Are you sure you want to do this?"):
        super().__init__(timeout=15)
        
        self.__event = Event()
        self.__lock = Lock()
        
        self.context = context
        self.message_ = message
        
        self.message = None
        self.value = False
        
        
    async def on_timeout(self):
        for child in self.children:
            await sleep(1e-3)
            child.disabled = True
            
        await self.message.edit(view=self)
        self.stop()
            
            
    async def process_confirmation(self, interaction: Interaction, *, value: bool):
        await interaction.response.defer()
        
        if interaction.user != self.context.author:
            return await interaction.followup.send(
                ephemeral=True,
                embed=Embed(color=Color.red(), description="> You are not permitted to interact with this.")
            )
            
        await self.on_timeout()
        
        self.value = value
        self.__event.set()
        
        
    async def __aenter__(self, *_) -> bool:
        assert not self.__event.is_set()
        
        self.message = await self.context.send_warning(self.message_)
        await self.message.edit(view=self)
        
        try:
            async with self.__lock, Timeout(self.timeout):
                await self.__event.wait()
                return self.value
                
        except TimeoutError:
            return False
            
            
    async def __aexit__(self, *_):
        return
            
            
    @Button(
        label="Approve",
        style=ButtonStyle.green
    )
    async def accept(self, interaction: Interaction, *_):
        await self.process_confirmation(interaction, value=True)
        
    
    @Button(
        label="Decline",
        style=ButtonStyle.red
    )
    async def decline(self, interaction: Interaction, *_):
        await self.process_confirmation(interaction, value=False)