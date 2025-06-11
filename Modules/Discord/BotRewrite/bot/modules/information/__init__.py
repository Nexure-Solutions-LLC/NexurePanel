from __future__ import annotations
from .information import Information

from asyncio import gather


async def setup(bot: Bot):
    gather(*(
        bot.add_cog(cog(bot))
        for cog in (Information,)
    ))