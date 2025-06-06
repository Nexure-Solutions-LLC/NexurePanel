import asyncio, discord
from discord.ext import commands
from discord.ui import View, select, button
import os

from utils.utils import NexureContext
from utils.constants import NexureConstants, logger


class CloseOptions(View):
    def __init__(self, *, timeout = None, bot, constants):
        super().__init__(timeout=timeout)
        self.bot = bot
        self.user = None
        self.nexure_constants = constants
        self.ticket_id = None
    

    @button(label='Open', style=discord.ButtonStyle.green)
    async def open_callback(self, interaction: discord.Interaction, button: button):
        await self.load_data(interaction)

        roles = interaction.user.roles
        for role in roles:
            await asyncio.sleep(1e-3)
            if role.id in self.nexure_constants.support_roles():
                break

        else:
            raise commands.MissingPermissions()
    
        await interaction.channel.set_permissions(self.user, read_messages=True, send_messages=True)
        await interaction.response.send_message(
            embed=discord.Embed(title='Success!', description='Ticket opened.', colour=self.nexure_constants.colour()),
            ephemeral=True
        )

    
    @button(label='Delete', style=discord.ButtonStyle.red)
    async def delete_callback(self, interaction: discord.Interaction, button: button):
        await self.load_data(interaction)
        await interaction.response.defer()

        roles = interaction.user.roles
        for role in roles:
            await asyncio.sleep(1e-3)
            if role.id in self.nexure_constants.support_roles():
                break

        else:
            raise commands.MissingPermissions()


        html = f"""<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Ticket Transcript - {self.ticket_id}</title>
            <style>
                body {{
                    margin: 0;
                    padding: 0;
                    background-color: #36393f;
                    font-family: 'Whitney', 'Helvetica Neue', Helvetica, Arial, sans-serif;
                    color: #dcddde;
                }}
                .container {{
                    max-width: 800px;
                    margin: 20px auto;
                    background-color: #2f3136;
                    border-radius: 5px;
                    overflow: hidden;
                    box-shadow: 0 0 10px rgba(0,0,0,0.3);
                }}
                .header {{
                    background-color: #202225;
                    padding: 10px 20px;
                    border-bottom: 1px solid #292b2f;
                }}
                .header h1 {{
                    margin: 0;
                    font-size: 18px;
                    color: #fff;
                }}
                .messages {{
                    padding: 20px;
                }}
                .message {{
                    display: flex;
                    align-items: flex-start;
                    margin-bottom: 15px;
                }}
                .avatar {{
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    margin-right: 10px;
                    flex-shrink: 0;
                }}
                .content {{
                    flex: 1;
                }}
                .author {{
                    font-weight: 600;
                    color: #fff;
                }}
                .timestamp {{
                    margin-left: 8px;
                    font-size: 12px;
                    color: #72767d;
                }}
                .text {{
                    margin-top: 2px;
                    white-space: pre-wrap;
                    color: #dcddde;
                }}
                .mention {{
                    color: #00aff4;
                    background-color: rgba(0, 170, 255, 0.1);
                    padding: 2px 4px;
                    border-radius: 3px;
                    font-weight: 500;
                }}
                .embed {{
                    background-color: #2f3136;
                    border-left: 4px solid #00aff4;
                    padding: 10px;
                    margin-top: 5px;
                    border-radius: 3px;
                }}
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Ticket Transcript - {self.ticket_id} | #{interaction.channel.name}</h1>
                </div>
                <div class="messages">
        """

        messages = [msg async for msg in interaction.channel.history(limit=None, oldest_first=True)]

        for msg in messages:
            await asyncio.sleep(1e-3)
            author = msg.author.display_name
            avatar_url = msg.author.display_avatar.url
            timestamp = msg.created_at.strftime('%Y-%m-%d %H:%M')
            content = msg.content or "[No text content]"

            content = content.replace("@", '<span class="mention">@')

            embeds_html = ""
            for embed in msg.embeds:
                await asyncio.sleep(1e-3)
                embeds_html += f"""
                <div class="embed">
                    <div class="embed-title">{embed.title or ''}</div>
                    <div class="embed-description">{embed.description or ''}</div>
                </div>
                """

            html += f"""
                    <div class="message">
                        <img src="{avatar_url}" alt="{author}" class="avatar">
                        <div class="content">
                            <div>
                                <span class="author">{author}</span>
                                <span class="timestamp">{timestamp}</span>
                            </div>
                            <div class="text">{content}</div>
                            {embeds_html}
                        </div>
                    </div>
            """

        html += """
                </div>
            </div>
        </body>
        </html>
        """

    
        filename = f"ticket-{self.ticket_id}.html"
        file = discord.File(io.StringIO(html), filename=filename)

        embed = discord.Embed(title='Nexure Support: Transcript', description='Thank you for contacting our support team. Attached is a transcript for your ease.', colour=self.nexure_constants.colour())
        embed.set_thumbnail(url=self.bot.display_avatar.url)
        await self.user.send(embed=embed, file=file)

        transcript_channel = await self.bot.fetch_channel(self.nexure_constants.ticket_transcript())
        staff_embed = discord.Embed(title='Staff: Transcript', description='A new ticket transcript has just been generated. I\'ve attached it to this message. ', colour=self.nexure_constants.colour())
        await transcript_channel.send(embed=staff_embed, file=file)

        await interaction.channel.delete(reason=f'Ticket closed by {interaction.user}')


    async def load_data(self, interaction: discord.Interaction):
        user_id, ticket_id = await self.bot.database.fetchrow(f'SELECT oAuthID, id FROM {self.nexure_constants.sql_tickets()} WHERE channelID = %s;', interaction.channel.id)
        self.user = await self.bot.fetch_user(user_id)
        self.ticket_id = ticket_id


