# 📝 ACTIONABLE NEXT STEPS - Backend Implementation Roadmap

**Status:** Phase 2 Ready  
**Scope:** Backend Implementation untuk mencapai 100% completion  
**Target Timeline:** 3-4 minggu (1 developer full-time)

---

## 🎯 Phase 2 Execution Plan

### MINGGU 1: Foundation (Database & Models)

#### ✅ HARI 1-2: CREATE ELOQUENT MODELS

**Task 1.1: User & User Profile Models**
```bash
# File: app/Models/User.php
# File: app/Models/UserProfile.php

# Relationships needed:
- User hasOne UserProfile
- User hasMany Articles (created_by)
- User hasMany Consultations
- User hasMany UserFeedbacks
- User hasMany AdminLogs (user_id)
```

**Checklist:**
- [ ] User model dengan fields: id, name, email, password, role, is_active, etc
- [ ] UserProfile model dengan relations
- [ ] Define all accessors/mutators
- [ ] Add $fillable & $hidden arrays
- [ ] Add timestamp casting
- [ ] Method untuk role checking: isAdmin(), isUser()

**Files to create:**
- `app/Models/User.php`
- `app/Models/UserProfile.php`

**Time:** 2 hours

---

**Task 1.2: Article & Article Tag Models**
```bash
# File: app/Models/Article.php
# File: app/Models/ArticleTag.php

# Relationships:
- Article belongsTo User (created_by)
- Article hasMany ArticleTags
- ArticleTag belongsTo Article
```

**Checklist:**
- [ ] Article model dengan scope: published(), latest()
- [ ] ArticleTag model untuk tagging system
- [ ] Article:slug unique constraint
- [ ] published() scope untuk query published articles
- [ ] View count tracking
- [ ] SEO meta fields

**Files to create:**
- `app/Models/Article.php`
- `app/Models/ArticleTag.php`

**Time:** 1.5 hours

---

**Task 1.3: Product & Brand Models**
```bash
# File: app/Models/Product.php
# File: app/Models/ProductBrand.php
# File: app/Models/Brand.php

# Relationships:
- Product belongsToMany Brands (via ProductBrand)
- Product hasMany UserFeedbacks
- Brand hasMany Products
```

**Checklist:**
- [ ] Product model dengan fields & scopes
- [ ] ProductBrand pivot model dengan relationships
- [ ] Brand model
- [ ] Product:slug unique
- [ ] Scope untuk filtering: bySkinType(), byBrand(), byPriceRange()
- [ ] Best seller indicator

**Files to create:**
- `app/Models/Product.php`
- `app/Models/ProductBrand.php`
- `app/Models/Brand.php`

**Time:** 1.5 hours

---

**Task 1.4: Consultation & Related Models**
```bash
# Files:
# - app/Models/Consultation.php
# - app/Models/ConsultationResult.php
# - app/Models/SkinConditionAnalysis.php
# - app/Models/ProductRec.php
# - app/Models/SkinType.php

# Relationships:
- Consultation belongsTo User
- Consultation hasMany ConsultationResults
- ConsultationResult hasMany SkinConditionAnalysis
- Consultation hasMany ProductRecs
- Product belongsTo SkinType (implicit)
```

**Checklist:**
- [ ] Consultation model dengan status enum, JSON field handling
- [ ] ConsultationResult model
- [ ] SkinConditionAnalysis model untuk detailed analysis
- [ ] ProductRec model untuk recommendations
- [ ] SkinType model (master data)
- [ ] Casting untuk JSON fields (tags, traits, preferences)

**Files to create:**
- `app/Models/Consultation.php`
- `app/Models/ConsultationResult.php`
- `app/Models/SkinConditionAnalysis.php`
- `app/Models/ProductRec.php`
- `app/Models/SkinType.php`

**Time:** 2 hours

---

**Task 1.5: Feedback & Admin Log Models**
```bash
# Files:
# - app/Models/UserFeedback.php
# - app/Models/AdminLog.php

# Relationships:
- UserFeedback belongsTo User
- UserFeedback belongsTo Product
- AdminLog belongsTo User
```

**Checklist:**
- [ ] UserFeedback model dengan rating, comment
- [ ] AdminLog model untuk audit trail
- [ ] Status enum untuk feedback (pending, approved, rejected)
- [ ] Scope untuk filtering

