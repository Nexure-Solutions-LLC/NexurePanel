# Author: Treyten
from dask.distributed import Client, LocalCluster


async def start_dask() -> Client:
    __import__("dask.distributed").GLOBAL_DASK = await Client(
        LocalCluster(
            dashboard_address=":8787",
            asynchronous=True,
            processes=True,
            threads_per_worker=4,
            n_workers=4,
            scheduler_port=0,
            silence_logs="error",
            worker_memory_target=0.8,
            worker_memory_spill=0.9
        ),
        direct_to_workers=True,
        asynchronous=True,
        name="Dzi",
    )
    
    return __import__("dask.distributed").GLOBAL_DASK


async def stop_dask() -> None | Exception:
    try:
        from dask.distributed import GLOBAL_DASK
        
    except ImportError:
        raise AttributeError("Dask has not been initialized through StartDask.")
    
    await __import__("dask.distributed").GLOBAL_DASK.close()
    del __import__("dask.distributed").GLOBAL_DASK