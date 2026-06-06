# Gunakan base image Python slim
FROM python:3.10-slim

# Install tools yang dibutuhkan untuk compile library seperti pandas/rapidfuzz
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /code

# Copy dan install requirements
COPY ./requirements.txt /code/requirements.txt
RUN pip install --no-cache-dir --upgrade -r /code/requirements.txt

# Copy seluruh file project
COPY . /code

# Ekspos port default Hugging Face
EXPOSE 7860

# Jalankan Uvicorn
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "7860"]