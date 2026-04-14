# 🛡️ SkinQuo Admin Framework - Developer Guide

**Version:** 1.0  
**Last Updated:** April 14, 2026  
**Status:** 🟢 Ready for Development  
**Author:** SkinQuo Tech Lead

---

## 📌 Overview

Admin panel SkinQuo dibangun dengan struktur terpisah dari user panel untuk menjaga kerapian kode dan maintainability. Semua file Admin ditempatkan dalam folder khusus dengan naming convention yang konsisten dan strict.

**Penting:** Panduan ini WAJIB diikuti oleh semua developer yang bekerja di bagian Frontend Admin. Ketidaksesuaian struktur akan menyebabkan masalah integrasi dengan backend.

---

## 📁 Struktur Folder Admin

```
project/
├── app/Http/Controllers/
│   ├── AdminController.php                     ← Dashboard & main operations
│   ├── AdminProductController.php              ← CRUD Produk
│   ├── AdminSkinGuideController.php            ← CRUD Artikel
│   └── AdminFeedbackController.php             ← Monitor Feedback
├── app/Http/Middleware/
│   └── AdminMiddleware.php                     ← Authorization (admin role check)
├── resources/views/
│   ├── admin/                                  ← 🏠 RUMAH ADMIN PANEL
│   │   ├── dashboard.blade.php                 ← Dashboard utama
│   │   ├── products/
│   │   │   ├── index.blade.php                 ← List produk
│   │   │   ├── create.blade.php                ← Form buat produk
│   │   │   ├── edit.blade.php                  ← Form edit produk
│   │   │   └── show.blade.php                  ← Detail produk
│   │   ├── skin-guide/
│   │   │   ├── index.blade.php                 ← List artikel
│   │   │   ├── create.blade.php                ← Form buat artikel
│   │   │   └── edit.blade.php                  ← Form edit artikel
│   │   └── feedback/
│   │       └── monitor.blade.php               ← Dashboard monitor feedback
│   └── layouts/admin/
│       └── admin.blade.php                     ← Layout Admin (dengan sidebar)
└── routes/web.php                              ← Admin route definitions

```

---

## 🎨 Standard Penamaan & Konvensi

### 1️⃣ Controller Naming Convention

**Format:** `Admin{Feature}Controller`

| Feature | Controller Name | File Location |
|---------|-----------------|----------------|
| Dashboard | `AdminController` | `app/Http/Controllers/AdminController.php` |
| Products | `AdminProductController` | `app/Http/Controllers/AdminProductController.php` |
| Skin Guide | `AdminSkinGuideController` | `app/Http/Controllers/AdminSkinGuideController.php` |
| Feedback | `AdminFeedbackController` | `app/Http/Controllers/AdminFeedbackController.php` |

**❌ JANGAN:**
```php
ProductController           // ← Ini untuk user panel, bukan admin
AdminProduct               // ← Incomplete name
AdminProductManagement     // ← Terlalu verbose
```

**✅ BENAR:**
```php
AdminProductController     // ← Clear, consistent dengan konvensi
```

---

### 2️⃣ View/Blade File Naming

**Format:** `kebab-case` di dalam folder `resources/views/admin/`

| Fitur | File | Path |
|-------|------|------|
| Dashboard | `dashboard.blade.php` | `resources/views/admin/dashboard.blade.php` |
| Product List | `index.blade.php` | `resources/views/admin/products/index.blade.php` |
| Create Product | `create.blade.php` | `resources/views/admin/products/create.blade.php` |
| Edit Product | `edit.blade.php` | `resources/views/admin/products/edit.blade.php` |
| Skin Guide List | `index.blade.php` | `resources/views/admin/skin-guide/index.blade.php` |
| Create Article | `create.blade.php` | `resources/views/admin/skin-guide/create.blade.php` |
| Monitor Feedback | `monitor.blade.php` | `resources/views/admin/feedback/monitor.blade.php` |

**❌ JANGAN:**
```blade
manage-products.blade.php       ← Terlalu verbose, gunakan index
product-management.blade.php    ← Terlalu verbose
admin_products_list.blade.php   ← Gunakan underscore hanya di namespace, bukan file
```

**✅ BENAR:**
```blade
index.blade.php                 ← Standard Laravel convention
create.blade.php                ← Clear intent
monitor.blade.php               ← Specific action name
```

---

### 3️⃣ Route Naming Convention

**Format:** `admin.{feature}.{action}`

Semua route di admin panel mengikuti naming convention yang strict untuk consistency.

