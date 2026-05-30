from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

from app.api.query_api import router as query_router

app = FastAPI()

# ==============================
# CORS
# ==============================

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # sementara bebas dulu untuk testing
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# ==============================
# ROUTER
# ==============================

app.include_router(
    query_router,
    prefix="/api",
    tags=["Recommendation"]
)