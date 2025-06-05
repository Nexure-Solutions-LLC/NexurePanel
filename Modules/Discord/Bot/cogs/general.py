import discord
from discord.ext import commands
from utils.utils import NexureContext

class General(commands.Cog):
    def __init__(self, bot):
        self.bot = bot

    @commands.hybrid_command()
    async def about(self, ctx: NexureContext):
        ...

async def setup(bot):
    await bot.add_cog(General(bot))

# Love, bread.