#### Route List Lengkap:

```php
// Dashboard
route('admin.dashboard')          // GET /admin/dashboard

// Products CRUD
route('admin.products.index')     // GET /admin/products
route('admin.products.create')    // GET /admin/products/create
route('admin.products.store')     // POST /admin/products
route('admin.products.show', $id) // GET /admin/products/{id}
route('admin.products.edit', $id) // GET /admin/products/{id}/edit
route('admin.products.update', $id) // PUT /admin/products/{id}
route('admin.products.destroy', $id) // DELETE /admin/products/{id}

// Skin Guide / Articles CRUD
route('admin.skin-guide.index')   // GET /admin/skin-guide
route('admin.skin-guide.create')  // GET /admin/skin-guide/create
route('admin.skin-guide.store')   // POST /admin/skin-guide
route('admin.skin-guide.show', $id) // GET /admin/skin-guide/{id}
route('admin.skin-guide.edit', $id) // GET /admin/skin-guide/{id}/edit
route('admin.skin-guide.update', $id) // PUT /admin/skin-guide/{id}
route('admin.skin-guide.destroy', $id) // DELETE /admin/skin-guide/{id}

// Feedback Monitoring
route('admin.feedback.monitor')   // GET /admin/feedback/monitor
route('admin.feedback.approve', $id) // POST /admin/feedback/{id}/approve
route('admin.feedback.reject', $id)  // POST /admin/feedback/{id}/reject
route('admin.feedback.helpful', $id) // POST /admin/feedback/{id}/helpful
```

#### Di Blade Template, gunakan:

```blade
{{-- Link ke Dashboard --}}
<a href="{{ route('admin.dashboard') }}">Dashboard</a>

{{-- Link ke Product List --}}
<a href="{{ route('admin.products.index') }}">All Products</a>

{{-- Link ke Create Product --}}
<a href="{{ route('admin.products.create') }}">New Product</a>

{{-- Link ke Edit Product --}}
<a href="{{ route('admin.products.edit', $product->id) }}">Edit</a>

{{-- Form submit ke Store --}}
<form action="{{ route('admin.products.store') }}" method="POST">
    @csrf
    {{-- form fields --}}
</form>

{{-- Form submit ke Update --}}
<form action="{{ route('admin.products.update', $product->id) }}" method="POST">
    @csrf
    @method('PUT')
    {{-- form fields --}}
</form>
```

---

## 🔐 Middleware & Authorization

### Struktur Middleware Admin

Semua route admin dilindungi dengan **DUA middleware sekaligus**:

```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Hanya user dengan role='admin' yang bisa akses di sini
});
```

### Penjelasan Middleware:

| Middleware | Function | Requirement | Location |
|-----------|----------|-------------|----------|
| `auth` | Memverifikasi user sudah login | User harus authenticated | Built-in Laravel |
| `admin` | Memverifikasi user adalah admin | `user.role === 'admin'` | `app/Http/Middleware/AdminMiddleware.php` |

### Implementasi AdminMiddleware:

```php
// app/Http/Middleware/AdminMiddleware.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1️⃣ Cek apakah user sudah login (auth middleware handle ini dulu)
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // 2️⃣ Cek apakah user adalah ADMIN
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Admin privileges required.');
        }

        // 3️⃣ User sudah login dan adalah admin ✅
        return $next($request);
    }
}
```

### Access Control Flow:

```
User Request
    ↓
[Middleware: auth] ──→ Is user logged in?
                       ├─ NO  → Redirect to /login
                       └─ YES ↓
[Middleware: admin] ──→ Is user.role === 'admin'?
                        ├─ NO  → HTTP 403 Forbidden
                        └─ YES ↓
Controller ✅ →→→ Route Handler Executed
```

### Registrasi Middleware:

Middleware `admin` harus didaftarkan di `app/Http/Kernel.php`:

```php
// app/Http/Kernel.php
protected $routeMiddleware = [
    // ... middleware lainnya
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
```

---

## 🎯 HTML & Tailwind v4 Standards

### 1️⃣ Layout Extension (WAJIB)

**Semua Admin Views HARUS extend layout admin, bukan user layout:**

```blade
{{-- ✅ BENAR --}}
@extends('layouts.admin.admin')
@section('page_title', 'Dashboard')
@section('content')
    {{-- Your content here --}}
@endsection

{{-- ❌ SALAH --}}
@extends('layouts.app')           {{-- Ini layout user! --}}
@extends('layouts.admin.layout')  {{-- Path salah --}}
```

