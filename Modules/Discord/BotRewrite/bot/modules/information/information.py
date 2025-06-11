from __future__ import annotations
from bot.utils.converter import dominant_color, Member, User

from discord.ext.commands import (
    Author,
    BucketType,
    Cog,
    command as Command,
    cooldown as Cooldown,
    group as Group,
    hybrid_command as HybridCommand,
    hybrid_group as HybridGroup,
    max_concurrency as MaxConcurrency
)

from asyncio import gather
from datetime import datetime as Date
from discord import ButtonStyle, Embed, Member as DiscordMember, User as DiscordUser
from discord.ui import Button, View
from discord.utils import format_dt as FormatDate
from humanize import ordinal as Ordinal
from typing import Literal, Optional, Union


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
        
        await ctx.respond(f"**WebSocket latency:** `{round(ctx.bot.latency*1000, 2)}ms`\n> **Processing latency:** `{processing_latency:.2f}ms`\n> **REST latency:** `Calculating...`", title="Network Information")
        return await ctx.respond(
            f"**WebSocket latency:** `{round(ctx.bot.latency*1000, 2)}ms`\n> **Processing latency:** `{processing_latency:.2f}ms`\n> **REST latency:** `{(Date.now().timestamp() - rest_start.timestamp()) * 1e3:.2f}ms`",
            title="Network Information", edit=True
        )
    
    
    @HybridGroup(
        name="user",
        aliases=("member", "u"),
        usage="<subcommand>",
        example="info @nickderry24",
        invoke_without_command=True
    )
    async def user(self, ctx: Context):
        """Get details about a user."""
        return await ctx.send_help(ctx.command.qualified_name)
    

    @user.command(
        name="avatar",
        aliases=("pfp",),
        usage="<user USER>",
        example="@nickderry24"
    )
    async def user_avatar(self, ctx: Context, user: Optional[User] = Author):
        """Get the avatar of a user."""
        return await ctx.reply(
            view=(
                View()
                .add_item(Button(
                    label="WEBP",
                    style=ButtonStyle.link,
                    url=user.display_avatar.replace(size=4096, format="webp").url
                ))
                .add_item(Button(
                    label="PNG",
                    style=ButtonStyle.link,
                    url=user.display_avatar.replace(size=4096, format="png").url
                ))
                .add_item(Button(
                    label="JPG",
                    style=ButtonStyle.link,
                    url=user.display_avatar.replace(size=4096, format="jpg").url
                ))
            ),
            embed=ctx.default_embed.set_image(url=user.display_avatar.url).set_color(await dominant_color(user.display_avatar))
        )
    

    @user.command(
        name="info",
        aliases=("details", "stats", "profile"),
        usage="<user USER>",
        example="@nickderry24"
    )
    async def user_info(self, ctx: Context, user: Optional[Union[ DiscordMember, DiscordUser ]] = Author):
        """Get information about a user."""

        if user == ctx.bot.user:
            user = ctx.guild.me

        if hasattr(user, "joined_at"):
            if len(mentions := list(map(lambda role: role.mention, reversed(user.roles[1:])))) > 5:
                roles = ", ".join(mentions[:5]) + f" and {len(mentions) - 5} more"
            else:
                roles = ", ".join(mentions + ["@everyone"])

        embed = (
            Embed(title=user, color=await dominant_color(user.display_avatar))
            .set_author(name=f"{user.name} ({user.id})", icon_url=user.display_avatar)
            .set_thumbnail(url=user.display_avatar)
            .set_footer(text="" if not getattr(user, "joined_at", None) else "   \u2022   ".join(filter(None, [
                "Administrator" if user.guild_permissions.administrator else "Create Invite" if user.guild_permissions.create_instant_invite else "No Permissions",
                f"Join position: {Ordinal(sorted(ctx.guild.members, key=lambda m: m.joined_at).index(user)+1)}",
                ("No mutual guilds" if not user.mutual_guilds else f"{len(user.mutual_guilds)} mutual guild{'s' if len(user.mutual_guilds) != 1 else ''}") if user != ctx.bot.user else "Mutual guilds N/A"
            ]))) 
            .add_field(name="Created", value=FormatDate(user.created_at, style="R"))
            .add_field(name="Joined", value="N/A" if not getattr(user, "joined_at", None) else FormatDate(user.joined_at, style="R"))
            .add_field(name="Boosted", value="N/A" if not getattr(user, "premium_since", None) else FormatDate(user.premium_since, style="R"))
        )

        if hasattr(user, "joined_at"):
            embed.add_field(name=f"Roles [{len(user.roles)}]", value=roles, inline=False)

        fetched_user = await ctx.bot.fetch_user(user.id)
        banner_url = fetched_user.banner.url if fetched_user.banner else None

        await ctx.reply(embed=embed, view=(
            View()
            .add_item(Button(label="Avatar", style=ButtonStyle.link, url=user.display_avatar.url))
            .add_item(Button(label="Banner", style=ButtonStyle.link, url=banner_url or "https://example.com", disabled=not banner_url))
        ))
