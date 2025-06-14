# Author: Treyten
from munch import Munch
from os import environ as ENV
from pydantic import BaseModel, create_model as _create_model
from typing import Any, Dict, List
import orjson


HEADERS = Munch(
    User_Agent = "Application",
    Authorization = Munch(
        Discord = ENV.get("DISCORD_AUTH_TOKEN"),
    )
)


PROXIES = (
    "http://yjxaasab-rotate:n6hhdcqoywub@p.webshare.io:80/",
    "http://zmdluuhm-rotate:i65lbvbkobgy@p.webshare.io:80/",
    "http://quyvkwot-rotate:9xy23fddg43e@p.webshare.io:80/",
    "http://qickjcmv-rotate:q1kc5yjmse27@p.webshare.io:80/",
    "http://dcuhusch-rotate:hxoou3p9mve9@p.webshare.io:80/"
)


def normalize_dict(dictionary: Dict[ str, Any ] | List[Dict]) -> Dict:
    if isinstance(dictionary, dict):
        dictionary = [dictionary]
    
    for dictionary_ in dictionary:
        new = dict()
        for key, value in tuple(dictionary_.items()):
            new[key.lower()] = dictionary_.pop(key)
        
        dictionary_.update(new)
        
    del new
    return dictionary


def create_model(data: Dict | List) -> BaseModel | List[BaseModel]:
    if not isinstance(data, (dict, list)):
        raise TypeError(f"Unexpected type: {type(data)}")
    
    def _clean_data(data: Dict) -> Dict:
        return orjson.loads(orjson.dumps(data).replace(b"#text", b"text"))

    def _generate_model_fields(data: Dict) -> Dict:
        fields = dict()
        
        for key, value in data.items():
            if isinstance(value, dict):
                fields[key] = (create_model(value), ...)
                
            elif isinstance(value, list):
                if value and isinstance(value[0], dict):
                    fields[key] = (List[create_model(value[0])], ...)
                
                else:
                    fields[key] = (List, ...)
                    
            else:
                fields[key] = (type(value), ...)
                
        return fields
    
    if "data" in data:
        data = data["data"]
        
    if isinstance(data, list):
        normalize_dict(data)
        return list(map(lambda item: create_model(item), data))
        
    cleaned_data = _clean_data(data)
    model_fields = _generate_model_fields(cleaned_data)
    
    return _create_model("Response", **model_fields)(**cleaned_data)