import discord
from discord.ext import commands
from discord.ui import View, Button
from datetime import datetime, timedelta
from zuid import ZUID
from typing import Optional
import contextlib
import re

from utils.utils import NexureContext, Timespan
from utils.constants import NexureConstants

case_prefix = NexureConstants().case_prefix()
generator = ZUID(prefix=case_prefix, length=22)

class Paginator(View):
    def __init__(self, pages: list[discord.Embed], user, timeout: int = 300):
        super().__init__(timeout=timeout)
        self.user = user
        self.pages = pages
        self.current_page = 0

        self.previous_page = Button(emoji='<:left:1377235457393426513>', style=discord.ButtonStyle.grey)
        self.previous_page.callback = self.previous_page_callback
        self.add_item(self.previous_page)

        self.next_page = Button(emoji='<:right:1377235525911445586>', style=discord.ButtonStyle.grey)
        self.next_page.callback = self.next_page_callback
        self.add_item(self.next_page)

    async def previous_page_callback(self, interaction: discord.Interaction):
        if not await self.interaction_check(interaction):
            return
        
        self.current_page = (self.current_page - 1) % len(self.pages)
        await interaction.response.edit_message(embed=self.pages[self.current_page], view=self)

    async def next_page_callback(self, interaction: discord.Interaction):
        if not await self.interaction_check(interaction):
            return
        self.current_page = (self.current_page + 1) % len(self.pages)
        await interaction.response.edit_message(embed=self.pages[self.current_page], view=self)

    async def interaction_check(self, interaction: discord.Interaction) -> bool:
        if interaction.user.id != self.user.id:
            await interaction.response.send_message(embed=discord.Embed(title='Error!', description='Sorry! You may not interact with this.', colour=discord.Colour.red()), ephemeral=True)
            return False
        
        return True

