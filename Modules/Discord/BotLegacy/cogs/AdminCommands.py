import discord
import uuid
import time
from discord.ui import View, Button
from discord.ext import commands
from utils.constants import NexureConstants
from utils.utils import get_next_case_id, NexureContext
from utils.constants import blacklists, blacklist_bypass, cases
from utils.pagination import GuildPaginator
from datetime import timedelta
from datetime import datetime


constants = NexureConstants()


# This is the admins cog for the bots admin commands that only server admins may run.
# This includes a debug command to debug the bot.


class AdminCommandsCog(commands.Cog):
    def __init__(self, nexure):
        self.nexure = nexure

    @commands.command()
    @commands.is_owner()
    async def checkguild(self, ctx: NexureContext, id: str):
        return

    @commands.command()
    async def guildlist(self, ctx: NexureContext):
        role = discord.utils.get(ctx.guild.roles, id=1175891590397571166)
        if ctx.guild.id == 1175890904230408223 and role in ctx.author.roles:
            guilds = sorted(ctx.bot.guilds, key=lambda g: -g.member_count)
            view = GuildPaginator(ctx, guilds)
            await view.send()
        else:
            await ctx.send_error("You do not have permission to use this command.")

    # This command will add users into blacklist_bypass collection so they can run commands like JSK
    # and blacklist_guild or blacklist_user.

    @commands.command()
    async def addowner(self, ctx: NexureContext, user: discord.User):
        role = discord.utils.get(ctx.guild.roles, id=1175891590397571166)
        if ctx.guild.id == 1175890904230408223 and role in ctx.author.roles:
            if user.id in constants.bypassed_users:
                return await ctx.send_error(
                    f"{user.mention} is already in the bypass list."
                )

            # Add the user to the MongoDB collection

            await blacklist_bypass.insert_one({"discord_id": user.id})

            await ctx.send_success(f"{user.mention} has been added to the bypass list.")

    # This command will remove owners from the bypassed users and prevent them from using blacklist commands
    # or JSK commands. This is incase the developer or owner leaves or steps down.

    @commands.command()
    async def removeowner(self, ctx: NexureContext, user: discord.User):
        role = discord.utils.get(ctx.guild.roles, id=1175891590397571166)
        if ctx.guild.id == 1175890904230408223 and role in ctx.author.roles:
            if user.id not in constants.bypassed_users:
                return await ctx.send_error(
                    f"{user.mention} is not in the bypass list."
                )

            await blacklist_bypass.delete_one({"discord_id": user.id})

            await ctx.send_success(
                f"{user.mention} has been removed from the bypass list."
            )

    # This allows the searching of bot owners and people bypassed, this should only be owners and developers but we
    # need a way to track it.

    @commands.command()
    async def showowners(self, ctx: NexureContext):
        role = discord.utils.get(ctx.guild.roles, id=1175891590397571166)
        if ctx.guild.id != 1175890904230408223 or role not in ctx.author.roles:
            return await ctx.send_error(
                f"You do not have permission to use this command."
            )

        owners_cursor = blacklist_bypass.find({})
        owners = await owners_cursor.to_list(length=None)
        processed_ids = set()
        owner_list = []

        for owner in owners:
            discord_id = owner.get("discord_id")
            if discord_id and discord_id not in processed_ids:
                user = await self.nexure.fetch_user(discord_id)
                if user:
                    owner_list.append(f"{user.mention} (`{discord_id}`)")
                    processed_ids.add(discord_id)

        pages = []
        page_size = 5

        for i in range(0, len(owner_list), page_size):
            embed = discord.Embed(
                title="Nexure Owners and Developers",
                description=(
                    "Listed below are the owners and developers of <:1E:1303180097200586843> **Nexure**, "
                    "when a new one is added using `!addowner` or when one is removed with `!removeowner` "
                    "this list will update."
                ),
                color=constants.nexure_embed_color_setup(),
            )

            embed.add_field(
                name="", value="\n".join(owner_list[i : i + page_size]), inline=False
            )

            embed.set_footer(
                text=f"Page {len(pages) + 1} of {((len(owner_list) - 1) // page_size) + 1}"
            )
            pages.append(embed)

        # This is the class for the button navigation when records become more than 5. This is to prevent overflow.

        class PaginationView(View):
            def __init__(self, embeds):
                super().__init__(timeout=60)
                self.embeds = embeds
                self.current_page = 0

                self.previous_button = Button(
                    emoji="<:left:1377235457393426513>",
                    style=discord.ButtonStyle.gray,
                    disabled=True,
                )
                self.previous_button.callback = self.previous_page

                self.next_button = Button(
                    emoji="<:right:1377235525911445586>",
                    style=discord.ButtonStyle.gray,
                    disabled=(len(embeds) <= 1),
                )
                self.next_button.callback = self.next_page

                self.add_item(self.previous_button)
                self.add_item(self.next_button)

            async def update_message(self, interaction):
                self.previous_button.disabled = self.current_page == 0
                self.next_button.disabled = self.current_page == len(self.embeds) - 1
                await interaction.response.edit_message(
                    embed=self.embeds[self.current_page], view=self
                )

            async def previous_page(self, interaction: discord.Interaction):
                if self.current_page > 0:
                    self.current_page -= 1
                    await self.update_message(interaction)

            async def next_page(self, interaction: discord.Interaction):
                if self.current_page < len(self.embeds) - 1:
                    self.current_page += 1
                    await self.update_message(interaction)

        view = PaginationView(pages)
        await ctx.send(embed=pages[0], view=view)

    # This is a custom sync command cause JSK sync is broken, this will sync the commands with Discord
    # guilds accross the platform that uses the bot.

    @commands.command()
    @commands.is_owner()
    async def sync(self, ctx: NexureContext, guild_id: int = None):
        if guild_id:
            guild = discord.Object(id=guild_id)
            try:
                guild_name = self.nexure.get_guild(guild_id).name
                loading_msg = await ctx.send_loading(
                    f"Syncing for ***{guild_name}**..."
                )
            except AttributeError:
                loading_msg = await ctx.send_loading(
                    f"Syncing for guild ID **{guild_id}**.."
                )
            synced = await self.nexure.tree.sync(guild=guild)
        else:
            loading_msg = await ctx.send_loading(f"Syncing commands globally...")
            synced = await self.nexure.tree.sync()
        await loading_msg.delete()
        await ctx.send_success(f"Synced **{len(synced)}** commands.")

    # This is the set of commands to unblacklist a user from the bot. This follows the same set of logic as
    # blacklisting the user but in the opposit order.

    @commands.command()
    @commands.is_owner()
    async def unblacklist(self, ctx: NexureContext, entity: int, *, reason: str):

        entity_type = ""

        # Check to see if its a user or guild

        try:
            entity_fetched: discord.User = await self.nexure.fetch_user(entity)
            entity_type = "user"
        except Exception:
            entity_fetched = entity
            entity_type = "guild"

        # Assign what to call it in confirm message

        if entity_type == "user":
            entity = entity_fetched.mention
            entity_id = entity_fetched.id
        elif entity_type == "guild":
            entity_id = entity

        # Checks to see if its in the db

        if not await blacklists.find_one(
            {"discord_id": entity_id, "type": entity_type}
        ):
            return await ctx.send_error(f"{entity} is not blacklisted.")

        case_entry = await cases.find_one(
            {"guild_id": ctx.guild.id, "user_id": entity_id, "type": "blacklist"},
            sort=[("timestamp", -1)],
        )

        if case_entry:

            await cases.update_one(
                {"_id": case_entry["_id"]}, {"$set": {"status": "cleared"}}
            )

            await blacklists.delete_one({"discord_id": entity_id, "type": entity_type})
            await ctx.send_success(f"**{entity}** has been unblacklisted for {reason}.")

        else:
            await ctx.send_error(f"{entity} is not blacklisted.")

    # This set of commands allows server administrators to blacklist the bot and prevent users
    # from runing commands.

    @commands.command()
    @commands.is_owner()
    async def blacklist(self, ctx: NexureContext, entity: int, *, reason: str):

        entity_type = ""

        # Checks to see if its a user or guild

        try:
            entity_fetched: discord.User = await self.nexure.fetch_user(entity)
            entity_type = "user"
        except Exception:
            entity_fetched = entity
            entity_type = "guild"

        # Assign what to call it in confirm message

        if entity_type == "user":
            entity = entity_fetched.mention
            entity_id = entity_fetched.id
        elif entity_type == "guild":
            entity_id = entity

        # Checks to see if its in the db

        if await blacklists.find_one({"discord_id": entity_id, "type": entity_type}):
            return await ctx.send_error(f"{entity} is already blacklisted.")

        case_id = await get_next_case_id(ctx.guild.id)

        # Creates, enters, and sends confirm message

        blacklist_entry = {
            "discord_id": entity_id,
            "type": entity_type,
            "reason": reason,
        }

        case_entry = {
            "case_id": case_id,
            "guild_id": ctx.guild.id,
            "user_id": entity_id,
            "moderator_id": ctx.author.id,
            "reason": reason,
            "timestamp": int(time.time()),
            "type": "blacklist",
            "status": "active",
        }

        await blacklists.insert_one(blacklist_entry)
        await cases.insert_one(case_entry)
        await ctx.send_success(
            f"**Case #{case_id} - {entity}** has been blacklisted for {reason}."
        )


async def setup(nexure):
    await nexure.add_cog(AdminCommandsCog(nexure))