**Files to create:**
- `app/Models/UserFeedback.php`
- `app/Models/AdminLog.php`

**Time:** 1 hour

---

**✅ HARI 3: CREATE DATABASE MIGRATIONS**

**Task 2.1: Run existing migrations**
```bash
php artisan migrate
```

**Task 2.2: Create new migration files** (13 migrations)

**Files to create** (gunakan template dari DATABASE_DESIGN.md):

```bash
# Core tables
database/migrations/2025_04_14_000003_create_user_profiles_table.php
database/migrations/2025_04_14_000004_create_skin_types_table.php
database/migrations/2025_04_14_000005_create_articles_table.php
database/migrations/2025_04_14_000006_create_article_tags_table.php
database/migrations/2025_04_14_000007_create_brands_table.php
database/migrations/2025_04_14_000008_create_products_table.php
database/migrations/2025_04_14_000009_create_product_brands_table.php
database/migrations/2025_04_14_000010_create_consultations_table.php
database/migrations/2025_04_14_000011_create_consultation_results_table.php
database/migrations/2025_04_14_000012_create_skin_condition_analysis_table.php
database/migrations/2025_04_14_000013_create_product_recs_table.php
database/migrations/2025_04_14_000014_create_user_feedbacks_table.php
database/migrations/2025_04_14_000015_create_admin_logs_table.php
```

**Checklist per migration:**
- [ ] Primary keys (UUID)
- [ ] Foreign keys dengan cascading
- [ ] Unique constraints (slug, email, etc)
- [ ] Indexes untuk performance
- [ ] ENUM types (PostgreSQL specific)
- [ ] Timestamps & soft deletes
- [ ] JSON fields dengan casting

**Sample structure:**
```php
public function up(): void {
    Schema::create('products', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description');
        $table->decimal('price', 10, 2);
        // ... more fields
        $table->timestamps();
        $table->softDeletes();
        
        // Indexes
        $table->index('slug');
        $table->index('category');
        // ...
    });
}
```

**Time:** 4 hours

---

**✅ HARI 4-5: RUN MIGRATIONS & SEED DATA**

**Task 3.1: Execute migrations**
```bash
php artisan migrate
```

**Task 3.2: Create Seeders**
```bash
# Create base seeder untuk test data
database/seeders/SkinTypeSeeder.php    (5 skin types)
database/seeders/BrandSeeder.php       (10-15 brands)
database/seeders/ProductSeeder.php     (50 products)
database/seeders/ArticleSeeder.php     (20 articles)
database/seeders/UserSeeder.php        (5-10 users)
```

**Checklist:**
- [ ] SkinTypeSeeder: Normal, Oily, Dry, Combination, Sensitive
- [ ] BrandSeeder: Real skincare brands
- [ ] ProductSeeder: Products dengan brand relations
- [ ] ArticleSeeder: Articles dengan tags
- [ ] UserSeeder: Test users (1 admin, 4 regular users)
- [ ] Run all seeders: `php artisan db:seed`

**Files to create:**
- `database/seeders/SkinTypeSeeder.php`
- `database/seeders/BrandSeeder.php`
- `database/seeders/ProductSeeder.php`
- `database/seeders/ArticleSeeder.php`
- `database/seeders/UserSeeder.php`

**Time:** 3 hours

---

### MINGGU 2: Controller Implementation

#### ✅ HARI 6-7: COMPLETE PRODUCT CONTROLLER

**Task 4.1: ProductController::index() dengan filter**
```php
// routes/web.php sudah ada
// app/Http/Controllers/ProductController.php

public function index(Request $request)
{
    // Query dengan filters
    $products = Product::query()
        ->when($request->skin_type, fn($q) => $q->where('skin_type_id', $request->skin_type))
        ->when($request->brand, fn($q) => $q->whereHas('brands', ...))
        ->when($request->price_min, fn($q) => $q->wherePriceRange($request->price_min, $request->price_max))
        ->latest()
        ->paginate(12);
    
    return view('pages.catalog', compact('products'));
}
```

