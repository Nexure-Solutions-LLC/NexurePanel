import discord
from discord.ext import commands
from discord.ui import View

from utils.utils import NexureContext
from utils.constants import NexureConstants

class Paginator(View):
    def __init__(self, pages: list[discord.Embed], user, timeout: int = 300):
        super().__init__(timeout=timeout)
        self.user = user
        self.pages = pages
        self.current_page = 0

        self.previous_page = Button(emoji='<:left:1377235457393426513>', style=discord.ButtonStyle.grey)
        self.previous_page.callback = self.previous_page_callback
        self.add_item(self.previous_page)

        self.next_page = Button(emoji='<:right:1377235525911445586>', style=discord.ButtonStyle.grey)
        self.next_page.callback = self.next_page_callback
        self.add_item(self.next_page)

    async def previous_page_callback(self, interaction: discord.Interaction):
        if not await self.interaction_check(interaction):
            return
        
        self.current_page = (self.current_page - 1) % len(self.pages)
        await interaction.response.edit_message(embed=self.pages[self.current_page], view=self)

    async def next_page_callback(self, interaction: discord.Interaction):
        if not await self.interaction_check(interaction):
            return
        self.current_page = (self.current_page + 1) % len(self.pages)
        await interaction.response.edit_message(embed=self.pages[self.current_page], view=self)

    async def interaction_check(self, interaction: discord.Interaction) -> bool:
        if interaction.user.id != self.user.id:
            await interaction.response.send_message(embed=discord.Embed(title='Error!', description='Sorry! You may not interact with this.', colour=discord.Colour.red()), ephemeral=True)
            return False
        
        return True

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
    async def show_owners(self, ctx: NexureContext):
        res = await ctx.bot.database.fetch(f'SELECT oAuthID FROM {self.nexure_constants.sql_users()} WHERE botAuth = %s', 1)
        embed = discord.Embed(title='Authorised Users', description=' ', colour=self.nexure_constants.colour())
        
        for user in res:
            embed.description += f'<@{user}>\n'

        await ctx.send(embed=embed)

    @commands.command()
    @commands.is_owner()
    async def show_guilds(self, ctx: NexureContext):
        guilds = sorted(ctx.bot.guilds, key=lambda g: -g.member_count)

        pages = []
        for guild in guilds:
            embed = discord.Embed(title=f'{guild.name} ({guild.id})', description=f'Member Count: {guild.member_count}')
            try:
                embed.set_thumbnail_url(url=guild.icon.url)
            except discord.NotFound():
                embed.set_thumbnail_url(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')

        view = Paginator()


async def setup(bot):
    await bot.add_cog(DeveloperCommands(bot))

# Love, bread.