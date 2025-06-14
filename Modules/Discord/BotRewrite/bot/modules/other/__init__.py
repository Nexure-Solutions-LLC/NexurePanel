# Author: Treyten
from __future__ import annotations
from .developer import Developer

from asyncio import gather as GatherTasks
from typing import TYPE_CHECKING

if TYPE_CHECKING:
    from bot import NexureClient


async def setup(bot: NexureClient):
    GatherTasks(*(
        bot.add_cog(cog(bot))
        for cog in (Developer,)
    ))