**Checklist:**
- [ ] Implement filters: skin_type, brand, price_min, price_max
- [ ] Implement search query parameter
- [ ] Sort options: newest, price (asc/desc), popular
- [ ] Proper scoping dengan query builder
- [ ] Pagination dengan filter persistence

**Time:** 1.5 hours

---

**Task 4.2: ProductController::show() & related products**
```php
public function show($slug, Request $request)
{
    $product = Product::where('slug', $slug)->firstOrFail();
    $relatedProducts = Product::where('category', $product->category)
        ->where('id', '!=', $product->id)
        ->take(4)
        ->get();
    
    return view('pages.product-detail', compact('product', 'relatedProducts'));
}
```

**Checklist:**
- [ ] Load product with brands relationship
- [ ] Load related products (same category)
- [ ] Load feedbacks/reviews
- [ ] Handle 404 if not found
- [ ] Increment view count (optional)

**Time:** 1 hour

---

#### ✅ HARI 8: COMPLETE ARTICLE CONTROLLER

**Task 5.1: ArticleController improvements**
```php
// Add methods for article tags & related articles
public function index(Request $request)
{
    $articles = Article::published()
        ->when($request->category, fn($q) => $q->where('category', $request->category))
        ->when($request->tag, fn($q) => $q->whereHas('tags', fn($q) => $q->where('tag_name', $request->tag)))
        ->latest('published_at')
        ->paginate(12);
    
    $categories = Article::distinct()->pluck('category');
    return view('pages.skin-guide', compact('articles', 'categories'));
}

public function show($slug)
{
    $article = Article::where('slug', $slug)
        ->wherePublished()
        ->firstOrFail();
    
    // Increment views
    $article->increment('view_count');
    
    // Get related articles by tags
    $relatedArticles = Article::whereHas('tags', function($q) use ($article) {
        $q->whereIn('tag_name', $article->tags->pluck('tag_name'));
    })
    ->where('id', '!=', $article->id)
    ->take(4)
    ->get();
    
    return view('pages.article-detail', compact('article', 'relatedArticles'));
}
```

**Checklist:**
- [ ] Category filtering di index
- [ ] Tag filtering di index
- [ ] Query related articles in show
- [ ] Eager load tags & author
- [ ] Increment view count
- [ ] Handle soft deletes

**Time:** 1.5 hours

---

#### ✅ HARI 9-10: CONSULTATION CONTROLLER IMPROVEMENTS

**Task 6.1: Improve ConsultationController::analyze()**
```php
// Enhance AI inference logic
private function inferTraitsFromStory($story, $tags)
{
    // Better keyword matching (not just basic)
    $storyLower = strtolower($story);
    $detectedTraits = [];
    
    // Define trait keywords
    $traitKeywords = [
        'oily' => ['oily', 'minyak', 'sebum'],
        'dry' => ['dry', 'kering', 'tight', 'flaking'],
        'sensitive' => ['sensitive', 'sensitif', 'irritated', 'iritasi'],
        'acne' => ['acne', 'jerawat', 'breakout', 'pimple'],
        'wrinkles' => ['wrinkle', 'fine line', 'aging', 'penuaan'],
        'hyperpigmentation' => ['dark spot', 'spot', 'pigmentation', 'noda'],
    ];
    
    // Match keywords in story
    foreach ($traitKeywords as $trait => $keywords) {
        foreach ($keywords as $keyword) {
            if (str_contains($storyLower, $keyword)) {
                $detectedTraits[] = $trait;
                break;
            }
        }
    }
    
    // Combine with user-selected tags
    $allTraits = array_unique(array_merge($detectedTraits, $tags));
    
    return $allTraits;
}
```

**Checklist:**
- [ ] Better keyword dictionary untuk Indonesian + English
- [ ] Semantic matching (tidak hanya exact string)
- [ ] Weighted scoring untuk common traits
- [ ] ML-ready structure (dapat upgrade ke real ML later)

**Time:** 2 hours

---