class TicketButtons(View):
    def __init__(self, *, timeout = None, bot, constants):
        super().__init__(timeout=timeout)
        self.bot = bot
        self.user = None
        self.nexure_constants = constants
        self.ticket_id = None

    @button(label='Close', style=discord.ButtonStyle.red, custom_id='Nexure-Ticket-Close')
    async def close_callback(self, interaction: discord.Interaction, button: button):
        await self.load_data(interaction)
        
        roles = interaction.user.roles
        for role in roles:
            await asyncio.sleep(1e-3)
            if role.id in self.nexure_constants.support_roles():
                break

        else:
            raise commands.MissingPermissions()
        
        view = CloseOptions(bot=self.bot, constants=self.nexure_constants)
        embed = discord.Embed(title='Closure options', description='Select one of the options below. **TICKETS ARE AUTOMATICALLY TRANSCRIBED.**', colour=self.nexure_constants.colour())

        await asyncio.gather(*(
            interaction.channel.set_permissions(self.user, read_messages=None, send_messages=None),
            interaction.response.send_message(embed=embed, view=view, ephemeral=True)
        ))


    @button(label='Close with reason', style=discord.ButtonStyle.red, custom_id='Nexure-Ticket-Close-Reason')
    async def close_reason_callback(self, interaction: discord.Interaction, button: button):
        await self.load_data(interaction)

        roles = interaction.user.roles
        for role in roles:
            await asyncio.sleep(1e-3)
            if role.id in self.nexure_constants.support_roles():
                break

        else:
            raise commands.MissingPermissions()
        
        embed = discord.Embed(title='Closure options', description='Select one of the options below. **TICKETS ARE AUTOMATICALLY TRANSCRIBED.**', colour=self.nexure_constants.colour())

        await asyncio.gather(*(
            interaction.channel.set_permissions(self.user, read_messages=False, send_messages=False),
            interaction.response.send_message(embed=embed)
        ))


    async def load_data(self, interaction: discord.Interaction):
        user_id, ticket_id = await self.bot.database.fetchrow(f'SELECT oAuthID, id FROM {self.nexure_constants.sql_tickets()} WHERE channelID = %s;', interaction.channel.id)
        self.user = await self.bot.fetch_user(user_id)
        self.ticket_id = ticket_id


