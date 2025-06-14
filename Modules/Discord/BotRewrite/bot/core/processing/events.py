# Author: Treyten
from __future__ import annotations

from discord.ext.commands import (
    BucketType,
    CooldownMapping,
    BadBoolArgument,
    BadInviteArgument,
    BadLiteralArgument,
    BadUnionArgument,
    BotMissingPermissions,
    ChannelNotFound,
    CheckFailure,
    CommandError,
    CommandNotFound,
    CommandOnCooldown,
    EmojiNotFound,
    GuildNotFound,
    GuildStickerNotFound,
    HybridCommandError,
    MaxConcurrencyReached,
    MemberNotFound,
    MissingPermissions,
    NotOwner,
    RoleNotFound,
    UserNotFound
)

from discord import (
    AppCommandOptionType,
    AuditLogEntry,
    Guild,
    Interaction,
    Message,
    Permissions
)

from discord.app_commands import (
    AppCommandError,
    CommandInvokeError,
    TransformerError
)

from asyncio import sleep
from collections import deque
from datetime import timedelta as TimeDelta
from discord.utils import oauth_url as OAuthURL
from humanize import naturaldelta as NaturalDelta
from traceback import format_exception as Traceback
from tuuid import tuuid as TUUID
from typing import Any, Dict, TYPE_CHECKING

import regex as re

if TYPE_CHECKING:
    from bot import NexureClient
    from bot.utils.patch import Context

def multi_replace(text: str, to_replace: Dict[str, str], once: bool = False) -> str:
    if once:
        pattern = '|'.join(re.escape(key) for key in to_replace)
        return re.sub(pattern, lambda m: to_replace[m.group(0)], text, 1)
    
    pattern = re.compile('|'.join(re.escape(key) for key in to_replace))
    return pattern.sub(lambda m: to_replace[m.group(0)], text)

class NoReturn(Exception):
    pass
    
NEWLINE = "\n"
SMART_QUOTE_REPLACEMENT_DICT = {
    "\u2018": "'", "\u2019": "'",
    "\u201c": '"', "\u201d": '"',
    "\u0027": "'", "\u0022": '"'
}; SMART_QUOTE_REPLACE_RE = re.compile("|".join(SMART_QUOTE_REPLACEMENT_DICT.keys()))
    
def normalize_smartquotes(to_normalize: str) -> str:
    def replacement_for(obj):
        return SMART_QUOTE_REPLACEMENT_DICT.get(obj.group(0), "")

    return SMART_QUOTE_REPLACE_RE.sub(replacement_for, to_normalize)


