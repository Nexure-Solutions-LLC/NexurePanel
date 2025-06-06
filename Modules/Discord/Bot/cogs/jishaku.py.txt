from discord.ext import commands

from jishaku.cog import STANDARD_FEATURES


class CustomDebugCog(*STANDARD_FEATURES):
    pass


async def setup(bot: commands.Bot):
    await bot.add_cog(CustomDebugCog(bot=bot))

# Love, bread.