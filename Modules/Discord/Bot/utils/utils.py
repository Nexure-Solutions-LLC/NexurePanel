import discord
from discord.ext import commands
import asyncmy

from utils.constants import NexureConstants

async def initialise_connection():
    SQL_CON = NexureConstants().sql_con()

    pool = await asyncmy.create_pool(
        host=SQL_CON['HOST'],
        port=SQL_CON['PORT'],
        user=SQL_CON['USER'],
        password=SQL_CON['PASSWORD'],
        db=SQL_CON['DATABASE'],
        minsize=1,
        maxsize=10,
        autocommit=True
    )
    return pool

async def reload_auth(db) -> list:
    users = []
    async with db.acquire() as conn:
        async with conn.cursor() as cur:
            await cur.execute(f'SELECT oAuthID FROM {NexureConstants().sql_users()} WHERE botAuth = %s', True)
            result = await cur.fetchall()
        await cur.close()
        users = [int(uid[0]) for uid in result]

    return users


class NexureContext(commands.Context):
    @property
    def nexure(self):
        return self.bot

    @property
    def user(self):
        return self.author

    async def send_success(self, message: str):
        embed = discord.Embed(
            title="",
            description=f"{self.nexure.success} {message}",
            color=self.nexure.base_color,
        )
        return await super().send(embed=embed, reference=self.message)

    async def send_error(self, message: str):
        embed = discord.Embed(
            title="",
            description=f"{self.nexure.error} {message}",
            color=self.nexure.base_color,
        )
        return await super().send(embed=embed, reference=self.message)

    async def send_loading(self, message: str):
        embed = discord.Embed(
            title="", description=f"{self.nexure.loading} {message}", color=0x2A2C31
        )
        return await super().send(embed=embed, reference=self.message)

    async def send_warning(self, message: str):
        embed = discord.Embed(
            title="",
            description=f"{self.nexure.warning} {message}",
            color=self.nexure.base_color,
        )
        return await super().send(embed=embed, reference=self.message)

    async def send_normal(self, message: str):
        embed = discord.Embed(
            title="", description=f"{message}", color=self.nexure.base_color
        )
        return await super().send(embed=embed)

# Love, bread.