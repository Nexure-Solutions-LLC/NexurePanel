from jishaku.cog import STANDARD_FEATURES, OPTIONAL_FEATURES


class CustomDebugCog(*OPTIONAL_FEATURES, *STANDARD_FEATURES):
    pass


async def setup(bot):
    await bot.add_cog(CustomDebugCog(bot=bot))