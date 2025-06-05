import discord
import datetime
from discord.ext import commands
from datetime import datetime
from utils.constants import NexureConstants
from utils.utils import NexureContext
from utils.modules.embeds import (
    AboutEmbed,
    AboutWithButtons,
    PingCommandEmbed
)


constants = NexureConstants()


class General(commands.Cog):
    def __init__(self, bot):
        self.bot = bot
        

    @commands.hybrid_command(
        description="Provides important information about Nexure.",
        with_app_command=True,
        extras={"category": "Other"},
    )
    async def about(self, ctx: NexureContext):
        await ctx.defer(ephemeral=True)

        guilds = len(self.bot.guilds)
        users = sum(guild.member_count for guild in self.bot.guilds)
        environment = constants.environment()
        command_run_time = datetime.now()
        formatted_time = command_run_time.strftime("Today at %I:%M %p UTC")

        embed = AboutEmbed.create_info_embed(
            uptime=self.bot.start_time,
            guilds=guilds,
            users=users,
            latency=self.bot.latency,
            bot_name=ctx.guild.name,
            bot_icon=ctx.guild.icon,
            environment=environment,
            command_run_time=formatted_time,
            thumbnail_url="https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png",
        )
        
        view = AboutWithButtons.create_view()
        await ctx.send(embed=embed, view=view)
        

    @commands.hybrid_command(
        name="ping",
        description="Check the bot's latency, uptime, and shard info.",
        with_app_command=True,
        extras={"category": "Other"},
    )
    async def ping(self, ctx: NexureContext):
        latency = self.bot.latency
        uptime = self.bot.start_time

        embed = PingCommandEmbed.create_ping_embed(
            latency, uptime
        )

        await ctx.send(embed=embed)


    @commands.hybrid_command(
        description="Use this command to say things to people using the bot.",
        with_app_command=True,
        extras={"category": "General"},
    )
    @commands.has_permissions(administrator=True)
    async def say(self, ctx, *, message: str):

        if ctx.interaction:
            await ctx.send(
                "sent", allowed_mentions=discord.AllowedMentions.none(), ephemeral=True
            )
            await ctx.channel.send(
                message, allowed_mentions=discord.AllowedMentions.none()
            )
        else:
            await ctx.channel.send(
                message, allowed_mentions=discord.AllowedMentions.none()
            )
            await ctx.message.delete()


async def setup(bot):
    await bot.add_cog(General(bot))

# Love, bread.