class PanelView(View):
    def __init__(self, *, timeout = None, bot, constants):
        super().__init__(timeout=timeout)
        self.bot = bot
        self.user = None
        self.nexure_constants = constants
    
    @select(
        custom_id='Nexure-Ticket-Select',
        placeholder='Open a ticket',
        min_values=1,
        max_values=1,
        options=[
            discord.SelectOption(label='General Support', description='Questions? Queries? No other tickets match your selection?', value='gspt'),
            discord.SelectOption(label='Partnerships', description='Looking to work with us?', value='past'),
            discord.SelectOption(label='Product Support', description='Issues with our products?', value='prst')
        ]
    )
    async def select_on_submit(self, interaction: discord.Interaction, select: select):
        await interaction.response.defer()

        self.user = interaction.user
        guild = interaction.guild

        overwrites = {
            interaction.guild.default_role: discord.PermissionOverwrite(read_messages=False, send_messages=False),
            interaction.guild.me: discord.PermissionOverwrite(read_messages=True, send_messages=True),
            self.user: discord.PermissionOverwrite(read_messages=True, send_messages=True)
        }

        if select.values[0] == 'gspt':
            title = 'Nexure Support: General'
            description = 'Thank you for contacting Nexure. Please elaborate on your issue below, this will help us assist you further. A member of our team will be with you shortly, please refrain from pinging staff.'
            ping = f'|| {self.user.mention} <@&1175892144419000462 ||'

            role = guild.get_role(1175892144419000462)
            overwrites[role] = discord.PermissionOverwrite(read_messages=True, send_messages=True)

        elif select.values[0] == 'past':
            title = 'Nexure Support: Partnership'
            description = 'Thank you for contacting Nexure. Please elaborate on your company, and your position within it. A member of our team will be with you shortly, please refrain from pinging staff.'
            ping = f'|| {self.user.mention} <@&1338057505581826058> ||'

            role = guild.get_role(1175892144419000462)
            overwrites[role] = discord.PermissionOverwrite(read_messages=True, send_messages=True)

        elif select.values[0] == 'post':
            title = 'Nexure Support: Product'
            description = 'Thank you for contacting Nexure. Please elaborate on your the issue you are experiencing, this will help us assist you further. A member of our team will be with you shortly, please refrain from pinging staff.'
            ping = f'|| {self.user.mention} <@&1175899654404182116> <@&1175892144419000462> ||'

            roles = [
                guild.get_role(1175892144419000462),
                guild.get_role(1175899654404182116)
            ]

            for role in roles:
                overwrites[role] = discord.PermissionOverwrite(read_messages=True, send_messages=True)
        
        else:
            return await interaction.followup.send(
                embed=discord.Embed(title='Error!', description=f'{self.nexure_constants.emojis()['failed']} Sorry! I was not able to locate that ticket type within our system. Please try again.'),
                ephemeral=True
            )
        
        ticket_category = await self.bot.fetch_channel(self.nexure_constants.ticket_category())
        
        accountNumber = None
        if (email := await ctx.bot.database.fetchval(
            f'SELECT email FROM {self.nexure_constants.sql_users()} WHERE oAuthID = %s;',
            self.user.id
        )):
            accountNumber = await ctx.bot.database.fetchval(
                f'SELECT accountNumber FROM {self.nexure_constants.sql_accounts()} WHERE email = %s;',
                email
            )

        await ctx.bot.database.execute(f'INSERT INTO {self.nexure_constants.sql_tickets()} (accountNumber, oAuthID, channelID, type) VALUES (%s, %s, %s, %s);', str(accountNumber), str(self.user.id), None, select.values[0])
        ticket_id = await ctx.bot.database.fetchval("SELECT LAST_INSERT_ID();")
    
        view = TicketButtons(bot=self.bot, constants=self.nexure_constants)
        channel = await ticket_category.create_text_channel(name=f"{self.user.name}-{ticket_id}", overwrites=overwrites)
        embed = discord.Embed(title=title, description=description, colour=self.nexure_constants.colour())
        embed.set_thumbnail(url=ctx.bot.display_avatar.url)
        await channel.send(content=ping, embed=embed, view=view)
        await interaction.followup.send(embed=discord.Embed(title='Success!', description=f"{self.nexure_constants.emojis()['success']} Ticket created! {channel.jump_url}", colour=self.nexure_constants.colour()), ephemeral=True)

        await ctx.bot.database.execute(f'UPDATE {self.nexure_constants.sql_tickets()} SET channelID = %s WHERE id = %s;', channel.id, ticket_id)


class Tickets(commands.Cog):
    def __init__(self, bot):
        self.bot = bot
        self.nexure_constants = NexureConstants()

    @commands.command()
    @commands.is_owner()
    async def send_panel(self, ctx: NexureContext):
        embed = discord.Embed(title='Nexure Solutions Support', description='Welcome to Nexure! Got a question, query, or need assistance from one of our experts? Go ahead and open a ticket, and our team will be more then happy to help you.', colour=self.nexure_constants.colour())
        embed.set_thumbnail(url=ctx.bot.display_avatar.url)

        view = PanelView(bot=self.bot, constants=self.nexure_constants)
        await ctx.send(embed=embed, view=view)


async def setup(bot):
    nexure_constants = NexureConstants()
    await bot.add_cog(Tickets(bot))
    try:
        bot.add_view(PanelView(bot=bot, constants=nexure_constants))
        logger.info('Successfully loaded PanelView.')
    except Exception as e:
        logger.error(f'Failed to load PanelView. Error thrown: {e}')

    try:
        bot.add_view(TicketButtons(bot=bot, constants=nexure_constants))
        logger.info('Successfully loaded TicketButtons.')
    except Exception as e:
        logger.error(f'Failed to load TicketButtons. Error thrown: {e}')


# Love, bread.