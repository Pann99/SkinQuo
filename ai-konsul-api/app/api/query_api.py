from fastapi import APIRouter
from app.services.query_pipeline_service import QueryPipelineService
from app.schemas.query_schema import QueryRequest

router = APIRouter()
pipeline = QueryPipelineService()

# Endpoint untuk memproses query
@router.post("/process-query")
def process_query(request: QueryRequest):
    result = pipeline.run(request.query)
    return result