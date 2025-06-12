from functools import wraps as Wraps
from typing import Any, Callable, Dict, List, Optional


def Offload(function: Callable) -> Optional[Callable]:
    try:
        from dask import GLOBAL
    
    except ImportError:
        raise AttributeError("Dask has not been initialized through StartDask.")
    
    @Wraps(function)
    async def wrapper(*args: List[Any], **kwargs: Dict[ str, Any ]) -> Any:
        return await __import__("dask").GLOBAL.submit(function, *args, **kwargs)
        
    return wrapper