from __future__ import annotations

from discord.ext import commands
from discord.ext.commands import (
    Context,
    CommandError,
    Converter,
    GuildConverter,
    MemberConverter,
    MessageConverter,
    TextChannelConverter,
    RoleConverter,
    RoleNotFound,
    UserConverter
)

from discord import Color as DefaultColorC
from fast_string_match import closest_match
from matplotlib.colors import cnames as CNames
from munch import Munch
from typing import Any, Union, Optional

__all__ = (
    "Guild", "GuildConverter",
    "Member", "MemberConverter",
    "Message", "MessageConverter",
    "TextChannel", "TextChannelConverter",
    "Role", "RoleConverter",
    "User", "UserConverter",
)


class BaseConverter(Converter):
    async def convert(
        self,
        ctx: Context, argument: str
    ) -> Any:
        if argument.isnumeric():
            id = int(argument)

            if await ctx.bot.redis.sismember(f"invalid_ids:{self.__class__.__name__.lower()}", id):
                raise getattr(commands, f"{self.__class__.__name__.title()}NotFound")(argument)

            if not (16 <= len(argument) <= 20):
                await ctx.bot.redis.sadd(f"invalid_ids:{self.__class__.__name__.lower()}", id)
                raise getattr(commands, f"{self.__class__.__name__.title()}NotFound")(argument)

        try:
            return await super().convert(ctx, argument)

        except getattr(commands, f"{self.__class__.__name__.title()}NotFound"):
            if argument.isnumeric():
                await ctx.bot.redis.sadd(f"invalid_ids:{self.__class__.__name__.lower()}", id)
            raise


class Guild(BaseConverter, GuildConverter):
    pass


class User(BaseConverter, UserConverter):
    pass


class Member(BaseConverter, MemberConverter):
    async def can_moderate(
        self, 
        ctx: "Context", member: Member, 
        *, action: str = "moderate"
    ) -> Munch:
        message = ""
        
        if member.id == ctx.author.id:
            message = f"You can't **{action}** yourself."
        
        if (member.top_role >= ctx.author.top_role and ctx.author.id != ctx.guild.owner_id) or member.id == ctx.guild.owner_id:
            message = f"You can't **{action}** that member."
            
        if member.id == ctx.bot.user.id:
            message = f"You can't **{action}** me."
            
        if (member.top_role >= ctx.guild.me.top_role and ctx.author.id != ctx.guild.owner_id):
            message = f"I can't **{action}** that member."
        
        return Munch(
            result=not message,
            message=message
        )


class Message(BaseConverter, MessageConverter):
    pass


class TextChannel(BaseConverter, TextChannelConverter):
    pass


class Role(RoleConverter):
    async def convert(
        self,
        ctx: Context, argument: str
    ) -> Any:
        if argument.isnumeric():
            id = int(argument)

            if await ctx.bot.redis.sismember("invalid_ids:role", id):
                raise RoleNotFound(argument)

            if not (16 <= len(argument) <= 20):
                await ctx.bot.redis.sadd("invalid_ids:role", id)
                raise RoleNotFound(argument)
                
        try:
            return await super().convert(
                ctx, (
                    argument
                    if argument.isnumeric() or "<@&" in argument
                    else closest_match(argument, tuple(map(lambda r: r.name, ctx.guild.roles)))
                )
            )

        except commands.RoleNotFound:
            if argument.isnumeric():
                await ctx.bot.redis.sadd("invalid_ids:role", int(argument))
            raise


class Color(Converter):
    async def convert(self, _, argument: str) -> Optional[Union[str, DefaultColorC]]:
        if argument.lower() in ("random", "rand", "r"):
            return DefaultColorC.random()

        if argument.lower() in ("invisible", "invis"):
            return DefaultColorC.dark_embed()

        if argument.lower() in ("blurple", "blurp"):
            return DefaultColorC.blurple()

        if argument.lower() in ("black", "dark"):
            return DefaultColorC.from_str("#000001")

        try:
            color = DefaultColorC.from_str(argument)

        except ValueError:
            if argument not in CNames:
                raise CommandError("I couldn't find that color.")

            color = DefaultColorC.from_str(CNames[argument])

        return color


GuildConverter = Guild
MemberConverter = Member
MessageConverter = Message
RoleConverter = Role
UserConverter = User
TextChannelConverter = TextChannel