class Moderation(commands.Cog):
    def __init__(self, bot):
        self.bot = bot
        self.nexure_constants = NexureConstants()

    @commands.hybrid_command()
    @commands.has_permissions(ban_members=True)
    async def ban(self, ctx: NexureContext, user: discord.Member, *, reason: str):
        await ctx.defer(ephemeral=False)

        if user.id == self.bot.user.id or user.id == ctx.author.id:
            return await ctx.send_error("Sorry! You cannot ban this user.")

        if not ctx.guild.me.top_role.position > user.top_role.position:
            raise discord.Forbidden("Bot's role is not high enough to ban this user")
        
        if not ctx.author.top_role.position > user.top_role.position:
            raise commands.MissingPermissions(["ban_members"])

        case_id = generator() 
        with contextlib.suppress(discord.Forbidden):
            await user.send(embed=(
                discord.Embed(title='Ban', description='You have been banned from Nexure. If you would like to appeal please do not hesitate in [contacting us](https://nexuresolutions.com/).', colour=self.nexure_constants.colour())
                .set_thumbnail(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')
                .add_field(name='Issued by', value=f'{ctx.author.mention} (`{ctx.author.id}`)')
                .add_field(name='Reason', value=reason)
                .set_footer(text=f'Case ID: {case_id}')
            ))

        try:
            await user.ban(delete_message_days=7, reason=f'{ctx.author} has issued a ban. Reason: {reason}')
        except discord.Forbidden:
            return await ctx.send_error("Sorry! I was unable to ban the user.")

        accountNumber = None
        if (email := await ctx.bot.database.fetchval(
            f'SELECT email FROM {self.nexure_constants.sql_users()} WHERE oAuthID = %s;',
            user.id
        )):
            accountNumber = await ctx.bot.database.fetchval(
                f'SELECT accountNumber FROM {self.nexure_constants.sql_accounts()} WHERE email = %s;',
                email
            )
    
        await ctx.bot.database.execute(
            f'INSERT INTO {self.nexure_constants.sql_cases()} (caseID, accountNumber, oAuthID, reason, type) VALUES (%s, %s, %s, %s, %s);',
            case_id, str(accountNumber), str(user.id), reason, 'ban'
        )         

        with contextlib.suppress(discord.Forbidden):
            if (channel := ctx.bot.get_channel(1175890905748746390)):
                await channel.send(embed=(
                    discord.Embed(title=f'Case ID: {case_id}', description='A new ban has been issued. See below for more information.', colour=self.nexure_constants.colour())
                    .add_field(name='Issued by', value=f'{ctx.author.mention} (`{ctx.author.id}`)', inline=True)
                    .add_field(name='Issued to', value=f'{user.mention} (`{user.id}`)', inline=True)
                    .add_field(name='Reason', value=reason, inline=False)
                    .set_thumbnail(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')
                ))

        return await ctx.reply(
            ephemeral=False,
            embed=(
                discord.Embed(title='Success!', description=f"{self.nexure_constants.emojis()['success']} User banned!", colour=self.nexure_constants.colour())
                .set_footer(text=f'Case ID: {case_id}')
            )
        )


    @commands.hybrid_command()
    @commands.has_permissions(ban_members=True)
    async def unban(self, ctx: NexureContext, user: discord.Member = None, case: str = None):
        await ctx.defer(ephemeral=False)

        if user != None:
            column = 'oAuthID'
            param = str(user.id)
        else:
            column = 'caseID'
            param = str(case)

        if not (authID := await ctx.bot.database.fetchval(
            f'SELECT oAuthID FROM {self.nexure_constants.sql_cases()} WHERE {column} = %s AND type = %s AND active = %s;',
            param, 'ban', True
        )):
            return await ctx.send_error("Sorry! I could not associate this case ID with a user.")
                
        user = await ctx.bot.fetch_user(int(authID))
        with contextlib.suppress(discord.Forbidden):
            await user.send(embed=(
                discord.Embed(title='Unbanned', description='You have been unbanned from Nexure. If you have any questions or queries, please do not hesitate in [contacting us](https://nexuresolutions.com/).', colour=self.nexure_constants.colour())
                .set_thumbnail(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')
                .add_field(name='Revoked by', value=f'{ctx.author.mention} (`{ctx.author.id}`)')
            ))

        async for ban in ctx.guild.bans():
            await asyncio.sleep(1e-3)
            if ban.user.id == user.id:
                try:
                    await ctx.guild.unban(user, reason=f'{ctx.author} has revoked a ban.')
                except discord.Forbidden:
                    return await ctx.send_error("Sorry! I was unable to unban the user.")   
                break
        else:
            return await ctx.send_error("Sorry! This user is not banned.")

        with contextlib.suppress(discord.Forbidden):
            if (channel := ctx.bot.get_channel(1175890905748746390)):
                staff_embed = discord.Embed(title='Moderation Action', description='A ban has been revoked. See below for more information.', colour=self.nexure_constants.colour())
                staff_embed.add_field(name='Revoked by', value=f'{ctx.author.mention} (`{ctx.author.id}`)', inline=True)
                staff_embed.add_field(name='Revoked from', value=f'{user.mention} (`{user.id}`)', inline=True)
                staff_embed.set_thumbnail(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')
                await channel.send(embed=staff_embed)

        return await ctx.send_success("User unbanned!")


    @commands.hybrid_command()
    @commands.has_permissions(kick_members=True)
    async def kick(self, ctx: NexureContext, user: discord.Member, *, reason: str):
        await ctx.defer(ephemeral=False)

        if user.id == self.bot.user.id or user.id == ctx.author.id:
            return await ctx.send_error("Sorry! You cannot kick this user.")

        if not ctx.guild.me.top_role.position > user.top_role.position:
            raise discord.Forbidden("Bot's role is not high enough to kick this user")
        
        if not ctx.author.top_role.position > user.top_role.position:
            raise commands.MissingPermissions(["kick_members"])

        case_id = generator()
        with contextlib.suppress(discord.Forbidden):
            await user.send(embed=(
                discord.Embed(title='Kick', description='You have been kicked from Nexure, you may rejoin at anytime. If you have any question, or queries, please do not hesitate in [contacting us](https://nexuresolutions.com/).', colour=self.nexure_constants.colour())
                .set_thumbnail(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')
                .add_field(name='Issued by', value=f'{ctx.author.mention} (`{ctx.author.id}`)')
                .add_field(name='Reason', value=reason)
                .set_footer(text=f'Case ID: {case_id}')
            ))

        try:
            await user.kick(reason=f'{ctx.author} has issued a kick. Reason: {reason}')
        except discord.Forbidden:
            return await ctx.send_error("Sorry! I was unable to kick the user.")

        accountNumber = None
        if (email := await ctx.bot.database.fetchval(
            f'SELECT email FROM {self.nexure_constants.sql_users()} WHERE oAuthID = %s;',
            user.id
        )):
            accountNumber = await ctx.bot.database.fetchval(
                f'SELECT accountNumber FROM {self.nexure_constants.sql_accounts()} WHERE email = %s;',
                email
            )

        await ctx.bot.database.execute(
            f'INSERT INTO {self.nexure_constants.sql_cases()} (caseID, accountNumber, oAuthID, reason, type) VALUES (%s, %s, %s, %s, %s);',
            case_id, str(accountNumber), str(user.id), reason, 'kick'
        )

        with contextlib.suppress(discord.Forbidden):
            if (channel := ctx.bot.get_channel(1175890905748746390)):
                await channel.send(embed=(
                    discord.Embed(title=f'Case ID: {case_id}', description='A new kick has been issued. See below for more information.', colour=self.nexure_constants.colour())
                    .add_field(name='Issued by', value=f'{ctx.author.mention} (`{ctx.author.id}`)', inline=True)
                    .add_field(name='Issued to', value=f'{user.mention} (`{user.id}`)', inline=True)
                    .add_field(name='Reason', value=reason, inline=False)
                    .set_thumbnail(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')
                ))

        return await ctx.send(
            ephemeral=False,
            embed=(
                discord.Embed(title='Success!', description=f"{self.nexure_constants.emojis()['success']} User kicked!", colour=self.nexure_constants.colour())
                .set_footer(text=f'Case ID: {case_id}')
            )
        )
        

    @commands.hybrid_command()
    @commands.has_permissions(moderate_members=True)
    async def mute(self, ctx: NexureContext, user: discord.Member, duration: Optional[Timespan] = None, *, reason: str):
        await ctx.defer(ephemeral=False)

        if timespan:
            if timespan.seconds < 1 or timespan.seconds > 2.419e6:
                return await ctx.send_error("Sorry! Send a valid timespan between 1 second and 4 weeks.")

        if user.id == self.bot.user.id or user.id == ctx.author.id:
            return await ctx.send_error("You cannot mute this user.")

        if not ctx.guild.me.top_role.position > user.top_role.position:
            raise discord.Forbidden("Bot's role is not high enough to mute this user")
        
        if not ctx.author.top_role.position > user.top_role.position:
            raise commands.MissingPermissions(["moderate_members"])

        until_dt = datetime.now().astimezone() + timedelta(seconds=(timespan.seconds if timespan else 2419200)),
        duration = int(until_dt.timestamp())

        case_id = generator()
        with contextlib.suppress(discord.Forbidden):
            await user.send(embed=(
                discord.Embed(title='Mute', description='You have been muted in Nexure. If you would like to appeal please do not hesitate in [contacting us](https://nexuresolutions.com/).', colour=self.nexure_constants.colour())
                .set_thumbnail(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')
                .add_field(name='Issued by', value=f'{ctx.author.mention} (`{ctx.author.id}`)', inline=True)
                .add_field(name='Duration', value=f"<t:{duration}:R>", inline=True)
                .add_field(name='Reason', value=reason, inline=False)
                .set_footer(text=f'Case ID: {case_id}')
            ))

        try:
            await user.timeout(until_dt, reason=reason)
        except discord.Forbidden:
            return await ctx.send_error("Sorry! I was unable to mute the user.")

        accountNumber = None
        if (email := await ctx.bot.database.fetchval(
            f'SELECT email FROM {self.nexure_constants.sql_users()} WHERE oAuthID = %s;',
            user.id
        )):
            accountNumber = await ctx.bot.database.fetchval(
                f'SELECT accountNumber FROM {self.nexure_constants.sql_accounts()} WHERE email = %s;',
                email
            )

        await ctx.bot.database.execute(
            f'INSERT INTO {self.nexure_constants.sql_cases()} (caseID, accountNumber, oAuthID, reason, type) VALUES (%s, %s, %s, %s, %s);',
            case_id, str(accountNumber), str(user.id), reason, 'mute'
        )

        with contextlib.suppress(discord.Forbidden):
            if (channel := ctx.bot.get_channel(1175890905748746390)):
                await channel.send(embed=(
                    discord.Embed(title=f'Case ID: {case_id}', description='A new mute has been issued. See below for more information.', colour=self.nexure_constants.colour())
                    .add_field(name='Issued by', value=f'{ctx.author.mention} (`{ctx.author.id}`)', inline=True)
                    .add_field(name='Issued to', value=f'{user.mention} (`{user.id}`)', inline=True)
                    .add_field(name='Duration', value=f"<t:{duration}:R>", inline=True)
                    .add_field(name='Reason', value=reason, inline=False)
                    .set_thumbnail(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')
                ))

        return await ctx.send(
            ephemeral=False,
            embed=(
                discord.Embed(title='Success!', description=f"{self.nexure_constants.emojis()['success']} User muted!", colour=self.nexure_constants.colour())
                .set_footer(text=f'Case ID: {case_id}')
            )
        )


    @commands.hybrid_command()
    @commands.has_permissions(moderate_members=True)
    async def warn(self, ctx: NexureContext, user: discord.Member, *, reason: str):
        await ctx.defer(ephemeral=False)

        if user.id == self.bot.user.id or user.id == ctx.author.id:
            return await ctx.send_error("Sorry! You cannot warn this user.")
        
        if not ctx.author.top_role.position > user.top_role.position:
            raise commands.MissingPermissions(["moderate_members"])

        case_id = generator()
        with contextlib.suppress(discord.Forbidden):
            await user.send(embed=(
                discord.Embed(title='Warning', description='You have been issued a warning within Nexure. If you would like to appeal please do not hesitate in [contacting us](https://nexuresolutions.com/).', colour=self.nexure_constants.colour())
                .set_thumbnail(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')
                .add_field(name='Issued by', value=f'{ctx.author.mention} (`{ctx.author.id}`)')
                .add_field(name='Reason', value=reason)
                .set_footer(text=f'Case ID: {case_id}')
            ))

        accountNumber = None
        if (email := await ctx.bot.database.fetchval(
            f'SELECT email FROM {self.nexure_constants.sql_users()} WHERE oAuthID = %s;',
            user.id
        )):
            accountNumber = await ctx.bot.database.fetchval(
                f'SELECT accountNumber FROM {self.nexure_constants.sql_accounts()} WHERE email = %s;',
                email
            )

        await ctx.bot.database.execute(
            f'INSERT INTO {self.nexure_constants.sql_cases()} (caseID, accountNumber, oAuthID, reason, type) VALUES (%s, %s, %s, %s, %s);',
            case_id, str(accountNumber), str(user.id), reason, 'warning'
        )
                 
        with contextlib.suppress(discord.Forbidden):
            if (channel := ctx.bot.get_channel(1175890905748746390)):
                await channel.send(embed=(
                    discord.Embed(title=f'Case ID: {case_id}', description='A new warning has been issued. See below for more information.', colour=self.nexure_constants.colour())
                    .add_field(name='Issued by', value=f'{ctx.author.mention} (`{ctx.author.id}`)', inline=True)
                    .add_field(name='Issued to', value=f'{user.mention} (`{user.id}`)', inline=True)
                    .add_field(name='Reason', value=reason, inline=False)
                    .set_thumbnail(url='https://media.discordapp.net/attachments/1370199512123052033/1377213812947816510/NexureLogoSquare.png')
                ))

        return await ctx.send(
            ephemeral=False,
            embed=(
                discord.Embed(title='Success!', description=f"{self.nexure_constants.emojis()['success']} User warned!", colour=self.nexure_constants.colour())
                .set_footer(text=f'Case ID: {case_id}')
            )
        )


    @commands.hybrid_group()
    async def revoke(self, ctx: NexureContext):
        pass

    @revoke.command()
    @commands.has_permissions(moderate_members=True)
    async def case(self, ctx: NexureContext, case: str):
        if not await ctx.bot.database.fetchval(
            f'SELECT active FROM {self.nexure_constants.sql_cases()} WHERE caseID = %s;',
            case
        ):
            return await ctx.send_error("Sorry! I could not associate this case ID with a moderation action.") 

        await asyncio.gather(*(
            ctx.bot.database.execute(
                f'UPDATE {self.nexure_constants.sql_cases()} SET active = 0 WHERE caseID = %s;',
                case
            ),
            ctx.send_success("Case revoked!")
        ))


    @commands.hybrid_command()
    @commands.has_permissions(moderate_members=True)
    async def modlogs(self, ctx: NexureContext, user: discord.Member):
        embed = discord.Embed(title=' ', description=' ', colour=NexureConstants().colour())
        embed.set_author(name=f"{user.name}'s Modlogs")

        if not (results := await ctx.bot.database.execute(
            f'SELECT * FROM {self.nexure_constants.sql_cases()} WHERE oAuthID = %s;',
            user.id
        )):
            embed.description = 'This user does not have any modlogs.'
            return await ctx.send(embed=embed)
        
        per_page = 5
        pages = []
        for i in range(0, len(result), per_page):
            chunk = result[i:i + per_page]
            description = ' '
            embed = (
                discord.Embed(title=' ', description=' ', colour=NexureConstants().colour())
                .set_author(name=f'{user.name}\'s Modlogs', icon_url=user.display_avatar)
                .set_footer(text=f'Page {i // per_page + 1}/{(len(result) - 1) // per_page + 1}')
            )

            for log in chunk:
                _, case_id, _, _, action, reason, case_id = log
                description += f'**Case ID:** `{case_id}` | {action}\n**Reason:** {reason}\n'
            
            embed.description = description.strip()
            pages.append(embed)
        
        view = Paginator(pages, ctx.user)
        await ctx.send(embed=pages[0], view=view)
        `

async def setup(bot):
    await bot.add_cog(Moderation(bot))

# Love, bread.