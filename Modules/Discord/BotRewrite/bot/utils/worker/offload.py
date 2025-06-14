# Author: Treyten
from functools import wraps
from typing import Any, Callable, Optional


def Offload(function: Callable) -> Optional[Callable]:
    try:
        from dask import GLOBAL
    except ImportError:
        raise AttributeError("Dask has not been initialized through StartDask.")

    @wraps(function)
    async def wrapper(*args: Any, **kwargs: Any) -> Any:
        return await GLOBAL.submit(function, *args, **kwargs)

    return wrapper