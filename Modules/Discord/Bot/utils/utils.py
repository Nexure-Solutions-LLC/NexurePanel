import discord
from discord.ext import commands
from durations_nlp import Duration
from typing import Optional
import asyncmy

from utils.constants import NexureConstants
constants = NexureConstants()


# async def initialise_connection():
#     SQL_CON = constants.sql_con()

#     pool = await asyncmy.create_pool(
#         host=SQL_CON['HOST'],
#         port=SQL_CON['PORT'],
#         user=SQL_CON['USER'],
#         password=SQL_CON['PASSWORD'],
#         db=SQL_CON['DATABASE'],
#         minsize=1,
#         maxsize=10,
#         autocommit=True
#     )
#     return pool

# async def reload_auth(db) -> list:
#     users = []
#     async with db.acquire() as conn:
#         async with conn.cursor() as cur:
#             await cur.execute(f'SELECT oAuthID FROM {NexureConstants().sql_users()} WHERE botAuth = %s', True)
#             result = await cur.fetchall()
#         await cur.close()
#         users = [int(uid[0]) for uid in result]

#     return users
# --  Deprecated; use drivers/database/mysql.py


class NexureContext(commands.Context):
    @property
    def nexure(self):
        return self.bot

    @property
    def user(self):
        return self.author

    async def send_success(self, message: str):
        embed = discord.Embed(
            title="Success!",
            description=f"{constants.emojis()['success']} {message}",
            color=constants.colour(),
        )
        return await super().send(embed=embed, reference=self.message)

    async def send_error(self, message: str):
        embed = discord.Embed(
            title="Error!",
            description=f"{constants.emojis()['failed']} {message}",
            color=constants.colour(),
        )
        return await super().send(embed=embed, reference=self.message)

    async def send_loading(self, message: str):
        embed = discord.Embed(
            title="", description=f"{self.nexure.loading} {message}", color=0x2A2C31
        )
        return await super().send(embed=embed, reference=self.message)

    async def send_warning(self, message: str):
        embed = discord.Embed(
            title="Warning!",
            description=f"{constants.emojis()['failed']} {message}",
            color=constants.colour(),
        )
        return await super().send(embed=embed, reference=self.message)

    async def send_normal(self, message: str):
        embed = discord.Embed(
            title="", description=f"{message}", color=constants.colour()
        )
        return await super().send(embed=embed)


class Timespan(commands.Converter):
    async def convert(self, ctx, argument: str) -> Optional[Duration]:
        if (ret := Duration(argument)).seconds and argument not in ("0", "0 seconds", "0s", "0h", "0w", "0m", "0 hours", "0 weeks"," 0 months", "0 hours"):
            return ret
            
        await ctx.send_error("Send a valid timestamp in this format: (number)(d/h/m/s).\nExample: 5d for 5 days")
        raise ValueError
        

# Love, bread.

# More love,   Nick's secret admirer