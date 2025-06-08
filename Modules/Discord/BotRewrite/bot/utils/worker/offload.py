from functools import wraps as Wraps
from typing import Any, Callable, Dict, List, Optional


def Offload(function: Callable) -> Optional[Callable]:
    try:
        from dask.distributed import GLOBAL_DASK
    
    except ImportError:
        raise AttributeError("Dask has not been initialized through StartDask.")
    
    @Wraps(function)
    async def wrapper(*args: List[Any], **kwargs: Dict[ str, Any ]) -> Any:
        return await GLOBAL_DASK.submit(function, *args, **kwargs)
        
    return wrapper