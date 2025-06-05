import discord
import platform
from discord.ui import View, Button
from utils.constants import NexureConstants


constants = NexureConstants()
        

class AboutEmbed:
    @staticmethod
    def create_info_embed(
        uptime,
        guilds,
        users,
        latency,
        bot_name,
        bot_icon,
        environment,
        command_run_time,
        thumbnail_url,
    ):
        embed = discord.Embed(
            description=(
                "Nexure Solutions is a comprehensive business management and digital solutions provider, dedicated to helping companies achieve their goals in a competitive, digital-first world."
            ),
            color=discord.Color.from_str("#2a2c30"),
        )

        embed.add_field(name="", value=(""), inline=False)

        embed.add_field(
            name="Nexure Information",
            value=(
                f"> **Servers:** `{guilds:,}`\n"
                f"> **Users:** `{users:,}`\n"
                f"> **Uptime:** <t:{int((uptime.timestamp()))}:R>\n"
                f"> **Latency:** `{round(latency * 1000)}ms`"
            ),
            inline=True,
        )

        embed.add_field(
            name="System Information",
            value=(
                f"> **Language:** `Python`\n"
                f"> **Host OS:** `{platform.system()}`\n"
                f"> **Host:** `Nexure Solutions`"
            ),
            inline=True,
        )

        embed.add_field(name="", value=(""), inline=False)

        embed.set_footer(
            text=f"© Nexure Solutions LLP | {environment.capitalize()} • {command_run_time}"
        )

        embed.set_author(name=bot_name, icon_url=bot_icon)
        embed.set_thumbnail(url=thumbnail_url)
        return embed


class AboutWithButtons:
    @staticmethod
    def create_view():
        view = View()

        support_server_button = Button(
            label="Support Server",
            emoji="<:NexureChat:1377219689041756310>",
            style=discord.ButtonStyle.primary,
            url="https://discord.gg/bvVNAzm89a",
        )

        view.add_item(support_server_button)

        return view
        

class PingCommandEmbed:
    @staticmethod
    def create_ping_embed(
        latency: float,
        uptime,
    ):
        embed = discord.Embed(
            title="<:1E:1303180097200586843> Nexure",
            color=constants.nexure_embed_color_setup(),
        )

        embed.set_thumbnail(
            url="https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png"
        )

        embed.add_field(
            name="<:settings:1377235960902848593> **Network Information**",
            value=(
                f"**Latency:** `{round(latency * 1000)}ms` \n"
                f"**Uptime:** <t:{int(uptime.timestamp())}:R>"
            ),
            inline=False,
        )

        return embed