---

### 2️⃣ CSS Classes dari Admin Layout

**Gunakan class yang sudah didefinisikan di `layouts/admin/admin.blade.php`:**

| Element | Class | Usage |
|---------|-------|-------|
| Sidebar | `.admin-sidebar` | Struktur sidebar (sudah ada) |
| Navigation Link | `.nav-link-admin` | Link di sidebar |
| Main Content | `.admin-main` | Wrapper main content area |
| Header | `.admin-header` | Top header bar |
| Card/Panel | `.card-admin` | Content container |
| Button Primary | `.btn-primary-admin` | CTA button (warna cokelat) |
| Button Secondary | `.btn-secondary-admin` | Secondary button |
| Table | `.table-admin` | Admin table styling |
| Badge | `.badge-admin` | Status badge |
| Badge Success | `.badge-success` | Green badge |
| Badge Warning | `.badge-warning` | Orange badge |
| Badge Danger | `.badge-danger` | Red badge |
| Input Field | `.input-admin` | Form input styling |
| Alert Box | `.alert-admin` | Alert container |

---

### 3️⃣ Contoh Koding Premium

#### ✅ BENAR - Menggunakan Admin Classes:

```blade
{{-- Dashboard Card --}}
<div class="card-admin">
    <h2 class="text-lg font-bold text-[var(--dark-brown)]">📊 Dashboard</h2>
    <p class="text-gray-600 mt-2">Welcome to SkinQuo Admin Panel</p>
</div>

{{-- Button --}}
<button type="submit" class="btn-primary-admin">
    ✅ Save Changes
</button>

{{-- Status Badge --}}
<span class="badge-admin badge-success">✅ Active</span>

{{-- Form Input with Error Handling --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
    <input 
        type="text" 
        name="name" 
        class="input-admin @error('name') error @enderror"
        value="{{ old('name') }}"
        required
    >
    @error('name')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Table --}}
<div class="card-admin overflow-x-auto">
    <table class="table-admin">
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>
                        <span class="badge-admin badge-success">Active</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit', $item->id) }}" class="btn-secondary-admin">
                            ✏️ Edit
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-gray-500 py-8">
                        📭 No items found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Alert Messages --}}
@if(session('success'))
    <div class="alert-admin alert-success">
        ✅ {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert-admin alert-error">
        <h3 class="font-semibold mb-2">⚠️ Validation Errors</h3>
        <ul class="text-sm space-y-1">
            @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

#### ❌ SALAH - Custom/Bootstrap Classes:

```blade
{{-- Inline styles (NO!) --}}
<div style="background: #603F26; padding: 20px; border-radius: 8px;">
    Content
</div>

{{-- Bootstrap classes (NO!) --}}
<button class="btn btn-primary">Submit</button>
<div class="alert alert-success">Success!</div>

{{-- Tailwind raw classes without admin prefix (AVOID) --}}
<div class="w-full bg-white p-6 rounded-lg">
    {{-- Boleh dipakai, tapi prioritas admin classes --}}
</div>

