from nexure import run
from utils.constants import logger
import asyncio
import sys

if __name__ == "__main__":
    try:
        asyncio.run(run())
    except KeyboardInterrupt:
        logger.warning('Shutting down...')
        sys.exit()
    except RuntimeError as e:
        logger.critical(e)

# Love, bread.
