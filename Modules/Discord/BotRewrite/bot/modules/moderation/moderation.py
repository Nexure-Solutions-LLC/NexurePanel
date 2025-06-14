# Author: Treyten
from __future__ import annotations
from bot.utils.converter import Member, Message, User
from bot.utils.views import Confirmation

from discord.ext.commands import (
    Author,
    BucketType,
    Cog,
    has_permissions as HasPermissions,
    hybrid_command as HybridCommand,
    hybrid_group as HybridGroup,
    max_concurrency as MaxConcurrency
)

from asyncio import gather as GatherTasks
from contextlib import suppress as SuppressException
from datetime import datetime as Date
from discord import Embed, HTTPException, Member as DiscordMember, User as DiscordUser
from typing import Literal, Optional, TYPE_CHECKING, Union

import regex

if TYPE_CHECKING:
    from bot import NexureClient
    from bot.utils.patch import Context


class Moderation(Cog):
    def __init__(self, bot: NexureClient):
        self.bot = bot
        

    async def cog_check(self, ctx: Context) -> bool:
        return ctx.me.guild_permissions.administrator
    

    async def log_moderation(
        self, moderator_id: int, member_id: int, 
        *, type: str, reason: str = "No reason provided"
    ):
        await self.bot.database.execute(
            """
            INSERT INTO nexure_moderations (moderator_id, member_id, type, reason, created_on, case_id) VALUES (
                %s, %s, %s, %s, %s, COALESCE((SELECT MAX(case_id) FROM nexure_moderations WHERE moderator_id = %s), 0) + 1);
            """,
            moderator_id, member_id, type, reason, Date.now(), moderator_id
        )


    async def do_checks(self, context: Context, member: Member, *, action: str) -> bool:
        if not (moderatable := (await Member().can_moderate(context, member, action=action))).result:
            await context.send_error(moderatable.message)
            return False
            
        if member.premium_since and action in ("ban", "softban", "kick"):
            async with Confirmation(context, message="That member is a server booster. Are you sure you want to remove them?") as confirmation:
                if confirmation is False:
                    await context.send_error("This action was cancelled by the user.", view=None, edit=True)
                    return False
                    
        return True
    
    
    async def notify_of_moderation(
        self, context: Context, member: Member,
        *, title: str, message: str, reason: Optional[str] = "No reason provided"
    ) -> Message:
        return await member.send(embed=(
            Embed(
                color=self.bot.config.colors.main, title=title,
                description=f"> {message} {context.guild.name}",
                timestamp=Date.now()
            )
            .set_author(name=context.guild.name, icon_url=context.guild.icon)
            .set_thumbnail(url=self.bot.user.display_avatar)
            .set_footer(text="Contact a moderator if you wish to dispute this punishment")
            .add_field(name="Moderator", value=context.author)
            .add_field(name="Reason", value=reason)
        ))
    

    @HybridGroup(
        name="purge",
        aliases=("clear", "c", "p"),
        usage="[member MEMBER DEFAULT ALL] <amount NUMBER>", example="@nickderry24 100",
        invoke_without_command=True
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge(self, ctx: Context, member: Optional[Member], amount: Optional[int] = 10):
        """Bulk delete messages from the channel with a variety of helper commands."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")
        
        if not 2 <= amount <= 1000:
            return await ctx.send_error("You can only clear between **1 and 1,000** messages.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                limit=amount+1,
                check=lambda m: (member.id == m.author.id) if member else True,
                reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
            ))}` messages.", delete_after=3)


    @purge.command(
        name="matches",
        aliases=("match", "contains"),
        usage="<expression TEXT> <amount NUMBER>", example="nigg.*? 100"
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge_matches(self, ctx: Context, expression: str, amount: Optional[int] = 10):
        """Bulk delete messages matching a regular expression."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")
        
        if not 2 <= amount <= 1000:
            return await ctx.send_error("You can only clear between **1 and 1,000** messages.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            try:
                return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                    limit=amount+1,
                    check=lambda m: bool(regex.search(expression, m.content, flags=regex.IGNORECASE)),
                    reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
                ))}` messages containing `{expression}`.", delete_after=3)
            
            except regex.error:
                return await ctx.send_error("The expression you provided is [invalid](https://docs.python.org/3/library/re.html#regular-expression-examples).")



    @purge.command(
        name="humans",
        aliases=("users", "people", "human", "humansonly", "h"),
        usage="<amount NUMBER>", example="100"
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge_humans(self, ctx: Context, amount: Optional[int] = 10):
        """Bulk delete messages from humans. This will not delete messages from bots."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")
        
        if not 2 <= amount <= 1000:
            return await ctx.send_error("You can only clear between **1 and 1,000** messages.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                limit=amount+1,
                check=lambda m: not m.author.bot,
                reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
            ))}` messages from humans.", delete_after=3)


    @purge.command(
        name="bots",
        aliases=("botsonly", "b"),
        usage="<amount NUMBER>", example="100"
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge_bots(self, ctx: Context, amount: Optional[int] = 10):
        """Bulk delete messages from bots. This will not delete messages from humans."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")
        
        if not 2 <= amount <= 1000:
            return await ctx.send_error("You can only clear between **1 and 1,000** messages.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                limit=amount+1,
                check=lambda m: m.author.bot,
                reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
            ))}` messages from bots.", delete_after=3)


    @purge.command(
        name="after",
        aliases=("since",),
        usage="<message MESSAGE>", example="1382763085911031839"
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge_after(self, ctx: Context, *, message: Message):
        """Bulk delete messages sent after the specified message. This will not delete the specified message itself."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                limit=None,
                after=message.created_at,
                reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
            ))}` messages after {message.jump_url}.", delete_after=3)


    @purge.command(
        name="stickers",
        aliases=("sticker", "stickeronly", "s"),
        usage="<amount NUMBER>", example="100"
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge_stickers(self, ctx: Context, amount: Optional[int] = 10):
        """Bulk delete messages with stickers. This will not delete messages without stickers."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")
        
        if not 2 <= amount <= 1000:
            return await ctx.send_error("You can only clear between **1 and 1,000** messages.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                limit=amount+1,
                check=lambda m: m.stickers,
                reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
            ))}` messages with stickers.", delete_after=3)


    @purge.command(
        name="attachments",
        aliases=("attachment", "images", "imageonly", "attachonly", "a", "i"),
        usage="<amount NUMBER>", example="100"
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge_attachments(self, ctx: Context, amount: Optional[int] = 10):
        """Bulk delete messages with attachments. This will not delete messages without attachments."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")
        
        if not 2 <= amount <= 1000:
            return await ctx.send_error("You can only clear between **1 and 1,000** messages.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                limit=amount+1,
                check=lambda m: m.attachments,
                reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
            ))}` messages with attachments.", delete_after=3)



    @purge.command(
        name="emojis",
        aliases=("emotes", "emoji", "emoteonly"),
        usage="<amount NUMBER>", example="100"
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge_emojis(self, ctx: Context, amount: Optional[int] = 10):
        """Bulk delete messages with emojis. This will not delete messages without emojis."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")
        
        if not 2 <= amount <= 1000:
            return await ctx.send_error("You can only clear between **1 and 1,000** messages.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                limit=amount+1,
                check=lambda m: m.emojis,
                reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
            ))}` messages with emojis.", delete_after=3)


    @purge.command(
        name="mentions",
        aliases=("pings", "mention", "ping", "mentiononly", "m"),
        usage="<amount NUMBER>", example="100"
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge_mentions(self, ctx: Context, amount: Optional[int] = 10):
        """Bulk delete messages with mentions. This will not delete messages without mentions."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")
        
        if not 2 <= amount <= 1000:
            return await ctx.send_error("You can only clear between **1 and 1,000** messages.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                limit=amount+1,
                check=lambda m: m.mentions,
                reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
            ))}` messages with mentions.", delete_after=3)


    @purge.command(
        name="upto",
        aliases=("before", "until", "u"),
        usage="<message MESSAGE>", example="1382763085911031839"
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge_upto(self, ctx: Context, *, message: Message):
        """Bulk delete messages sent before the specified message. This will not delete the specified message itself."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                limit=None,
                before=message.created_at,
                reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
            ))}` messages.", delete_after=3)


    @purge.command(
        name="embeds",
        aliases=("embed", "embedonly", "e"),
        usage="<amount NUMBER>", example="100"
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge_embeds(self, ctx: Context, amount: Optional[int] = 10):
        """Bulk delete messages with embeds in them. This will not delete messages without embeds."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")
        
        if not 2 <= amount <= 1000:
            return await ctx.send_error("You can only clear between **1 and 1,000** messages.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                limit=amount+1,
                check=lambda m: m.embeds,
                reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
            ))}` messages with embeds.", delete_after=3)


    @purge.command(
        name="reactions",
        aliases=("react", "reaction", "reactonly", "r"),
        usage="<amount NUMBER>", example="100"
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge_reactions(self, ctx: Context, amount: Optional[int] = 10):
        """Bulk delete messages with reactions. This will not delete messages without reactions."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")
        
        if not 2 <= amount <= 1000:
            return await ctx.send_error("You can only clear between **1 and 1,000** messages.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                limit=amount+1,
                check=lambda m: m.reactions,
                reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
            ))}` messages with reactions.", delete_after=3)


    @purge.command(
        name="links",
        aliases=("link", "url", "urls", "l"),
        usage="<amount NUMBER>", example="100"
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge_links(self, ctx: Context, amount: Optional[int] = 10):
        """Bulk delete messages with links in them. This will not delete messages without links."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")
        
        if not 2 <= amount <= 1000:
            return await ctx.send_error("You can only clear between **1 and 1,000** messages.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                limit=amount+1,
                check=lambda m: regex.search(r"(https?|s?ftp)://(\S+)", m.content, flags=regex.IGNORECASE),
                reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
            ))}` messages with links.", delete_after=3)


    @purge.command(
        name="webhooks",
        aliases=("whook", "wh", "webhook", "webhookonly"),
        usage="<amount>",
        example="100"
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge_webhooks(self, ctx: Context, amount: int):
        """Bulk delete messages sent by webhooks. This will not delete messages sent by users or bots."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")
        
        if not 2 <= amount <= 1000:
            return await ctx.send_error("You can only clear between **1 and 1,000** messages.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                limit=amount+1,
                check=lambda m: m.author.bot and not m.author.system and m.author.discriminator == "0000",
                reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
            ))}` messages with webhooks.", delete_after=3)
        

    @purge.command(
        name="between",
        aliases=("btw",),
        usage="<start MESSAGE> <finish MESSAGE>", example="1382749929910439937 1382750104431366318"
    )
    @MaxConcurrency(1, BucketType.channel, wait=False)
    @HasPermissions(manage_messages=True)
    async def purge_between(self, ctx: Context, start: Message, finish: Message):
        """Bulk delete messages between two specified messages. This will not delete the specified messages themselves."""

        if ctx.bot.redis.get_lock("purge_tasks").owned():
            return await ctx.send_error("There is already a purge in progress.")

        async with ctx.bot.redis.auto_lock("purge_tasks"):
            return await ctx.send_success(f"Successfully cleared `{len(await ctx.channel.purge(
                limit=None,
                before=finish.created_at, after=start.created_at,
                reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]"
            ))}` messages between {start.jump_url} and {finish.jump_url}.", delete_after=3)
        

    @HybridCommand(
        name="ban",
        aliases=("banish", "b"),
        usage="<member MEMBER> [delete days ENUM(0, 1, 7) DEFAULT 1] [reason TEXT DEFAULT 'No reason provided']",
        example="@nickderry24 1 Breaking the rules"
    )
    @HasPermissions(ban_members=True)
    async def ban(self, ctx: Context, user: Union[ DiscordMember, DiscordUser ], days: Optional[Literal[ 0, 1, 7 ]] = 0, *, reason: Optional[str] = "No reason provided"):
        """Ban the mentioned user from the server."""
        
        if await ctx.bot.redis.ratelimited(f"moderations:removal:{ctx.guild.id}", 15, 300):
            return await ctx.send_error("This resource is being rate limited.")
        
        if hasattr(user, "joined_at"):
            assert await self.do_checks(ctx, user, action="ban")
                
        if len(reason) > 64:
            return await ctx.send_error("Please provide a reason under 64 characters.")
        
        await ctx.guild.ban(
            user,
            delete_message_seconds=days*86400,
            reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]: {reason}"
        )
        await self.log_moderation(
            ctx.author.id, user.id, 
            type="Ban", reason=reason
        )

        if hasattr(user, "joined_at"):
            ctx.bot.loop.create_task(self.notify_of_moderation(
                ctx, user,
                title="Banned", message="You have been banned from", reason=reason
            ))
                
        return await ctx.send_success(f"Successfully banned {user.mention} for {reason == 'No reason provided' and 'no reason' or reason}.")
    
              
    @HybridCommand(
        name="unban",
        aliases=("unbanish", "ub"),
        usage="<user USER>", example="@nickderry24"
    )
    @HasPermissions(ban_members=True)
    async def unban(self, ctx: Context, user: User):
        """Unban the mentioned user from the server."""

        async for ban in ctx.guild.bans():
            if ban.user.id == user.id:
                await ctx.guild.unban(user, reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]")
                break
                
        else:
            return await ctx.send_error("That user is not banned.")
        
        ctx.bot.loop.create_task(self.notify_of_moderation(
            ctx, user,
            title="Unbanned", message="You have been unbanned from"
        ))

        await self.log_moderation(
            ctx.author.id, user.id, 
            type="Unban", reason=f"Manual unban by {ctx.author}."
        )
            
        return await ctx.send_success(f"Successfully unbanned {user.mention}.")
        
        
    @HybridCommand(
        name="softban",
        aliases=("softbanish", "sb"),
        usage="<member MEMBER> [delete days ENUM(0, 1, 7) DEFAULT 1] [reason TEXT DEFAULT 'No reason provided']",
        example="@nickderry24 Breaking the rules",
    )
    @HasPermissions(ban_members=True)
    async def softban(self, ctx: Context, member: Member, days: Optional[Literal[ 0, 1, 7 ]] = 1, *, reason: Optional[str] = "No reason provided"):
        """Softban the mentioned user from the server. This will ban and immediately unban them, deleting their messages in the process."""
        
        if await ctx.bot.redis.ratelimited(f"moderations:removal:{ctx.guild.id}", 15, 300):
            return await ctx.send_error("This resource is being rate limited.")
        
        assert await self.do_checks(ctx, member, action="softban")
                
        if len(reason) > 64:
            return await ctx.send_error("Please provide a reason under 64 characters.")

        #-  
        await ctx.guild.ban(
            member,
            delete_message_seconds=days*86400,
            reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]: {reason}"
        )
        await ctx.guild.unban(
            member,
            reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]: User was soft banned"
        )
        await self.log_moderation(
            ctx.author.id, member.id, 
            type="Softban", reason=reason
        )
        #-

        GatherTasks(
            self.notify_of_moderation(
                ctx, member,
                title="Soft-banned (Rejoin)", message="You have been soft-banned from", reason=reason
            ),
            ctx.send_success(f"Successfully soft banned {member.mention} for {reason == 'No reason provided' and 'no reason' or reason}.")
        )
            
    
    @HybridCommand(
        name="banned",
        aliases=("is_banned", "check_ban", "checkban", "checkbanned", "ib", "cb", "cban"),
        usage="<user USER>", example="@nickderry24"
    )
    @HasPermissions(ban_members=True)
    async def banned(self, ctx: Context, *, user: User):
        """Check if the mentioned user is banned from the server."""
        
        async for ban in ctx.guild.bans():
            if ban.user.id == user.id:
                return await ctx.send_success(f"{user} (`{user.id}`) is banned.")
                
        return await ctx.send_error(f"{user} (`{user.id}`) is not banned.")
    


    @HybridCommand(
        name="warn",
        aliases=("w", "warning", "warned", "warnmember", "wm"),
        usage="<member MEMBER> [reason TEXT DEFAULT 'No reason provided']",
        example="@nickderry24 Breaking the rules",
    )
    @HasPermissions(manage_messages=True)
    async def warn(self, ctx: Context, member: Member, *, reason: Optional[str] = "No reason provided"):
        """Warn the mentioned member in the server."""
        
        assert await self.do_checks(ctx, member, action="warn")
            
        if len(reason) > 64:
            return await ctx.send_error("Please provide a reason under 64 characters.")
                
        await self.log_moderation(
            ctx.author.id, member.id, 
            type="Warn", reason=reason
        )
        
        message_sent = False
        with SuppressException(HTTPException):
            await self.notify_of_moderation(
                ctx, member,
                title="Warning", message="You have been warned in", reason=reason
            )
            message_sent = True
            
        GatherTasks(
            ctx.message.add_reaction("👍"),
            ctx.send(f"{member.mention}, you have been warned for doing something stupid which broke the rules{reason != 'No reason provided' and f', specifically {reason}.' or '.'} {message_sent and 'You can find more information about this warning in your private messages.' or ''}")
        )

        
    @HybridCommand(
        name="kick",
        aliases=("kickmember", "k",),
        usage="<member MEMBER> [reason TEXT DEFAULT 'No reason provided']",
        example="@nickderry24 Breaking the rules",
    )
    @HasPermissions(kick_members=True)
    async def kick(self, ctx: Context, member: Member, *, reason: Optional[str] = "No reason provided"):
        """Kick the mentioned member from the server."""
        
        if await ctx.bot.redis.ratelimited(f"moderations:removal:{ctx.guild.id}", 15, 300):
            return await ctx.send_error("This resource is being rate limited.")
        
        assert await self.do_checks(ctx, member, action="kick")

        if len(reason) > 64:
            return await ctx.send_error("Please provide a reason under 64 characters.")
            
        await ctx.guild.kick(member, reason=f"{ctx.bot.user.name.title()} Moderation [{ctx.author}]: {reason}")
        await self.log_moderation(
            ctx.author.id, member.id, 
            type="Kick", reason=reason
        )

        GatherTasks(
            self.notify_of_moderation(
                ctx, member,
                title="Kicked", message="You have been kicked from", reason=reason
            ),
            ctx.send_success(f"Successfully **kicked** {member.mention} for {'no reason' if reason == 'No reason provided' else reason}.")
        )


    @HybridCommand(
        name="moderationhistory",
        aliases=("mh", "modhistory",),
        usage="[member MEMBER]", example="@nickderry24"
    )
    @HasPermissions(manage_messages=True)
    async def moderationhistory(self, ctx: Context, *, member: Optional[Member] = Author):
        """View the moderation history of a member or staff member in the server."""
        
        if not (history := await ctx.bot.database.execute(
            "SELECT case_id, type, member_id, reason FROM nexure_moderations WHERE moderator_id = %s ORDER BY case_id DESC;",
            member.id
        )):
            return await ctx.send_error("There is no previously recorded moderation history for that staff member.")
           
        return await ctx.paginate((
            ctx.default_embed.set_title(f"Moderation History for '{member}'"),
            [f"**Case #{case_id}**\n> **Type:** {type}\n> **Member:** {_member} (`{_member.id}`)\n> **Reason:** {reason}" for case_id, type, member_id, reason in history if (_member := await ctx.bot.fetch_user(member_id))]
        ), show_index=False)
        
        
    @HybridGroup(
        name="history",
        usage="[member MEMBER]", example="@mewa",
        invoke_without_command=True
    )
    @HasPermissions(manage_messages=True)
    async def history(self, ctx: Context, *, user: Optional[User] = Author):
        """View the punishment history of a user."""
        
        if not (history := await ctx.bot.database.execute(
            "SELECT case_id, type, moderator_id, reason FROM nexure_moderations WHERE member_id = %s ORDER BY case_id DESC;",
            user.id
        )):
            return await ctx.send_error("There is no previously recorded punishment history for that user.")
            
        return await ctx.paginate((
            ctx.default_embed.set_title(f"Punishment History for '{user}'"),
            [f"**Case #{case_id}**\n> **Type:** {type}\n> **Moderator:** {moderator} (`{moderator.id}`)\n> **Reason:** {reason}" for case_id, type, moderator_id, reason in history if (moderator := await ctx.bot.fetch_user(moderator_id))]
        ), show_index=False)
        
        
    @history.group(
        name="remove",
        usage="<member MEMBER> [case_id NUMBER]", example="@nickderry24 1528",
        invoke_without_command=True
    )
    @HasPermissions(manage_messages=True)
    async def history_remove(self, ctx: Context, member: Member, case_id: int):
        """Remove a specific punishment from a member's history"""
        
        if not await ctx.bot.database.fetchrow("SELECT * FROM moderation_history WHERE member_id = %s AND case_id = %s;", member.id, case_id):
            return await ctx.send_error("Please provide a valid case ID belonging to that member.")
            
        GatherTasks(
            ctx.bot.database.execute("DELETE FROM moderation_history WHERE case_id = %s;", case_id),
            ctx.success(f"Successfully removed that punishment.")
        )
        
        
    @history_remove.command(
        name="all",
        usage="<member MEMBER>", example="@nickderry24",
    )
    @HasPermissions(manage_messages=True)
    async def history_remove_all(self, ctx: Context, *, member: Member):
        """Remove all punishments from a member's history"""
        
        if not await ctx.bot.database.fetchrow("SELECT * FROM moderation_history WHERE member_id = %s LIMIT 1;", member.id):
            return await ctx.send_error("There is no previously recorded punishment history for that member.")
            
        GatherTasks(
            ctx.bot.database.execute("DELETE FROM moderation_history WHERE member_id = %s;", member.id),
            ctx.success(f"Successfully **removed** every punishment.")
        )