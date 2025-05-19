import discord
from discord.ext import commands
from utils.constants import guild_counters, reminder_counters, prefixes
import os
from dotenv import load_dotenv


async def get_next_case_id(guild_id):

    guild_case_number = await guild_counters.find_one_and_update(
        {"_id": str(guild_id)}, {"$inc": {"seq": 1}}, upsert=True, return_document=True
    )

    return guild_case_number["seq"]


async def get_next_reminder_id(guild_id):

    guild_reminder_number = await reminder_counters.find_one_and_update(
        {"_id": str(guild_id)}, {"$inc": {"seq": 1}}, upsert=True, return_document=True
    )

    return guild_reminder_number["seq"]


async def get_prefix(nexure, message):
    guild_data = await prefixes.find_one({"guild_id": str(message.guild.id)})

    if guild_data:
        prefix = guild_data.get("prefix")
    else:
        load_dotenv()
        prefix = str(os.getenv("PREFIX"))

    return commands.when_mentioned_or(prefix)(nexure, message)


class NexureContext(commands.Context):
    @property
    def nexure(self):
        return self.bot

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