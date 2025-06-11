from __future__ import annotations
from bot.utils.converter import User

from discord.ext.commands import (
    BucketType,
    Cog,
    command as Command,
    cooldown as Cooldown,
    group as Group,
    hybrid_command as HybridCommand,
    max_concurrency as MaxConcurrency
)

from asyncio import gather
from datetime import datetime as Date
from typing import Literal, Optional


class Information(Cog):
    def __init__(self, bot: Bot):
        self.bot = bot


    @HybridCommand(
        name="latency",
        aliases=("ping",),
        usage=None, example=None
    )
    async def latency(self, ctx: Context):
        """Check the bot's WebSocket, processing and REST latency."""
        processing_latency = (Date.now().timestamp()-ctx.message.created_at.timestamp()) * 1e3
        rest_start = Date.now()
        
        await ctx.respond(f"**WebSocket latency:** `{round(self.bot.latency*1000, 2):.2f}ms`\n> **Processing latency:** `{processing_latency:.2f}ms`\n> **REST latency:** `Calculating...`", title="Network Information")
        return await ctx.respond(
            f"**WebSocket latency:** `{round(self.bot.latency*1000, 2)}ms`\n> **Processing latency:** `{processing_latency:.2f}ms`\n> **REST latency:** `{(Date.now().timestamp() - rest_start.timestamp()) * 1e3:.2f}ms`",
            title="Network Information", edit=True
        )