from __future__ import annotations
from bot.core.main.keychain import Keychain
from bot.core.main.literals import Configuration, Colors, Emojis
from bot.core.processing import Events
from bot.utils.drivers.database import MySQL, Redis
from bot.utils.drivers.network import ClientSession
from bot.utils.worker import dask as Dask
from bot.utils.patch import Context

from discord import Interaction, Member, TextChannel, VoiceChannel
from discord.abc import GuildChannel
from discord.ext.commands import (
    Bot as NonShardedBot
)

from asyncio import CancelledError, Event, sleep, wait_for
from collections import deque
from jishaku import Flags
from loguru import logger
from munch import Munch
from pathlib import Path
from sys import stdout as STDOUT
from traceback import print_exc as PrintTraceback
from typing import Any, Literal, Tuple, Union
from watchfiles import Change, awatch

logger.remove()
logger.add(
    sink=STDOUT,
    format="<blue>[{time:YYYY-MM-DD HH:mm:ss}]</blue> - {file} - <level>{level}</level> - {message}",
    level="DEBUG",
    colorize=True
)


class NexureClient(NonShardedBot):
    __slots__ = (
        "guilds_chunked",
        "keychain", "fernet",
        "config", "logger",
        "_audit_log_cache",
        "database", "redis", "session", "dask"
    )
    
    def __init__(self):
        super().__init__(
            command_prefix=Configuration.command_prefix,
            max_messages=Configuration.max_messages,
            activity=Configuration.activity,
            owner_ids=Configuration.owner_ids,
            intents=Configuration.intents,
            allowed_mentions=Configuration.allowed_mentions,
            help_command=None,
            auto_update=False,
            anti_cloudflare_ban=True,
            case_insensitive=True,
            strip_after_prefix=True,
            chunk_guilds_at_startup=False
        )
        
        self.guilds_chunked = Event()
        
        self.keychain = Keychain()
        self.fernet = self.keychain._Keychain__fernet
        self.config = Munch(
            colors = Colors,
            emojis = Emojis
        )
        
        self.logger = logger
        self._audit_log_cache = deque()
        
        Events(self)
        
        
    @property
    def audit_cache(self) -> deque:
        return self._audit_log_cache
        
        
    @property
    def members(self) -> Tuple[Member]:
        return tuple(self.get_all_members())
    

    @property
    def channels(self) -> Tuple[GuildChannel]:
        return tuple(self.get_all_channels())
    

    @property
    def text_channels(self) -> Tuple[TextChannel]:
        return tuple(filter(
            lambda channel: isinstance(channel, TextChannel),
            self.get_all_channels()
        ))


    @property
    def voice_channels(self) -> Tuple[VoiceChannel]:
        return tuple(filter(
            lambda channel: isinstance(channel, VoiceChannel),
            self.get_all_channels()
        ))

                    
    async def get_context(
        self, origin: Union[ Message, Interaction ],
        /, cls: Context = Context,
    ) -> Any:
        return await super().get_context(origin, cls=cls)
        
    
    async def close(self):      
        await self.database.close()
        await Dask.stop_dask()
        await super().close()
        
        
    async def setup_hook(self):
        self.database = MySQL(bot)
        self.redis = Redis()
        self.session = ClientSession()
        
        self.dask = await Dask.start_dask()
        self.owner_ids = tuple(map(
            int,
            await self.database.fetch(f"SELECT oAuthID FROM nexure_users WHERE botAuth = 1;")
        ))
        
        try:
            await self.database.initialize()
            
        except Exception:
            self.logger.warning("Shutting down; MySQL pool failed to initialize.")
            await self.close()
            raise

        try:
            await self.redis.initialize()

        except Exception:
            logger.warning("Shutting down; Redis connection failed to initialize.")
            await self.close()
            raise

        
    async def manage_extension(
        self, action: Literal[ "load", "reload", "remove" ],
        *, extension: str
    ):
        try:
            await wait_for(
                getattr(self, f"{action}_extension")(extension),
                timeout=5
            )
                    
        except TimeoutError:
            self.logger.warning(f"Timed out while {action}ing '{extension}'.")
                    
        except CancelledError:
            self.logger.warning(f"Cancelled {action}ing '{extension}'.")
                    
        except Exception:
            PrintTraceback()
            self.logger.error(f"Failed to {action} '{extension}'.")
        
    
    async def load_extensions(self):
        Flags.RETAIN = True
        Flags.NO_DM_TRACEBACK = True
        Flags.FORCE_PAGINATOR = True
        Flags.NO_UNDERSCORE = True
        Flags.HIDE = True
        
        await self.load_extension("jishaku")
        
        for module in Path("bot/modules").iterdir():
            if module.is_dir() and (module / "__init__.py").is_file():
                await sleep(1e-3)
                await self.manage_extension("load", extension=".".join(module.parts))
                
        while True:
            await sleep(1e-3)
            async for change in awatch("bot/modules"):
                change_type, change_path = next(iter(change))
                extension = ".".join(Path(change_path).parts[-4:-1])
                
                match change_type:
                    case Change.added:
                        if extension not in self.extensions:
                            await self.manage_extension("load", extension=extension)
                            
                    case Change.deleted:
                        if extension in self.extensions:
                            await self.manage_extension("remove", extension=extension)
                
                    case Change.modified:
                        await self.manage_extension(
                            "reload" if extension in self.extensions else "load",
                            extension=extension
                        )