import discord
from discord.ext import commands

from utils.utils import NexureContext
from utils.constants import NexureConstants

class DeveloperCommands(commands.Cog):
    def __init__(self, bot):
        self.bot = bot
        self.nexure_constants = NexureConstants()

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

    @commands.command()
    @commands.is_owner()
    async def add_owner(self, ctx: NexureContext, user: discord.User):
        await ctx.defer()
        try:
            embed = discord.Embed(title='Success!', description=f'{self.nexure_constants.emojis()['success']} Added user to bypass.', color=self.nexure_constants.colour())
            await ctx.bot.database.execute(f'UPDATE {self.nexure_constants.sql_users()} SET botAuth = %s WHERE oAuthID = %s;', 1, user.id)
            await ctx.send(embed=embed)
        except Exception as e:
            raise Exception('Failed to update database')
    
    @commands.command()
    @commands.is_owner()
    async def remove_owner(self, ctx: NexureContext, user: discord.User):
        await ctx.defer()
        try:
            embed = discord.Embed(title='Success!', description=f'{self.nexure_constants.emojis()['success']} Removed user from bypass.', color=self.nexure_constants.colour())
            await ctx.bot.database.execute(f'UPDATE {self.nexure_constants.sql_users()} SET botAuth = %s WHERE oAuthID = %s;', 0, user.id)
            await ctx.send(embed=embed)
        except Exception as e:
            raise Exception('Failed to update database')

    @commands.command()
    @commands.is_owner()
    async def show_owners(self, ctx: NexureContext, user: discord.User):
        res = await ctx.bot.database.fetch(f'SELECT oAuthID FROM {self.nexure_constants.sql_users()} WHERE botAuth = %s', 1)
        embed = discord.Embed(title='Authorised Users', description=' ', colour=self.nexure_constants.colour())

        for user in res[0]:
            embed.description += f'<@{user}>\n'

        await ctx.send(embed=embed)

async def setup(bot):
    await bot.add_cog(DeveloperCommands(bot))

# Love, bread.