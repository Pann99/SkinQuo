import sys
from app.services.query_pipeline_service import QueryPipelineService
from app.services.keyword_manager import keyword_manager

GOLDEN_DATASET = [
    {
        "query": "muka aku oily banget dan berjerawat, butuh rekomendasi toner",
        "expected": {
            "Jenis Produk": ["Toner"],
            "Jenis/Tipe Kulit": ["Kulit Berminyak"],
            "Keluhan Kulit": ["Jerawat"]
        }
    },
    {
        "query": "kandungan centella asiatica cocok ga buat kulit sensitif?",
        "expected": {
            "Jenis Produk": [],
            "Kandungan Aktif": ["Centella Asiatica"],
            "Jenis/Tipe Kulit": ["Kulit Sensitif"],
            "Keluhan Kulit": []
        }
    },
    {
        "query": "hilangkan flek hitam di pipi pake serum apa ya",
        "expected": {
            "Jenis Produk": ["Serum"],
            "Kandungan Aktif": [],
            "Jenis/Tipe Kulit": [],
            "Keluhan Kulit": ["Flek Hitam"]
        }
    }
]

def run_nlp_evaluation():
    print("=" * 70)
    print("             STARTING KNOWLEDGE ENGINE NLP EVALUATION           ")
    print("=" * 70)
    
    if not keyword_manager.CANONICAL_MAP:
        keyword_manager.load_keywords_from_db()
        
    pipeline = QueryPipelineService()
    total_tp, total_fp, total_fn = 0, 0, 0
    
    for idx, test_case in enumerate(GOLDEN_DATASET, 1):
        print(f"\n[Test #{idx}] Input Query: \"{test_case['query']}\"")
        try:
            res = pipeline.run(test_case["query"])
            actual_display = res.get("display_explainability", {})
        except Exception as e:
            print(f"  ✘ Gagal memproses query: {str(e)}")
            continue
            
        for category, expected_list in test_case["expected"].items():
            actual_list = actual_display.get(category, [])
            for item in actual_list:
                if item in expected_list:
                    total_tp += 1
                    print(f"  ✔ [TP] Kategori '{category}': '{item}' BERHASIL.")
                else:
                    total_fp += 1
                    print(f"  ✘ [FP] Kategori '{category}': '{item}' SALAH.")
            for item in expected_list:
                if item not in actual_list:
                    total_fn += 1
                    print(f"  ✘ [FN] Kategori '{category}': '{item}' GAGAL ditangkap.")

    precision = total_tp / (total_tp + total_fp) if (total_tp + total_fp) > 0 else 0.0
    recall    = total_tp / (total_tp + total_fn) if (total_tp + total_fn) > 0 else 0.0
    f1_score  = (2 * precision * recall) / (precision + recall) if (precision + recall) > 0 else 0.0

    print("\n" + "=" * 70)
    print("                      FINAL METRIC CONFUSION MATRIX             ")
    print("=" * 70)
    print(f"  PRECISION ACCURACY        : {precision * 100:.2f}%")
    print(f"  RECALL (SENSITIVITY)       : {recall * 100:.2f}%")
    print(f"  F1-SCORE EVALUATION        : {f1_score * 100:.2f}%")
    print("=" * 70 + "\n")

if __name__ == "__main__":
    run_nlp_evaluation()