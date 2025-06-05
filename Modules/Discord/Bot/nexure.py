# ==========================================================================================================
# This software was created by Nexure Solutions LLP.
# This software was created by Alfie Chadd.
# ==========================================================================================================

import discord
from discord.ext import commands
from datetime import datetime
import asyncio 
import os
import sys
import sentry_sdk
from cogwatch import watch 

from utils.constants import NexureConstants, logger
from utils.utils import NexureContext, initialise_connection, reload_auth

class Nexure(commands.AutoShardedBot):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.start_time = datetime.now()
        self.context = NexureContext
        self.db = None

    async def get_context(self, message, *, cls=NexureContext):
        return await super().get_context(message, cls=cls)
    

    async def setup_hook(self) -> None:
        cog_count = 0
        found_files = False
        for root, dirs, files in os.walk('./cogs'):
            dirs[:] = [d for d in dirs if d != '__pycache__']
            
            cog_files = [f for f in files if f.endswith('.py')]
            if cog_files:
                found_files = True
                for filename in cog_files:
                    relative_path = os.path.relpath(root, './cogs').replace(os.sep, '.')
                    cog_name = f"cogs{('.' + relative_path) if relative_path != '.' else ''}.{filename[:-3]}"
                    try:
                        await self.load_extension(cog_name) 
                        logger.info(f"Loaded cog: {cog_name}")
                        cog_count += 1
                    except Exception as e:
                        logger.error(f"Failed to load cog: {cog_name}. Error: {e}")

        if not found_files:
            logger.critical('Unable to locate any cog files. Stopping bot.')
            sys.exit('RESOURCE NOT FOUND.')

        logger.info(f"Loaded {cog_count} cogs")

    @watch(path='cogs', preload=False)
    async def on_ready(self):
        await self.tree.sync()

        await self.change_presence(
            activity=discord.Activity(
                name=f"nexuresolutions.com",
                type=discord.ActivityType.watching,
            )
        )

        try:
            self.db = await initialise_connection()
            async with self.db.acquire() as conn:
                async with conn.cursor() as cur:
                    await cur.execute("SELECT 1")
                    result = await cur.fetchone()
                    logger.info(f'Connected to server. Returned: {result}')

        except Exception as e:
            logger.critical(f'Failed to connect to database, error thrown: {e}')
            sys.exit('RESOURCE NOT AVAILABLE')

        except Exception as e:
            logger.critical(f'Failed to connect to database, error thrown: {e}')
            sys.exit('RESOURCE NOT AVAILABLE')

        logger.info(f"Bot is ready: {self.user}")


    async def is_owner(self, user: discord.User):
        auth_list = await reload_auth(self.db)
        return user.id in auth_list

NexureConstants = NexureConstants()

intents = discord.Intents.default()
intents.message_content = True
intents.members = True

nexure = Nexure(
    command_prefix='!',
    intents=intents,
    chunk_guilds_at_startup=False,
    help_command=None,
    allowed_mentioms=discord.AllowedMentions(
        replied_user=True, everyone=True, roles=True
    ),
    cls=NexureContext
)

@nexure.before_invoke
async def before_invoke(ctx: NexureContext):
    auth_list = await reload_auth(ctx.bot.db)
    NexureConstants.Auth_list = auth_list
    if ctx.author.id in NexureConstants.Auth_list:
        return

    if ctx.guild == None:
        raise commands.NoPrivateMessage('You may not use this command here.')

    await blacklist_check(ctx)

async def blacklist_check(ctx):
    user = ctx.author.id
    async with ctx.bot.db.acquire() as conn:
        async with conn.cursor() as cur:
            await cur.execute(f'SELECT * FROM {NexureConstants.sql_blacklists()} WHERE type = %s AND associatedID = %s', ('user',  user))
            result = await cur.fetchone()
        await cur.close()
            
    if result != None:
        embed = discord.Embed(title='Blacklist notice', description='You are unable to use this command, you are blacklisted form **all** Nexure assets. If you would like to appeal, please [contact us](https://nexuresolutions.com/) and we will be more then happy to assist you.', colour=NexureConstants.colour())
        embed.set_thumbnail(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')
        embed.set_footer(text=f'User ID: {user}')
        await ctx.user.send(embed=embed)
        raise commands.CheckFailure("You are blacklisted from Nexure assets.")
    
    guild = ctx.guild.id
    async with ctx.bot.db.acquire() as conn:
        async with conn.cursor() as cur:
            await cur.execute(f'SELECT * FROM {NexureConstants.sql_blacklists()} WHERE type = %s AND associatedID = %s', ('guild',  guild))
            result = await cur.fetchone()
        await cur.close()

    if result != None:
        embed = discord.Embed(title='Blacklist notice', description='Nexure assets are not operational within this guild, it is blacklisted form **all** Nexure assets. If you would like to appeal, please [contact us](https://nexuresolutions.com/) and we will be more then happy to assist you.', colour=NexureConstants.colour())
        embed.set_thumbnail(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')
        embed.set_footer(text=f'Guild ID: {guild}')
        await ctx.send(embed=embed)
        raise commands.CheckFailure("This guild is blacklisted from Nexure assets.")

    return

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