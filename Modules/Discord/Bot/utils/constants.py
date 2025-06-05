import os
from dotenv import load_dotenv
import colorlog
import logging
import asyncio 

load_dotenv()

class NexureConstants():
    def __init__(self):
        self.Auth_list = []

    def environment(self) -> str:
        return os.getenv('ENVIRONMENT')

    def token(self) -> str:
        return os.getenv('TOKEN')

    def sentry_dsn(self) -> str: 
        return os.getenv('SENTRY_DSN')
    
    def sql_con(self) -> dict[any]:
        return {
            'HOST': os.getenv('SQL_HOST'),
            'PORT': int(os.getenv('SQL_PORT', '3306')),
            'USER': os.getenv('SQL_USER'),
            'PASSWORD': os.getenv('SQL_PASSWORD'),
            'DATABASE': os.getenv('SQL_DATABASE')
        }

    def sql_accounts(self) -> str:
        return os.getenv('SQL_ACCOUNTS')
    
    def sql_cases(self) -> str:
        return os.getenv('SQL_CASES')

    def sql_users(self) -> str:
        return os.getenv('SQL_USERS')
        
    def sql_blacklists(self) -> str:
        return os.getenv('SQL_BLACKLISTS')
        
    def sql_tickets(self) -> str:
        return os.getenv('SQL_TICKETS')

    def colour(self) -> int:
        return int(os.getenv('COLOUR'))

    def error_prefix(self) -> str:
        return os.getenv('ERROR_PREFIX') 

    def case_prefix(self) -> str:
        return os.getenv('CASE_PREFIX')
    
    def emojis(self) -> dict[str]:
        return {
            'success': os.getenv('SUCCESS_EMOJI'),
            'failed': os.getenv('FAILED_EMOJI'),
            'loading': os.getenv('LOADING_EMOJI')
        }

    def support_roles(self) -> list[int]:
        roles = [int(role.strip()) for role in os.getenv('SUPPORT_ROLES').split(',')]
        return roles
    
    def ticket_category(self) -> str:
        return os.getenv('TICKET_CATAGORY')

    def ticket_transcript(self) -> str:
        return os.getenv('TRANSCRIPT_CHANNEL')

    def welcome_channel(self) -> str:
        return os.getenv('WELCOME_CHANNEL')
    
    def welcome_role(self) -> int:
        return os.getenv('MEMBER_ROLE')


log = colorlog.ColoredFormatter(
    "%(blue)s[%(asctime)s]%(reset)s - %(filename)s - %(log_color)s%(levelname)s%(reset)s - %(message)s",
    datefmt='%Y-%m-%d %H:%M:%S',
    log_colors={
        'DEBUG': 'cyan',
        'INFO': 'green',
        'WARNING': 'yellow',
        'ERROR': 'red',
        'CRITICAL': 'bold_red',
    }
)

handler = logging.StreamHandler()
handler.setFormatter(log)

logger = logging.getLogger(__name__)
logger.addHandler(handler)
logger.setLevel(logging.DEBUG)

# Love, bread.