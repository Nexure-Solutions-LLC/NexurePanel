import os
import discord
from motor.motor_asyncio import AsyncIOMotorClient
from dotenv import load_dotenv


load_dotenv()


mongodb_url = os.getenv("MONGODB_URL")
mongodb_db = os.getenv("MONGODB_DB")
client = AsyncIOMotorClient(mongodb_url)
db = client[mongodb_db]


afks = db.afks
blacklist_bypass = db.blacklist_bypass
blacklistedwords = db.blacklistedwords
blacklists = db.blacklists
notes = db.notes
prefixes = db.prefixes
reminders = db.reminders
setup_col = db.setup
verify_waiting = db.verify_waiting
cases = db.cases
guild_counters = db.guild_counters
reminder_counters = db.reminder_counters
timezones = db.timezones
socials = db.socials
lastfm = db.lastfm
welcomer = db.welcomer
giveaways = db.giveaways


class NexureConstants:
    def __init__(self):
        self.mongo_client = None
        self.mongo_db = None
        self.bypassed_users = []
        self.blacklists = []
        self.server_blacklists = []


    # Checks the users to see if they are blacklist bypasssed and bot owner. This function
    # will get the User IDs from MongoDB and can be called to see if users are allowed to
    # run commands.


    async def fetch_bypassed_users(self):
        try:
            results = blacklist_bypass.find({}, {"discord_id": 1})

            bypassed_users = []
            async for result in results:
                bypassed_users.append(result.get("discord_id"))

            self.bypassed_users = bypassed_users

        except Exception as e:
            print(f"Error fetching bypassed users: {str(e)}")


    # Checks the owner of the bot
    # Owners will also be given Jsk Permissions which is dangerous so please be careful who you add into
    # the bot as an owner.


    async def is_owner(self, user_id: int):
        if not self.bypassed_users:
            await self.fetch_bypassed_users()
        return user_id in self.bypassed_users


    # Fetch the customizable prefix for the bot
    # Fetch the bot token


    def nexure_token_setup(self):
        token = os.getenv("TOKEN")
        if not isinstance(token, str):
            raise TypeError(
                f"expected token to be a str, received {type(token).__name__} instead"
            )
        return token


    def nexure_client_id_setup(self):
        token = os.getenv("CLIENT_ID")
        if not isinstance(token, str):
            raise TypeError(
                f"expected token to be a str, received {type(token).__name__} instead"
            )
        return token


    def nexure_client_secret_setup(self):
        token = os.getenv("CLIENT_SECRET")
        if not isinstance(token, str):
            raise TypeError(
                f"expected token to be a str, received {type(token).__name__} instead"
            )
        return token


    def nexure_redirect_uri_setup(self):
        token = os.getenv("REDIRECT_URL")
        if not isinstance(token, str):
            raise TypeError(
                f"expected token to be a str, received {type(token).__name__} instead"
            )
        return token


    # Fetch the Sentry DSN for error reporting


    def sentry_dsn_setup(self):
        return os.getenv("SENTRY_DSN")


    # Fetch the default embed color for the bot

    def nexure_embed_color_setup(self):
        DEFAULT_EMBED_COLOR = discord.Color.from_str("#8dc6f4")
        return DEFAULT_EMBED_COLOR


    # Gets the bots type either production or development

    def nexure_environment_type(self):
        return os.getenv("ENVIRONMENT")


    async def fetch_blacklisted_users(self):
        try:
            cursor = db.blacklists.find({}, {"discord_id": 1})

            blacklists = []

            async for document in cursor:
                blacklists.append(document["discord_id"])
            self.blacklists = blacklists

        except Exception as e:
            print(f"Error fetching blacklisted users: {str(e)}")


    async def fetch_blacklisted_guilds(self):
        try:
            cursor = blacklists.find({}, {"discord_id": 1})
            server_blacklists = []

            async for document in cursor:
                server_blacklists.append(document["discord_id"])
            self.server_blacklists = server_blacklists

        except Exception as e:
            print(f"Error fetching blacklisted guilds: {str(e)}")


    # Call this periodically to refresh blacklist data

    async def refresh_blacklists(self):
        await self.fetch_blacklisted_users()
        await self.fetch_blacklisted_guilds()