{{-- Incomplete class names --}}
<button class="btn-primary">{{-- Missing '-admin' suffix --}}</button>
```

---

## 📝 Form & Validation Examples

### Complete Form Example

```blade
@extends('layouts.admin.admin')
@section('title', 'Create Product — SkinQuo Admin')
@section('page_title', 'Create New Product')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-admin mb-6">
            <h3 class="text-lg font-bold text-[var(--dark-brown)] mb-6">Product Details</h3>

            {{-- Product Name Field --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Product Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    class="input-admin @error('name') error @enderror"
                    placeholder="e.g., Herbivore Botanical Serum"
                    value="{{ old('name') }}"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Price & Stock Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Price (IDR) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="price" 
                        step="0.01" 
                        class="input-admin @error('price') error @enderror"
                        placeholder="0.00"
                        value="{{ old('price') }}"
                        required
                    >
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Stock <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="stock_quantity" 
                        class="input-admin @error('stock_quantity') error @enderror"
                        placeholder="0"
                        value="{{ old('stock_quantity', 0) }}"
                        min="0"
                        required
                    >
                    @error('stock_quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Checkbox --}}
            <div class="mb-4 flex items-center">
                <input 
                    type="checkbox" 
                    id="is_active" 
                    name="is_active" 
                    value="1"
                    class="w-4 h-4 text-[var(--dark-brown)] border-gray-300 rounded cursor-pointer"
                    {{ old('is_active') ? 'checked' : '' }}
                >
                <label for="is_active" class="ml-2 text-sm text-gray-700">
                    Publish product immediately
                </label>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex gap-4">
            <button type="submit" class="btn-primary-admin">
                ✅ Create Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn-secondary-admin">
                ❌ Cancel
            </a>
        </div>
    </form>
</div>
@endsection
```

---

## 🔄 Working with Controllers

### Controller Method Structure

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    /**
     * Display all products
     */
    public function index(Request $request)
    {
        // Validate query params
        $search = $request->query('search');
        $status = $request->query('status');
        
        // Build query
        $query = Product::query();
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($status) {
            $query->where('is_active', $status === 'active');
        }
        
        // Paginate
        $products = $query->paginate(15);
        
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show creation form
     */
    public function create()
    {
        // Fetch data needed for form (e.g., categories, skin types)
        $skinTypes = SkinType::all();
        
        return view('admin.products.create', compact('skinTypes'));
    }

    /**
     * Store product
     */
    public function store(Request $request)
    {
        // Validate
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('products', 'public');
            $validated['main_image'] = $path;
        }

        // Create record
        Product::create($validated);

        // Log action
        // AdminLog::create([...])

        return redirect()->route('admin.products.index')
                       ->with('success', 'Product created successfully');
    }

    /**
     * Show edit form
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update product
     */
    public function update(Request $request, Product $product)
    {
        // Validate
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            // ... other rules
        ]);

        // Update
        $product->update($validated);

        return redirect()->route('admin.products.index')
                       ->with('success', 'Product updated successfully');
    }

    /**
     * Delete product (soft delete)
     */
    public function destroy(Product $product)
    {
        $product->delete(); // Uses soft delete if configured

        return redirect()->route('admin.products.index')
                       ->with('success', 'Product deleted successfully');
    }
}
```

---

## 🚀 Git & Version Control

### Branch Naming Convention

```bash
# Feature branches
git checkout -b feat-admin-product-management
git checkout -b feat-admin-dashboard
git checkout -b feat-admin-feedback-monitor

# Bug fix branches
git checkout -b fix-admin-validation-error
git checkout -b fix-admin-sorting

# Refactor branches
git checkout -b refactor-admin-layout
git checkout -b refactor-admin-controllers

# ❌ JANGAN
git checkout -b admin-stuff                    # Terlalu vague
git checkout -b feature/products               # Gunakan lowercase + underscore
git checkout -b update                         # Tidak deskriptif
```

### Commit Message Convention

```bash
# ✅ GOOD
git commit -m "Admin: Implement product management CRUD views"
git commit -m "Admin: Add feedback monitoring dashboard"
git commit -m "Admin: Fix form validation for article creation"
git commit -m "Admin: Update sidebar navigation styling"

# ❌ BAD
git commit -m "Fix bug"                        # Terlalu vague
git commit -m "Updated files"                  # Tidak informatif
git commit -m "wip"                            # Work in progress tidak jelas
git commit -m "Admin stuff"                    # Tidak deskriptif
git commit -m "asdf"                           # Random characters
```

### Full Workflow Example

```bash
# 1. Checkout latest develop/main
git checkout main
git pull origin main

# 2. Create feature branch
git checkout -b feat-admin-product-crud

# 3. Make changes
# ... edit files ...

# 4. Stage changes
git add .

# 5. Commit dengan message yang clear
git commit -m "Admin: Create product management views and controller"

# 6. Push to remote
git push origin feat-admin-product-crud

# 7. Create Pull Request on GitHub
# - Title: "Admin: Product Management CRUD Implementation"
# - Description: Explain what was done
# - Link to issue/requirement
# - Add screenshot if UI changes
```

---

## 📋 Checklist Sebelum Push

**Sebelum membuat Pull Request, pastikan:**

- [ ] Semua views extend `layouts.admin.admin`
- [ ] Menggunakan admin class: `.btn-primary-admin`, `.card-admin`, dll
- [ ] Form validation errors ditampilkan dengan `@error()` directive
- [ ] Links menggunakan `route()` helper dengan format `admin.{feature}.{action}`
- [ ] Responsive design (test di mobile 375px)
- [ ] Tidak ada console errors di browser DevTools
- [ ] Tidak ada PHP errors di server logs
- [ ] Routes terdaftar di `routes/web.php` dengan prefix `admin.`
- [ ] Controller methods sesuai Laravel conventions (index, create, store, edit, update, destroy)
- [ ] Controller docblocks lengkap (class & method documentation)
- [ ] No hardcoded colors atau styles (gunakan CSS classes)
- [ ] All TODO comments diisi atau dipindahkan ke issue
- [ ] Commit message jelas dan deskriptif

---

## 🧪 Manual Testing Checklist

### Dashboard Page
- [ ] Dashboard loads tanpa errors
- [ ] Sidebar navigation visible dan clickable
- [ ] Stats cards display correctly
- [ ] Quick action buttons work

### Products Page
- [ ] List products menampilkan dengan pagination
- [ ] Search filter berfungsi
- [ ] Status filter berfungsi
- [ ] "Add New Product" button membuka create form
- [ ] Edit button membuka edit form
- [ ] Delete button remove product

### Create/Edit Product Form
- [ ] Form fields render correctly
- [ ] Validation errors display dengan baik
- [ ] File upload input bekerja
- [ ] Submit button saves data
- [ ] Redirect ke list page setelah submit

### Skin Guide Page
- [ ] Article list display dengan pagination
- [ ] Category filter berfungsi
- [ ] Search berfungsi
- [ ] Create article button works

### Feedback Monitor
- [ ] Feedback list display dengan cards
- [ ] Filter (status, rating) berfungsi
- [ ] Search berfungsi
- [ ] Approve/Reject buttons work

### Responsive Design
- [ ] Test di 375px width (mobile)
- [ ] Test di 768px width (tablet)
- [ ] Test di 1024px width (desktop)
- [ ] Sidebar collapse di mobile
- [ ] All buttons accessible di mobile

---

## 🆘 Troubleshooting

### Problem: "Route not found [admin.products.index]"

**Solution:**
1. Check `routes/web.php` - ensure route is registered
2. Check route name - verify it matches exactly
3. Run `php artisan route:list | grep admin` to list all admin routes

### Problem: "403 Unauthorized"

**Solution:**
1. Check if user is logged in
2. Check if user has `role = 'admin'` in database
3. Check `AdminMiddleware.php` is properly configured

### Problem: "CSRF Token Mismatch"

**Solution:**
1. Ensure form has `@csrf` directive
2. Check HTML has `<meta name="csrf-token">` in head
3. Verify request header includes token

### Problem: "Validation error not showing"

**Solution:**
```blade
{{-- Add @error directive --}}
<input class="input-admin @error('name') error @enderror">
@error('name')
    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
@enderror
```

### Problem: "Styling looks broken"

**Solution:**
1. Verify Tailwind CSS is included (`@vite` in layout)
2. Check class names use correct format (`.btn-primary-admin` not `.btn-primary`)
3. Run `npm run dev` to rebuild CSS
4. Clear browser cache

### Problem: "File upload not working"

**Solution:**
1. Form must have `enctype="multipart/form-data"`
2. Input must be `type="file"`
3. Ensure storage symlink exists: `php artisan storage:link`
4. Check disk configuration in `config/filesystems.php`

---

## 📚 Resources & References

- **Tailwind CSS v4 Docs**: https://tailwindcss.com/docs
- **Laravel Blade Docs**: https://laravel.com/docs/blade
- **Laravel Routing**: https://laravel.com/docs/routing
- **Laravel Validation**: https://laravel.com/docs/validation
- **Laravel File Storage**: https://laravel.com/docs/filesystem
- **SkinQuo Database Design**: `/DATABASE_DESIGN.md`

---

## 💬 Support & Questions

Jika ada pertanyaan atau butuh clarification:

1. **Check dokumentasi** di file ini terlebih dahulu
2. **Check existing code** di folder `resources/views/admin/`
3. **Tanya ke tech lead** sebelum membuat big changes
4. **Jangan ragu untuk refactor** jika ada pattern yang bisa disederhanakan

---

## 🎯 Development Tips

1. **Gunakan Laravel Tinker** untuk testing query:
   ```bash
   php artisan tinker
   > Product::count()
   > Product::first()
   ```

2. **Check Routes**:
   ```bash
   php artisan route:list | grep admin
   ```

3. **Debug dengan dd() dan dump()**:
   ```php
   dd($variable);      // Die and dump
   dump($variable);    // Dump without die
   ```

4. **Use browser DevTools** untuk cek network requests dan console errors

5. **Test form validation** dengan berbagai input invalid

---

**Last Updated:** April 14, 2026  
**Version:** 1.0  
**Status:** 🟢 Ready for Development  

Good luck! Happy coding! 🚀
