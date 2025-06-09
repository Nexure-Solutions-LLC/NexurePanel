from __future__ import annotations
from bot.utils.drivers.network._essentials import HEADERS

from cryptography.fernet import Fernet
import requests


class Keychain:
    def __init__(self: Keychain):
        self.__FERNET_KEY = requests.get("http://localhost:1515").text
        self.__fernet = Fernet(self.FERNET_KEY)

        for key, value in HEADERS.Authorization.items():
            setattr(self, key, self.__fernet.decrypt(value.encode()).decode())