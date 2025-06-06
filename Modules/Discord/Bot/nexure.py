# ==========================================================================================================
# This software was created by Nexure Solutions LLP.
# This software was created by Alfie Chadd and Treyten Sanders.
# ==========================================================================================================

import discord
import asyncio
import os
import sys
import sentry_sdk
import traceback
import pathlib
from discord.ext import commands
from datetime import datetime
from cogwatch import watch
from typing import Any, Literal
from jishaku import Flags
from utils.constants import NexureConstants, logger
from utils.utils import NexureContext
from utils.drivers.database.mysql import MySQL


class Nexure(commands.AutoShardedBot):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.start_time = datetime.now()
        self.context = NexureContext
        self.before_invoke(self.command_check)
        self.check(self.command_check)
        self.guilds_chunked = asyncio.Event()


    async def get_context(self, message, *, cls=NexureContext) -> Any:
        return await super().get_context(message, cls=cls)


    async def before_commands(self, ctx: NexureContext):
        await ctx.bot.wait_until_ready()

        if not ctx.guild.chunked:
            await ctx.guild.chunk(cache=True)

        
    async def command_check(self, ctx: NexureContext) -> bool:
        await ctx.bot.wait_until_ready()
        
        if await ctx.bot.is_owner(ctx.author):
            return True

        if ctx.author.id in NexureConstants.Auth_list:
            return True

        if not ctx.guild:
            raise commands.NoPrivateMessage('You may not use this command here.')

        user = ctx.author.id
        guild = ctx.guild.id

        if await ctx.bot.database.fetchrow(
            f"SELECT * FROM {NexureConstants.sql_blacklists()} WHERE type = %s AND associatedID = %s;", 'user',  user
        ):
            await ctx.user.send(embed=(
                discord.Embed(title='Blacklist Notice', description='You are unable to use this command; you are blacklisted from **all** Nexure assets. If you would like to appeal, please [contact us](https://nexuresolutions.com/) and we will be more than happy to assist you.', colour=NexureConstants.colour())
                .set_thumbnail(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')
                .set_footer(text=f'User ID: {user}')
            ))
            raise commands.CheckFailure("You are blacklisted from Nexure assets.")
        
        if await ctx.bot.database.fetchrow(
            f"SELECT * FROM {NexureConstants.sql_blacklists()} WHERE type = %s AND associatedID = %s;", 'guild',  guild
        ):
            await ctx.send(embed=(
                discord.Embed(title='Blacklist Notice', description='Nexure assets are not operational within this guild; it is blacklisted from **all** Nexure assets. If you would like to appeal, please [contact us](https://nexuresolutions.com/) and we will be more then happy to assist you.', colour=NexureConstants.colour())
                .set_thumbnail(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')
                .set_footer(text=f'Guild ID: {guild}')
            ))
            raise commands.CheckFailure("This guild is blacklisted from Nexure assets.")
        

    async def setup_hook(self):
        self.database = MySQL(self)
        self.guild = await self.fetch_guild(1175890904230408223)
        
        try:
            await self.database.initialize()
            logger.info(f"Database connection initialized.") 
            
        except Exception:
            logger.critical("Shutting down; MySQL pool failed to initialize.")
            await self.close()
            raise

        NexureConstants.Auth_list = await self.database.reload_auth()
        await self.load_extensions()


    async def manage_extension(
        self, action: Literal[ "load", "reload", "remove" ],
        *, extension: str
    ):
        try:
            await asyncio.wait_for(
                getattr(self, f"{action}_extension")(extension),
                timeout=5
            )
                    
        except TimeoutError:
            logging.warning(f"Timed out while {action}ing '{extension}'.")
                    
        except CancelledError:
            logging.warning(f"Cancelled {action}ing '{extension}'.")
                    
        except Exception:
            traceback.print_exc()
            logging.error(f"Failed to {action} '{extension}'.")
        
    
    async def load_extensions(self):
        Flags.RETAIN = True
        Flags.NO_DM_TRACEBACK = True
        Flags.FORCE_PAGINATOR = True
        Flags.NO_UNDERSCORE = True
        Flags.HIDE = True
        
        await self.load_extension("jishaku")

        if not (cog_files := list(filter(
            lambda file: file.name != "__init__.py",
            pathlib.Path("cogs").rglob("*.py")
        ))):
            logger.critical('No cog files found. Shutting down.')
            sys.exit('RESOURCE NOT FOUND.')

        for file in cog_files:
            await asyncio.sleep(1e-3)
            await self.manage_extension("load", extension=".".join(file.with_suffix("").parts))


    @watch(path='cogs', preload=False)
    async def on_ready(self):
        # No API requests should be made on ready. Discord is annoying
        logger.info(f"Bot is ready: {self.user}")


    async def on_message(self, message: discord.Message):
        await self.wait_until_ready()

        if message.author.bot or message.guild is None:
            return
            
        async def chunk_guild(guild: discord.Guild):
            await asyncio.sleep(1.5)
            
            if guild.chunked is False:
                await guild.chunk(cache=True)

        for guild in sorted(
            self.guilds,
            key=lambda g: g.member_count,
            reverse=True
        ):
            if guild.chunked is False:
                await asyncio.sleep(1e-3)
                await self.loop.create_task(chunk_guild(guild))
                
        self.guilds_chunked.set()
        self.loop.create_task(self.process_commands(message))


    async def is_owner(self, user: discord.User):
        auth_list = await self.database.reload_auth()
        return user.id in auth_list

NexureConstants = NexureConstants()

nexure = Nexure (
    command_prefix='!',
    chunk_guilds_at_startup=False,
    help_command=None,
    intents = discord.Intents(
        guilds=True,
        members=True,
        moderation=True,
        emojis=True,
        integrations=False,
        webhooks=False,
        invites=False,
        voice_states=True,
        presences=False,
        guild_messages=True,
        dm_messages=False,
        guild_reactions=True,
        dm_reactions=False,
        guild_scheduled_events=False,
        auto_moderation=False,
        typing=False,
        message_content=True
    ),
    activity=discord.Activity(
        name="nexuresolutions.com",
        type=discord.ActivityType.watching,
    ),
    allowed_mentions=discord.AllowedMentions(
        everyone=False,
        users=True,
        roles=False,
        replied_user=False
    ),
    cls=NexureContext
)


async def run():
    if NexureConstants.environment() == 'PRODUCTION':
        sentry_sdk.init(
            dsn = NexureConstants.sentry_dsn(),
            traces_sample_rate=1.0,
            profiles_sample_rate=1.0
        )

    async with nexure:
        await nexure.start(NexureConstants.token())

# Love, bread.