**Task 6.2: Implement consultation product recommendations**
```php
// After consultation processing
private function generateProductRecommendations($consultation)
{
    // Based on detected traits & concerns
    $traits = $consultation->detected_traits;
    $recommendations = Product::query()
        ->whereHas('skinType', fn($q) => $q->whereIn('name', $traits))
        ->orWhere(function($q) use ($consultation) {
            $q->where('category', $this->mapConcernToCategory($consultation->concern_1))
                ->orWhere('category', $this->mapConcernToCategory($consultation->concern_2));
        })
        ->take(5)
        ->get();
    
    // Store recommendations
    foreach ($recommendations as $product) {
        ProductRec::create([
            'consultation_id' => $consultation->id,
            'product_id' => $product->id,
            'reason' => 'Based on skin analysis',
            'priority' => 1,
        ]);
    }
    
    return $recommendations;
}
```

**Checklist:**
- [ ] Map concerns to product categories
- [ ] Query products by skin type
- [ ] Query products by category
- [ ] Store recommendations di product_recs table
- [ ] Support priority ordering

**Time:** 2 hours

---

**Task 6.3: ConsultationController::result()**
```php
public function result($id)
{
    $consultation = Consultation::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();
    
    $results = ConsultationResult::where('consultation_id', $id)->first();
    $recommendations = ProductRec::where('consultation_id', $id)
        ->with('product')
        ->get();
    $analysis = SkinConditionAnalysis::where('consultation_id', $id)->get();
    
    return view('pages.consultation-result', compact(
        'consultation',
        'results',
        'recommendations',
        'analysis'
    ));
}
```

**Checklist:**
- [ ] Authorization check (user bisa access own consultation only)
- [ ] Eager load related data
- [ ] Handle 404 if not found
- [ ] Pass all necessary data to view

**Time:** 1 hour

---

#### ✅ HARI 11: PROFILE CONTROLLER IMPROVEMENTS

**Task 7.1: Add consultation history**
```php
public function show()
{
    $user = Auth::user();
    $consultations = Consultation::where('user_id', $user->id)
        ->with('results')
        ->latest()
        ->paginate(10);
    
    $skinType = $user->profile?->skin_type;
    
    return view('pages.profile', compact('user', 'consultations', 'skinType'));
}
```

**Checklist:**
- [ ] Load user with profile relation
- [ ] Load consultations dengan results
- [ ] Load skin type
- [ ] Order by latest first
- [ ] Paginate results

**Time:** 1 hour

---

**Task 7.2: Update skin type selection**
```php
public function update(Request $request)
{
    // ... existing validation
    
    // Update skin type jika submitted
    if ($request->has('skin_type_id')) {
        $user->profile->update([
            'skin_type_id' => $request->skin_type_id
        ]);
    }
    
    // ... rest of update logic
}
```

**Checklist:**
- [ ] Validate skin_type_id exists di skin_types table
- [ ] Update user_profiles.skin_type_id
- [ ] Fallback if profile doesn't exist: create it

**Time:** 0.5 hour

---

#### ✅ HARI 12: FEEDBACK CONTROLLER

**Task 8.1: Create FeedbackController**
```bash
# File: app/Http/Controllers/FeedbackController.php

class FeedbackController
{
    public function store(Request $request, $productId)
    {
        // Validate & store user feedback
    }
    
    public function index($productId)
    {
        // Get approved feedbacks untuk product
    }
}
```

**Checklist:**
- [ ] Create controller dengan store & index methods
- [ ] Validate rating (1-5), comment required
- [ ] Create UserFeedback record
- [ ] Return to product-detail page
- [ ] Fetch & display feedbacks

**Time:** 1.5 hours

---

### MINGGU 3: Testing & Polish

#### ✅ HARI 13-14: TESTING & BUG FIXING

**Task 9.1: Unit Testing**
- [ ] Test models & relationships
- [ ] Test controller methods
- [ ] Test validation

**Task 9.2: Feature Testing**
- [ ] Test authentication flow
- [ ] Test product filtering
- [ ] Test consultation flow
- [ ] Test profile update

**Task 9.3: Frontend Testing**
- [ ] Test responsive design
- [ ] Test form submissions
- [ ] Test error handling

**Time:** 4 hours

---

#### ✅ HARI 15: OPTIMIZATION & DEPLOYMENT

**Task 10.1: Performance Optimization**
- [ ] Eager loading to prevent N+1
- [ ] Caching frequently accessed data
- [ ] Query optimization

