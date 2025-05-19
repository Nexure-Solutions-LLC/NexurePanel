import discord
from discord.ext import commands
from typing import List
from utils.embeds import PingCommandEmbed, ReminderListEmbed


class PingPaginationView(discord.ui.View):
    def __init__(
        self, nexure: commands.Bot, latency, database_latency, uptime, shard_info
    ):
        super().__init__()
        self.nexure = nexure
        self.latency = latency
        self.database_latency = database_latency
        self.uptime = uptime
        self.shard_info = shard_info
        self.page = 0
        self.max_page = (len(shard_info) // 5) + 1

    async def update_message(self, interaction: discord.Interaction):

        embed = PingCommandEmbed.create_ping_embed(
            self.latency, self.database_latency, self.uptime, self.shard_info, self.page
        )

        self.update_buttons()

        await interaction.response.edit_message(embed=embed, view=self)

    def update_buttons(self):
        self.prev_button.disabled = self.page == 0
        self.next_button.disabled = self.page >= self.max_page
        self.page_button.label = "Network" if self.page == 0 else "Shards"
        self.page_button.emoji = (
            "<:settings:1338809252948738152>"
            if self.page == 0
            else "<:clock:1338811480451055719>"
        )

    @discord.ui.button(
        emoji="<:left:1338812178731503616>",
        style=discord.ButtonStyle.gray,
        disabled=True,
    )
    async def prev_button(
        self, interaction: discord.Interaction, button: discord.ui.Button
    ):
        if self.page > 0:
            self.page -= 1
            self.next_button.disabled = False
            if self.page == 0:
                self.prev_button.disabled = True
            await self.update_message(interaction)

    @discord.ui.button(
        emoji="<:settings:1338809252948738152>",
        label="Network",
        style=discord.ButtonStyle.blurple,
        disabled=True,
    )
    async def page_button(
        self, interaction: discord.Interaction, button: discord.ui.Button
    ):
        pass

    @discord.ui.button(
        emoji="<:right:1338812220825665650>", style=discord.ButtonStyle.gray
    )
    async def next_button(
        self, interaction: discord.Interaction, button: discord.ui.Button
    ):
        if self.page < self.max_page:
            self.page += 1
            self.prev_button.disabled = False
            if self.page == self.max_page:
                self.next_button.disabled = True
            await self.update_message(interaction)


class ReminderPaginationView(discord.ui.View):
    def __init__(self, nexure: commands.Bot, reminders: List[dict], per_page: int = 5):
        super().__init__()
        self.nexure = nexure
        self.reminders = reminders
        self.per_page = per_page
        self.page = 0

        self.max_page = (len(reminders) // per_page) + (
            1 if len(reminders) % per_page > 0 else 0
        )

        if self.max_page <= 1:
            self.prev_button.disabled = True
            self.next_button.disabled = True

    async def update_message(self, interaction: discord.Interaction):
        start_index = self.page * self.per_page
        end_index = (self.page + 1) * self.per_page
        current_page_reminders = self.reminders[start_index:end_index]

        embed = ReminderListEmbed(
            current_page_reminders, self.page + 1, self.max_page
        ).create_embed()
        self.update_buttons()
        await interaction.response.edit_message(embed=embed, view=self)

    def update_buttons(self):
        if self.max_page <= 1:
            self.prev_button.disabled = True
            self.next_button.disabled = True
        else:
            self.prev_button.disabled = self.page == 0
            self.next_button.disabled = self.page >= self.max_page - 1

    @discord.ui.button(
        emoji="<:left:1338812178731503616>",
        style=discord.ButtonStyle.gray,
        disabled=True,
    )
    async def prev_button(
        self, interaction: discord.Interaction, button: discord.ui.Button
    ):
        if self.page > 0:
            self.page -= 1
            await self.update_message(interaction)

    @discord.ui.button(
        emoji="<:right:1338812220825665650>", style=discord.ButtonStyle.gray
    )
    async def next_button(
        self, interaction: discord.Interaction, button: discord.ui.Button
    ):
        if self.page < self.max_page - 1:
            self.page += 1
            await self.update_message(interaction)


class GuildPaginator(discord.ui.View):
    def __init__(self, ctx, guilds, per_page=10):
        super().__init__()
        self.ctx = ctx
        self.guilds = guilds
        self.per_page = per_page
        self.page = 0
        self.max_pages = (len(guilds) - 1) // per_page + 1
        self.message = None

    def get_embed(self):
        start = self.page * self.per_page
        end = start + self.per_page
        guild_list = "\n".join(
            [
                f"> **Guild Name:** {g.name}\n> **Member Count:** {g.member_count}\n> **Guild ID:** `({g.id})`\n"
                for g in self.guilds[start:end]
            ]
        )

        embed = discord.Embed(
            title="Guilds by Member Count",
            description=guild_list or "No guilds available",
            color=discord.Color.blue(),
        )
        embed.set_footer(text=f"Page {self.page + 1}/{self.max_pages}")
        return embed

    async def update_message(self):
        if self.message:
            await self.message.edit(embed=self.get_embed(), view=self)

    @discord.ui.button(
        emoji="<:left:1338812178731503616>",
        style=discord.ButtonStyle.primary,
        disabled=True,
    )
    async def previous_page(
        self, interaction: discord.Interaction, button: discord.ui.Button
    ):
        self.page -= 1
        self.next_page.disabled = False
        if self.page == 0:
            button.disabled = True
        await self.update_message()
        await interaction.response.defer()

    @discord.ui.button(
        emoji="<:right:1338812220825665650>", style=discord.ButtonStyle.primary
    )
    async def next_page(
        self, interaction: discord.Interaction, button: discord.ui.Button
    ):
        self.page += 1
        self.previous_page.disabled = False
        if self.page >= self.max_pages - 1:
            button.disabled = True
        await self.update_message()
        await interaction.response.defer()

    async def send(self):
        self.message = await self.ctx.send(embed=self.get_embed(), view=self)
        await self.ctx.message.delete()