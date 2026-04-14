<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Tampilkan daftar artikel (skin guide).
     */
    public function index()
    {
        // Query dari database
        $articles = Article::published()->paginate(12);
        
        // Temporary dummy data untuk testing tanpa database
        if ($articles->isEmpty()) {
            $articles = [
                [
                    'id' => 1,
                    'title' => '10 Kesalahan Skincare yang Paling Umum',
                    'slug' => 'kesalahan-skincare-umum',
                    'category' => 'Tips & Trik',
                    'excerpt' => 'Pelajari kesalahan-kesalahan yang sering dilakukan dalam rutinitas skincare dan bagaimana cara mengatasinya untuk hasil maksimal.',
                    'author' => 'Dr. Siti Nurhaliza',
                    'date' => '15 Jan 2025',
                    'reading_time' => '8 min',
                    'thumbnail' => null,
                ],
                [
                    'id' => 2,
                    'title' => 'Rutinitas Skincare Pagi untuk Kulit Sehat',
                    'slug' => 'rutinitas-pagi-skincare',
                    'category' => 'Perawatan Dasar',
                    'excerpt' => 'Panduan lengkap langkah-demi-langkah untuk menciptakan rutinitas skincare pagi yang sempurna sesuai dengan tipe kulit Anda.',
                    'author' => 'Intan Beauty',
                    'date' => '10 Jan 2025',
                    'reading_time' => '6 min',
                    'thumbnail' => null,
                ],
                [
                    'id' => 3,
                    'title' => 'Manfaat Hyaluronic Acid untuk Kulit',
                    'slug' => 'hyaluronic-acid-benefits',
                    'category' => 'Ingredients',
                    'excerpt' => 'Temukan mengapa hyaluronic acid menjadi bahan ajaib dalam skincare modern dan bagaimana menggunakannya dengan efektif.',
                    'author' => 'Prof. Dr. Kusuma',
                    'date' => '05 Jan 2025',
                    'reading_time' => '7 min',
                    'thumbnail' => null,
                ],
                [
                    'id' => 4,
                    'title' => 'Cara Mengatasi Kulit Sensitif dan Reaktif',
                    'slug' => 'kulit-sensitif-solusi',
                    'category' => 'Kesehatan Kulit',
                    'excerpt' => 'Solusi komprehensif untuk mengatasi kulit sensitif termasuk pemilihan produk yang aman dan kebiasaan yang harus dihindari.',
                    'author' => 'Dr. Ria Wijaya',
                    'date' => '28 Dec 2024',
                    'reading_time' => '9 min',
                    'thumbnail' => null,
                ],
                [
                    'id' => 5,
                    'title' => 'SPF Sunscreen: Perlindungan Wajib Setiap Hari',
                    'slug' => 'sunscreen-protection',
                    'category' => 'Perawatan Dasar',
                    'excerpt' => 'Memahami pentingnya sunscreen dalam mencegah penuaan dini dan kerusakan kulit akibat sinar UV.',
                    'author' => 'Dermatology Clinic',
                    'date' => '20 Dec 2024',
                    'reading_time' => '5 min',
                    'thumbnail' => null,
                ],
                [
                    'id' => 6,
                    'title' => 'Exfoliation: Teknik dan Frekuensi yang Tepat',
                    'slug' => 'exfoliation-guide',
                    'category' => 'Tips & Trik',
                    'excerpt' => 'Pelajari cara melakukan exfoliation dengan benar tanpa merusak barrier kulit dan berapa kali sebulan yang ideal.',
                    'author' => 'Beauty Expert Sara',
                    'date' => '15 Dec 2024',
                    'reading_time' => '6 min',
                    'thumbnail' => null,
                ],
            ];
        }

        return view('pages.skin-guide', compact('articles'));
    }

    /**
     * Tampilkan detail artikel.
     */
    public function show($slug)
    {
        // Try to get from database first
        $article = Article::where('slug', $slug)->first();
        
        // All dummy articles for recommendations
        $allDummyArticles = [
            'kesalahan-skincare-umum' => [
                'id' => 1,
                'title' => '10 Kesalahan Skincare yang Paling Umum',
                'slug' => 'kesalahan-skincare-umum',
                'category' => 'Tips & Trik',
                'excerpt' => 'Pelajari kesalahan-kesalahan yang sering dilakukan dalam rutinitas skincare dan bagaimana cara mengatasinya untuk hasil maksimal.',
                'author' => 'Dr. Siti Nurhaliza',
                'date' => '15 Jan 2025',
                'reading_time' => '8 min',
                'thumbnail' => null,
                'content' => 'Kesalahan skincare adalah hal yang sangat umum dilakukan oleh mayoritas orang. Artikel ini membahas 10 kesalahan paling umum termasuk over-exfoliation, menggunakan produk yang salah, dan tidak konsisten dengan rutinitas. Dengan memahami kesalahan ini, Anda dapat mengoptimalkan hasil perawatan kulit Anda.',
            ],
            'rutinitas-pagi-skincare' => [
                'id' => 2,
                'title' => 'Rutinitas Skincare Pagi untuk Kulit Sehat',
                'slug' => 'rutinitas-pagi-skincare',
                'category' => 'Perawatan Dasar',
                'excerpt' => 'Panduan lengkap langkah-demi-langkah untuk menciptakan rutinitas skincare pagi yang sempurna sesuai dengan tipe kulit Anda.',
                'author' => 'Intan Beauty',
                'date' => '10 Jan 2025',
                'reading_time' => '6 min',
                'thumbnail' => null,
                'content' => 'Rutinitas skincare pagi adalah fondasi perawatan kulit yang baik. Mulai dari cleansing, toning, serum, moisturizer, hingga sunscreen. Setiap langkah dirancang untuk mempersiapkan kulit Anda menghadapi hari dengan perlindungan maksimal.',
            ],
            'hyaluronic-acid-benefits' => [
                'id' => 3,
                'title' => 'Manfaat Hyaluronic Acid untuk Kulit',
                'slug' => 'hyaluronic-acid-benefits',
                'category' => 'Ingredients',
                'excerpt' => 'Temukan mengapa hyaluronic acid menjadi bahan ajaib dalam skincare modern dan bagaimana menggunakannya dengan efektif.',
                'author' => 'Prof. Dr. Kusuma',
                'date' => '05 Jan 2025',
                'reading_time' => '7 min',
                'thumbnail' => null,
                'content' => 'Hyaluronic acid adalah humectant yang dapat menyimpan hingga 1000x beratnya dalam air. Ini membuat kulit tetap terhidrasi sepanjang hari. Pelajari cara menggunakan hyaluronic acid dalam skincare routine Anda.',
            ],
            'kulit-sensitif-solusi' => [
                'id' => 4,
                'title' => 'Cara Mengatasi Kulit Sensitif dan Reaktif',
                'slug' => 'kulit-sensitif-solusi',
                'category' => 'Kesehatan Kulit',
                'excerpt' => 'Solusi komprehensif untuk mengatasi kulit sensitif termasuk pemilihan produk yang aman dan kebiasaan yang harus dihindari.',
                'author' => 'Dr. Ria Wijaya',
                'date' => '28 Dec 2024',
                'reading_time' => '9 min',
                'thumbnail' => null,
                'content' => 'Kulit sensitif memerlukan perhatian khusus. Hindari bahan kimia keras, fragrances, dan produk yang tidak teruji secara dermatologi.',
            ],
            'sunscreen-protection' => [
                'id' => 5,
                'title' => 'SPF Sunscreen: Perlindungan Wajib Setiap Hari',
                'slug' => 'sunscreen-protection',
                'category' => 'Perawatan Dasar',
                'excerpt' => 'Memahami pentingnya sunscreen dalam mencegah penuaan dini dan kerusakan kulit akibat sinar UV.',
                'author' => 'Dermatology Clinic',
                'date' => '20 Dec 2024',
                'reading_time' => '5 min',
                'thumbnail' => null,
                'content' => 'Sunscreen bukan hanya untuk liburan pantai. Sinar UV hadir setiap hari dan dapat menyebabkan kerusakan kulit permanen.',
            ],
            'exfoliation-guide' => [
                'id' => 6,
                'title' => 'Exfoliation: Teknik dan Frekuensi yang Tepat',
                'slug' => 'exfoliation-guide',
                'category' => 'Tips & Trik',
                'excerpt' => 'Pelajari cara melakukan exfoliation dengan benar tanpa merusak barrier kulit dan berapa kali sebulan yang ideal.',
                'author' => 'Beauty Expert Sara',
                'date' => '15 Dec 2024',
                'reading_time' => '6 min',
                'thumbnail' => null,
                'content' => 'Exfoliation membantu menghilangkan sel kulit mati, tetapi harus dilakukan dengan hati-hati. Frekuensi ideal tergantung pada tipe kulit Anda.',
            ],
        ];
        
        // If not found in database, use dummy data
        if (!$article) {
            $article = $allDummyArticles[$slug] ?? null;
            
            if (!$article) {
                abort(404, 'Article not found');
            }
        }
        
        // Get recommended articles (exclude current article)
        $recommended = array_filter($allDummyArticles, function($item) use ($slug) {
            return $item['slug'] !== $slug;
        });
        
        // Shuffle and take 3 recommendations
        $recommended = array_slice(array_values($recommended), 0, 3);

        return view('pages.article-detail', compact('article', 'recommended'));
    }
}