**Task 10.2: Deployment Prep**
- [ ] Environment variables setup
- [ ] Database backup strategy
- [ ] Error monitoring (Sentry)
- [ ] Logging setup

**Time:** 2 hours

---

---

## 📋 DETAILED TASK BREAKDOWN

### Summary Table:

| Week | Day | Task | Priority | Est. Hours | Status |
|------|-----|------|----------|-----------|--------|
| W1 | 1-2 | Create 14 Models | P1 | 8 | 🔴 TODO |
| W1 | 3 | Migrations | P1 | 4 | 🔴 TODO |
| W1 | 4-5 | Seeders & Data | P2 | 3 | 🔴 TODO |
| W2 | 6-7 | ProductController | P1 | 2.5 | 🔴 TODO |
| W2 | 8 | ArticleController | P1 | 3 | 🔴 TODO |
| W2 | 9-10 | ConsultationController | P1 | 6 | 🔴 TODO |
| W2 | 11 | ProfileController | P1 | 1.5 | 🔴 TODO |
| W2 | 12 | FeedbackController | P2 | 1.5 | 🔴 TODO |
| W3 | 13-14 | Testing | P2 | 4 | 🔴 TODO |
| W3 | 15 | Optimization | P2 | 2 | 🔴 TODO |

**Total: ~36 hours** = ~5 business days per week × 3 weeks

---

---

## 🔧 IMPLEMENTATION GUIDELINES

### Best Practices Checklist:

- [ ] Use type hints untuk semua parameters & return types
- [ ] Add PHPDoc comments untuk setiap method
- [ ] Follow Laravel naming conventions
- [ ] Use scopes untuk complex queries
- [ ] Eager load relationships (->with())
- [ ] Validate all inputs
- [ ] Use authorization middleware
- [ ] Log important actions
- [ ] Handle exceptions gracefully
- [ ] Write tests untuk critical paths
- [ ] Comment complex logic
- [ ] Use meaningful variable names
- [ ] Keep methods small & focused (SRP)
- [ ] DRY: Don't Repeat Yourself
- [ ] SOLID principles

### Code Quality Standards:

```php
// ✅ GOOD
public function filter(Request $request): View
{
    $products = Product::query()
        ->when($request->category, fn($q) => $q->byCategory($request->category))
        ->paginate(12);
    
    return view('pages.catalog', compact('products'));
}

// ❌ BAD
public function filter($request)
{
    $products = DB::table('products')
        ->where('category', $request->get('category'))
        ->paginate(12);
    
    return view('pages.catalog', ['products' => $products]);
}
```

---

---

## 📌 SUCCESS CRITERIA

Project considered **COMPLETE** ketika:

✅ **Backend:**
- [ ] Semua 14 models created dengan relationships
- [ ] 13 migrations created & running
- [ ] All controllers fully implemented
- [ ] All queries optimized (no N+1)
- [ ] Proper error handling & validation
- [ ] Tests passing (80%+ coverage)

✅ **Database:**
- [ ] Database connected to PostgreSQL/Supabase
- [ ] All tables created dengan indexes
- [ ] Seed data loaded (test products, articles, users)
- [ ] Relationships working properly
- [ ] Soft deletes implemented

✅ **Frontend:**
- [ ] All pages receiving data from backend
- [ ] Forms working with validation feedback
- [ ] Search & filters working
- [ ] Pagination working
- [ ] Mobile responsive
- [ ] No console errors

✅ **User Flows:**
- [ ] Complete auth flow: register → login → profile → logout
- [ ] Complete consultation flow: form → analyze → result
- [ ] Complete shopping flow: browse → filter → view detail → review
- [ ] Complete skin guide flow: list → filter → read → related

---

---

## 🚀 GO LIVE CHECKLIST

Sebelum production deployment:

- [ ] Database migration production-ready
- [ ] All tests passing
- [ ] Code review completed
- [ ] Performance benchmarked
- [ ] Security audit passed
- [ ] Error monitoring setup (Sentry)
- [ ] Backup strategy in place
- [ ] CDN for static assets configured
- [ ] Email notifications tested
- [ ] Admin panel tested
- [ ] Documentation updated
- [ ] Team training completed

---

**Ready to start?** Begin with Week 1, Task 1.1 - Create User & UserProfile Models!

