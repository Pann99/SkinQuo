from fastapi import FastAPI
from app.api.query_api import router as query_router

app = FastAPI()

app.include_router(query_router)