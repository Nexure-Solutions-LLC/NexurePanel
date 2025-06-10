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
from typing import Literal, Optional


class Developer(Cog):
    def __init__(self, bot: Bot):
        self.bot = bot


    async def cog_check(self, ctx: Context) -> bool:
        return await ctx.bot.is_owner(ctx.author)


    @Command(
        name="sync",
        aliases=("appsync",),
        usage=None, example=None
    )
    @MaxConcurrency(1, BucketType.default, wait=False)
    @Cooldown(1, 30, BucketType.default)
    async def sync_tree(self, ctx: Context, scope: Optional[Literal[ "guild", "global" ]] = "global"):
        """Synchronize the command tree to either the current guild or globally."""
        await ctx.bot.loop.create_task(ctx.bot.tree.sync(guild={"guild": ctx.guild, "global": None}[scope]))
        return await ctx.send_success(f"Successfully synced commands to the `{scope.title()}` scope.")


    @Group(
        name="owner",
        aliases=("owners",),
        usage="<subcommand>",
        example="add @nickderry24",
        invoke_without_command=True
    )
    async def bot_owner_management(self, ctx: Context):
        """Manage the list of users with privileges on the bot."""
        return await ctx.send_help(ctx.command.qualified_name)


    @bot_owner_management.command(
        name="list",
        aliases=("show", "display"),
        usage=None, example=None
    )
    async def list_bot_owners(self, ctx: Context):
        """Displays all users who have bot owner privileges."""
        if not (bot_owner_ids := tuple(map(
            int, await ctx.bot.database.fetch("SELECT oAuthID FROM nexure_users WHERE botAuth = 1;")
        ))):
            return await ctx.send_error("There are no privileged users to display.")

        users = tuple(map(ctx.bot.get_user, bot_owner_ids))
        return await ctx.paginate((
            ctx.default_embed, [f"{user.mention} (`{user.id}`)" for user in filter(None, users)]
        ))


    @bot_owner_management.command(
        name="add",
        aliases=("append",),
        usage="<user>",
        example="@nickderry24"
    )
    async def add_bot_owner(self, ctx: Context, *, user: User):
        """Grants bot owner privileges to a registered user."""
        if hasattr(
            botauth := await ctx.bot.database.fetchval("SELECT botAuth FROM nexure_users WHERE oAuthID = %s;", user.id),
            "__len__"
        ):
            return await ctx.send_error("This user is not registered in our database.")

        if botauth:
            return await ctx.send_error("This user is already privileged.")

        await gather(*(
            ctx.bot.database.execute("UPDATE nexure_users SET botAuth = 1 WHERE oAuthID = %s;", user.id),
            ctx.send_success(f"Successfully added {user.mention} as a privileged user.")
        ))


    @bot_owner_management.command(
        name="remove",
        usage="<user>",
        example="@nickderry24"
    )
    async def remove_bot_owner(self, ctx: Context, *, user: User):
        """Revokes bot owner privileges from a user."""
        if hasattr(
            botauth := await ctx.bot.database.fetchval("SELECT botAuth FROM nexure_users WHERE oAuthID = %s;", user.id),
            "__len__"
        ):
            return await ctx.send_error("This user is not registered in our database.")

        if not botauth:
            return await ctx.send_error("This user is not privileged.")

        await gather(*(
            ctx.bot.database.execute("UPDATE nexure_users SET botAuth = 0 WHERE oAuthID = %s;", user.id),
            ctx.send_success(f"Successfully revoked {user.mention}'s privileges.")
        ))


    @Command(
        name="guilds",
        aliases=("servers",)
    )
    async def show_guilds(self, ctx: Context):
        """Lists all servers the bot is currently in, with member counts."""
        return await ctx.paginate((
            ctx.default_embed, [f"{guild.name} (`{guild.id}`) - **{guild.member_count:,} members**" for guild in ctx.bot.guilds]
        ))