import discord
from discord.ui import Modal, Select, TextInput
from discord import Interaction
from utils.constants import NexureConstants, setup_col

constants = NexureConstants()

# This file contains all the modals for Nexure. I have taken this idea from production but made it more readable and
# easier to understand, I was originally going to embed each modal into the actual command, but why do that when
# we can do this, it also makes it easier on developers as they only have to edit once.

# ========================================= Start Utility Modals =========================================

# This modal is for the setup command for bot config
# This is the bot configuration modal, this determines what is shown in the modal to the user along
# with the modals logic on submit and how everything is handled. The models are sill buggy but
# work fine.


class BotConfigModal(Modal):
    def __init__(self, bot, setup_id: str, guild_id: int):
        super().__init__(title="Bot Configuration")
        self.bot = bot
        self.setup_id = setup_id
        self.guild_id = guild_id

        self.prefix = TextInput(
            label="Bot Prefix", placeholder="Enter bot prefix", required=True
        )
        self.theme_color = TextInput(
            label="Theme Color", placeholder="#RRGGBB", required=True
        )

        self.add_item(self.prefix)
        self.add_item(self.theme_color)

    # This is the modal onsubmit logic to send it to the database. We do this so that the data is stored

    async def on_submit(self, interaction: Interaction):
        if interaction.response.is_done():
            return

        prefix = self.prefix.value
        theme_color = self.theme_color.value

        await setup_col.update_one(
            {"setup_id": self.setup_id},
            {
                "$set": {
                    "guild_id": self.guild_id,
                    "prefix": prefix,
                    "theme_color": theme_color,
                }
            },
            upsert=True,
        )

        response_message = f"<:NexureSuccess:1370202310113886339> Bot prefix set to `{prefix}` and theme color set to `{theme_color}`."
        setup_cog = self.bot.get_cog("SetupCog")
        if setup_cog.setup_message_id:
            await interaction.response.send_message(response_message, ephemeral=True)
        else:
            await interaction.response.send_message(response_message, ephemeral=True)


# This modal is for the setup command for banner config
# This is the banner configuation model, similar to the bot config but for banners like SSD, SSU, Server Shutdown, Server Startup
# and svote. The user can enter a url like https://example.com


class PluginConfigModal(Modal):
    def __init__(self, bot, setup_id: str, guild_id: int):
        super().__init__(title="Module Configuration")

        self.bot = bot
        self.setup_id = setup_id
        self.guild_id = guild_id

        # Text input for modules
        self.modules_input = TextInput(
            label="Modules",
            placeholder="Enter module names separated by commas",
            style=discord.TextStyle.short,
            required=True,
        )

        self.add_item(self.modules_input)

    async def on_submit(self, interaction: Interaction):
        if interaction.response.is_done():
            return

        # Gather selected modules from the input
        selected_modules = [
            module.strip() for module in self.modules_input.value.split(",")
        ]

        # Update the database with the enabled/disabled modules
        enabled_modules = {module: True for module in selected_modules}

        await setup_col.update_one(
            {"setup_id": self.setup_id},
            {"$set": {"guild_id": self.guild_id, "enabled_modules": enabled_modules}},
            upsert=True,
        )

        response_message = f"<:NexureSuccess:1370202310113886339> Modules updated: {', '.join(selected_modules)}."

        # Retrieve the setup cog and update the setup message
        setup_cog = self.bot.get_cog("SetupCog")
        if setup_cog.setup_message_id:
            setup_message = await interaction.channel.fetch_message(
                setup_cog.setup_message_id
            )
            await setup_message.edit(content=response_message, embed=None, view=None)
        else:
            await interaction.response.send_message(response_message, ephemeral=True)