import discord
from discord.ext import commands
from utils.constants import NexureConstants

class on_member_join(commands.Cog):
    def __init__(self, bot):
        self.bot = bot
    
    @commands.Cog.listener()
    async def on_member_join(self, member):
        nexure_constants = NexureConstants()

        channel = await self.bot.fetch_channel(nexure_constants.welcome_channel())
        role = await self.bot.fetcH_role(nexure_constants.welcome_role())
        await member.add_roles(role)
        await channel.send(content=f'{member.mention} Welcome to Nexure Solutions! You are the **{member.guild.member_count}** member to join us.')

async def setup(bot):
    await bot.add_cog(on_member_join(bot))