class Events:
    def __init__(self, bot: NexureClient):
        bot.exceptions = dict()
        self.command_cooldown = CooldownMapping.from_cooldown(
            2, 4, BucketType.member
        )
        
        
        @bot.before_invoke
        async def before_commands(ctx: Context):
            await bot.wait_until_ready()
            
            if not ctx.guild.chunked:
                await ctx.guild.chunk(cache=True)
                
            if not ctx.interaction and ctx.command.extras.get("interaction", False) is True:
                return
                
            if ctx.interaction:
                if ctx.command.extras.get("defer", True) is True:
                    await ctx.defer(ephemeral=ctx.command.extras.get("ephemeral", (ctx.command.parent.extras.get("ephemeral", False)) if ctx.command.parent else False))
            
            else:
                if not await bot.redis.ratelimited(f"commands:typing:{ctx.channel.id}", 3, 10):
                    await ctx.typing()
                    
                    
        @bot.check
        async def command_check(ctx: Context) -> bool:
            await bot.wait_until_ready()
            
            if not ctx.interaction and ctx.command.extras.get("interaction", False) is True:
                raise NoReturn()
                
            if await bot.is_owner(ctx.author):
                return True
                
            async with bot.redis.get_lock(f"commands:usage:{ctx.author.id}"):
                if (retry_after := self.command_cooldown.get_bucket(ctx.message).update_rate_limit()):
                    raise CommandOnCooldown(None, retry_after, None)

            return True
            
            
        @bot.event
        async def on_ready():
            bot.logger.info(f"Logged in as {bot.user} (Application ID: {bot.user.id})")
            bot.loop.create_task(bot.load_extensions())
            
            bot.invite = OAuthURL(
                bot.user.id, permissions=Permissions(8)
            )
            
            async def chunk_guild(guild: Guild):
                await sleep(1.5)
                if not guild.chunked:
                    await guild.chunk(cache=True)

            for guild in sorted(
                bot.guilds,
                key=lambda g: g.member_count, reverse=True
            ):
                if not guild.chunked:
                    await sleep(1e-3)
                    await bot.loop.create_task(chunk_guild(guild))
                    
            bot.guilds_chunked.set()
                    
        
        @bot.event      
        async def on_message(message: Message):
            await bot.wait_until_ready()
            
            if message.author.bot or not message.guild:
                return
            
            message.content = normalize_smartquotes(message.content)
            bot.loop.create_task(bot.process_commands(message))
                
              
        @bot.event  
        async def on_message_edit(_, after: Message) -> NoReturn:
            await bot.wait_until_ready()
            
            if after.author.bot or not after.guild:
                return
                
            after.content = normalize_smartquotes(after.content)
            bot.loop.create_task(bot.process_commands(after))
            
        
        @bot.event
        async def on_audit_log_entry_create(entry: AuditLogEntry):
            if entry.guild.id not in bot._audit_log_cache:
                bot._audit_log_cache[entry.guild.id] = deque()
                
            bot._audit_log_cache[entry.guild.id].append(entry)
                    
        
        @bot.event
        async def on_command_error(ctx: Context, error: Exception) -> Any:
            if isinstance(error, NoReturn):
                return
                
            if isinstance(error, (AppCommandError, CommandInvokeError, HybridCommandError)) and hasattr(error, "original"):
                error = error.original
                
            if isinstance(error, (AssertionError, CommandNotFound)):
                return
            
            if isinstance(getattr(error, "original", error), (CheckFailure, NotOwner)):
                return await ctx.send_error("You are not permitted to run this command.")
            
            # - -- - -- - -- - -- - -- - -- - #
            # - -- - -- - -- - -- - -- - -- - #
            
            if isinstance(error, CommandOnCooldown):
                if await bot.redis.ratelimited(f"cooldown_message:{ctx.author.id}", 1, int(error.retry_after)):
                    return
                
                return await ctx.send_error(
                    f"You're on a [**cooldown**](https://discord.com/developers/docs/topics/rate-limits) & cannot use `{ctx.invoked_with}` for **{NaturalDelta(TimeDelta(seconds=error.retry_after))}**.",
                    delete_after=error.retry_after
                )
            
            # - -- - -- - -- - -- - -- - -- - #
            # - -- - -- - -- - -- - -- - -- - #
            
            if isinstance(error, CommandError):
                ret = str(error)[29:].capitalize().rstrip(".")+"."
                return await ctx.send_error(ret, delete_after=None)
            
            if isinstance(error, TransformerError):
                if error.type == AppCommandOptionType.user and "to Member" in str(error):
                    return await ctx.send_error("I couldn't find that member.")

                if error.type == AppCommandOptionType.user:
                    return await ctx.send_error("I couldn't find that user.")

                if error.type == AppCommandOptionType.channel:
                    return await ctx.send_error("I couldn't find that channel.")

                if error.type == AppCommandOptionType.role:
                    return await ctx.send_error("I couldn't find that role.")
                
                if error.type == AppCommandOptionType.boolean:
                    return await ctx.send_error("Please provide a **valid** true/false value.")
                
            # - -- - -- - -- - -- - -- - -- - #
            # - -- - -- - -- - -- - -- - -- - #
            
            if isinstance(error, BotMissingPermissions):
                permission = error.missing_permissions[0].lower().replace("_", " ").title()
                return await ctx.send_error(f"I'm missing the **{permission}** permission.")

            if isinstance(error, MissingPermissions):
                permission = error.missing_permissions[0].lower().replace("_", " ").title()
                return await ctx.send_error(f"You're missing the **{permission}** permission.")
                
            # - -- - -- - -- - -- - -- - -- - #
            # - -- - -- - -- - -- - -- - -- - #
            
            if isinstance(error, MemberNotFound):
                return await ctx.send_error("I couldn't find that member.")

            if isinstance(error, UserNotFound):
                return await ctx.send_error("I couldn't find that user.")

            if isinstance(error, ChannelNotFound):
                return await ctx.send_error("I couldn't find that channel.")

            if isinstance(error, RoleNotFound):
                return await ctx.send_error("I couldn't find that role.")

            if isinstance(error, EmojiNotFound):
                return await ctx.send_error("I couldn't find that emoji.")
            
            if isinstance(error, GuildStickerNotFound):
                return await ctx.send_error("I couldn't find that sticker.")

            if isinstance(error, GuildNotFound):
                return await ctx.send_error("I couldn't find that guild.")

            if isinstance(error, BadInviteArgument):
                return await ctx.send_error("I couldn't find that invite.")
            
            if isinstance(error, BadBoolArgument):
                return await ctx.send_error("Please provide a **valid** true/false value.")
            
            if isinstance(error, BadLiteralArgument):
                return await ctx.send_error(f"The parameter **{error.param.name}** must be one of the following: \n```{NEWLINE.join(error.literals)}```")
                
            if isinstance(error, MaxConcurrencyReached):
                return await ctx.send_error(f"This command can only be run {error.number} time{'s' if error.number > 1 else ''} concurrently {'globally' if error.per.name == 'default' else f'per {error.per.name}'}.")
        
            if isinstance(error, BadUnionArgument):
                return await ctx.send_error(
                    multi_replace(
                        error.args[0].lower(), {
                            "could not convert": "i couldn't convert the parameter",
                            '"': "**", 
                            "into": "into a", 
                            "member": "**member**", 
                            "user": "**user**", 
                            "guild": "**server**", 
                            "invite": "**server invite**"
                        }
                    )[:-1].capitalize()+"."
                )
        
            bot.exceptions[error_id := TUUID()] = {
                "context": ctx,
                "error": "\n".join(Traceback(error))
            }
            
            return await ctx.send_error(
                "The command you were attempting to run failed.",
                title=f"Traceback ID: {error_id}"
            )
            
    
        @bot.tree.error
        async def on_app_command_error(interaction: Interaction, error: AppCommandError):         
            context = await bot.get_context(interaction)
            bot.dispatch("command_error", context, error)
    
    