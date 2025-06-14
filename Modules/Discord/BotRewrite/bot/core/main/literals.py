# Author: Treyten
from __future__ import annotations

from discord import (
    Activity,
    ActivityType,
    AllowedMentions,
    Intents,
    Message
)

from munch import Munch
from typing import List


Configuration = Config = configuration = config = Munch(
    command_prefix = "\u0021",
    max_messages = 500,
    owner_ids = (), # Dynamically defined in Bot.setup_hook
    allowed_mentions = AllowedMentions(
        everyone=False,
        users=True,
        roles=False,
        replied_user=False
    ),
    activity = Activity(
        type=ActivityType.watching,
        name="nexuresolutions.com"
    ),
    intents = Intents(
        guilds=True,
        members=True,
        moderation=True,
        emojis=True,
        integrations=False,
        webhooks=False,
        invites=False,
        voice_states=True,
        presences=False,
        guild_messages=True,
        dm_messages=False,
        guild_reactions=True,
        dm_reactions=False,
        guild_scheduled_events=False,
        auto_moderation=False,
        typing=False,
        message_content=True
    )
)


Colors = colors = Munch(
    main = 0x8DC6F4,
)


Emojis = emojis = Munch(
    success = "<:NexureSuccess:1370202310113886339>",
    failure = "<:NexureFail:1377202015507054663>"
)