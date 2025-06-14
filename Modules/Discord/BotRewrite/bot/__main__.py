# Author: Treyten
from bot.core.main import NexureClient
from os.path import abspath, dirname

import asyncio
import sys

sys.path.append(dirname(abspath(__file__)))


async def run():
    async with NexureClient() as bot:
        try:
            await bot.start(token=bot.keychain.Discord, reconnect=False)

        finally:
            if not bot.is_closed():
                await bot.close()
                
            bot.session.close()
            bot.logger.info("Application shut down.")
            
            
assert __name__ == "__main__"
asyncio.run(run())
