import discord
import sentry_sdk as sdk
from discord.ext import commands
from zuid import ZUID
from utils.constants import NexureConstants, logger

error_prefix = NexureConstants().error_prefix()
generator = ZUID(prefix=error_prefix, length=32)

class OnError(commands.Cog):
    def __init__(self, bot):
        super().__init__()
        self.bot = bot
        self.emojis = NexureConstants().emojis()

    @commands.Cog.listener()
    async def on_command_error(self, ctx: commands.Context, error):
        show_error = False
        error_id = None

        if isinstance(error, commands.MissingRequiredArgument):
            description = f'{self.emojis["failed"]} Sorry! You are missing a required argument - `{error.param.name}`.'

        elif isinstance(error, commands.BadArgument):
            description = f'{self.emojis["failed"]} Sorry! I could not process one of your inputs. Please try again.'

        elif isinstance(error, discord.NotFound):
            description = f'{self.emojis["failed"]} Sorry! I could not find the requested asset. Please try again, this could be due to a permission error.'

        elif isinstance(error, discord.Forbidden):
            description = f'{self.emojis["failed"]} Sorry! I do not have sufficient permissions to perform this action. Please try again later.'

        elif isinstance(error, commands.MissingPermissions):
            description = f'{self.emojis["failed"]} Sorry! You lack sufficient permissions to perform this action. Please try again later.'

        elif isinstance(error, commands.CommandNotFound):
            return
        
        elif isinstance(error, commands.CheckFailure):
            return

        else:
            description = f'{self.emojis['failed']} Sorry! Something went wrong, this is unusual. Please try again, and if the issue persists contact our [support team](https://nexuresolutions.com/).'
            show_error = True
            error_id = generator()

            if NexureConstants().environment() == "PRODUCTION":
                with sdk.push_scope() as scope:
                    scope.set_tag("error_id", error_id)
                    scope.level = "error"
                    sdk.capture_exception(error, scope=scope)

            staff_embed = discord.Embed(title='Error!', description='An unexpected error has been caught, see below for more information.', colour=discord.Colour.red())
            staff_embed.add_field(name="Invoking User", value=f"{ctx.author} (`{ctx.author.id}`)", inline=True)
            staff_embed.add_field(name="Invoked Guild", value=f"{ctx.guild} (`{ctx.guild.id}`)", inline=True)
            staff_embed.add_field(name="Invoked Command", value=f"{ctx.command}", inline=True)
            staff_embed.add_field(name="Arguments", value=f"{ctx.args}", inline=True)
            staff_embed.add_field(name="Error", value=f"{error}", inline=False)
            staff_embed.add_field(name="Error ID", value=f"{error_id}", inline=False)

            guild = self.bot.get_guild(1175890904230408223)
            channel = discord.utils.get(guild.channels, id=1378813679570784327)
            
            await channel.send(embed=staff_embed)

        try:
            embed = discord.Embed(title='Error!', description=description, colour=discord.Color.red())
            embed.set_footer(text=f'User ID: {ctx.author.id} | Guild ID: {ctx.guild.id}')
            if error_id: 
                embed.add_field(name='Error ID', value=f'`{error_id}`')
                
            await ctx.send(embed=embed, ephemeral=not show_error)

        except Exception as e:
            logger.error(e)
        
    
async def setup(bot):
    await bot.add_cog(OnError(bot))

# Love, bread.