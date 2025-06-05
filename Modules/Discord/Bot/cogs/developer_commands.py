import discord
from discord.ext import commands

from utils.utils import NexureContext

class DeveloperCommands(commands.Cog):
    def __init__(self, bot):
        self.bot = bot

    @commands.command()
    @commands.is_owner()
    async def sync(self, ctx: NexureContext, *, scope: str = "guild"):   
        if scope.lower() == "guild":
            await ctx.bot.tree.sync(guild=ctx.guild)
        elif scope.lower() == "global":
            await ctx.bot.tree.sync()
        else:
            raise commands.BadArgument()
        await ctx.send("Synced!")

async def setup(bot):
    await bot.add_cog(DeveloperCommands(bot))

# Love, bread.