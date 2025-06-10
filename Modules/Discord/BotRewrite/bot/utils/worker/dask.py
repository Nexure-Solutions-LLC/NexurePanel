# Author: Treyten
from dask.distributed import Client, LocalCluster


async def start_dask() -> Client:
    __import__("dask.distributed").GLOBAL = await Client(
        LocalCluster(
            dashboard_address=":8787",
            asynchronous=True,
            processes=True,
            threads_per_worker=4,
            n_workers=4,
            scheduler_port=0,
            silence_logs="ERROR",
        ),
        direct_to_workers=True,
        asynchronous=True,
        name="Nexure",
    )
    
    return __import__("dask.distributed").GLOBAL


async def stop_dask() -> None | Exception:
    try:
        from dask.distributed import GLOBAL
        
    except ImportError:
        raise AttributeError("Dask has not been initialized through StartDask.")
    
    await __import__("dask.distributed").GLOBAL.close()
    del __import__("dask.distributed").GLOBAL