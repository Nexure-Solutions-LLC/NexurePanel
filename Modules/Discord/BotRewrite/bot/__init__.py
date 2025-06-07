# Author: Treyten
from warnings import filterwarnings

filterwarnings("ignore", category=DeprecationWarning)
filterwarnings("ignore", category=RuntimeWarning)
filterwarnings("ignore", category=UserWarning)
filterwarnings("ignore", category=DeprecationWarning, module="importlib", lineno=219)
filterwarnings("ignore", category=DeprecationWarning, module="asyncio", message="The loop argument is deprecated since Python 3.8")


import aiohttp.resolver
import aiohttp.connector

aiohttp.resolver.aiodns_default = True
aiohttp.resolver._DefaultType \
= aiohttp.resolver.DefaultResolver \
= aiohttp.connector.DefaultResolver \
= aiohttp.resolver.AsyncResolver


from discord.utils import setup_logging
from logging import getLogger

logger = getLogger(__name__)
setup_logging()


from dotenv import load_dotenv
load_dotenv("bot/.env", verbose=True)


import uvloop
uvloop.install()

# Globals are all attributes patched into their corresponding libraries.