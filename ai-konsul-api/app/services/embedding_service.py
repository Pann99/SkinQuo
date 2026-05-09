from sentence_transformers import SentenceTransformer

class EmbeddingService:
    def __init__(self):
        # Using a multilingual model 
        self.model = SentenceTransformer('paraphrase-multilingual-MiniLM-L12-v2')
        
    def encode(self, text: str):
        return self.model.encode(text, normalize_embeddings=True)

    def encode_words(self, words: list):
        return self.model.encode(words, normalize_embeddings=True)