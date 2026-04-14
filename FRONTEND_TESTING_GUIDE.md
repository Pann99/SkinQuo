# 🧪 FRONTEND TESTING GUIDE - MOCK DATA VERSION

Panduan lengkap untuk testing semua halaman Frontend yang telah dibuat.

---

## 📋 PRE-TESTING SETUP

### 1. Pastikan Routes Terdaftar

Edit `routes/web.php` dan tambahkan:

```php
// Halaman Frontend dengan Mock Data
Route::get('/catalog', function() {
    return view('pages.catalog-with-mock');
})->name('catalog.index');

Route::get('/skin-guide', function() {
    return view('pages.skin-guide-with-mock');
})->name('skin-guide.index');

Route::get('/consultation-result', function() {
    return view('pages.consultation-result-with-mock');
})->name('consultation.result');

Route::get('/feedback', function() {
    return view('pages.feedback-with-mock');
})->name('feedback.create');

Route::middleware('auth')->get('/profile', function() {
    return view('pages.profile-with-mock');
})->name('profile.show');
```

### 2. Jalankan Laravel Server

```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

### 3. Test Koneksi

Buka browser dan akses:
- http://localhost:8000/catalog ✓
- http://localhost:8000/skin-guide ✓
- http://localhost:8000/consultation-result ✓
- http://localhost:8000/feedback ✓
- http://localhost:8000/profile (perlu login) ✓

---

## 🧪 TEST SCENARIO 1: CATALOG PAGE

**URL:** http://localhost:8000/catalog

### Visual Tests
- [ ] Page memuat dengan background peach (#FFEAC5)
- [ ] Header title "Katalog Produk Skincare" terlihat jelas
- [ ] Subtitle description terlihat di bawah title
- [ ] Filter container berwarna white dengan shadow

### Filter Tests
- [ ] Dropdown "Tipe Kulit" dapat diklik dan terbuka
- [ ] Dropdown "Kategori" dapat diklik dan terbuka
- [ ] Input "Harga Max" dapat menerima input angka
- [ ] Tombol "🔍 Cari" responsive dan berubah warna saat hover
- [ ] Tombol "Reset" dapat di-klik dan mereset form

### Product Grid Tests
- [ ] Jumlah products 6 items terlihat
- [ ] Setiap product card menampilkan:
  - [ ] Product image (emoji placeholder ✓)
  - [ ] Brand name di atas
  - [ ] Product name (heading)
  - [ ] Category badge
  - [ ] Star rating (bintang)
  - [ ] Review count
  - [ ] Price (Rp format)
  - [ ] "Keranjang" button
  - [ ] "View detail" button
- [ ] 2 products memiliki "⭐ Bestseller" badge (CeraVe & Vitamin C)

### Product Card Interactions
- [ ] Hover pada card → card naik (translateY effect)
- [ ] Hover pada card → shadow bertambah besar
- [ ] Klik "🛒 Keranjang" button → tampil alert "✅ [Product] ditambahkan ke keranjang!"
- [ ] Klik "👁️" button → navigate ke detail (mock)

### Responsive Tests

**Desktop (1200px+):**
- [ ] Filter sidebar: 4 columns (tipe, kategori, harga, buttons)
- [ ] Product grid: 3 columns
- [ ] Spacing dan padding proper

**Tablet (768px):**
- [ ] Filter: masih terlihat dengan proper wrapping
- [ ] Product grid: 2 columns
- [ ] Font sizes readable
- [ ] Buttons clickable

**Mobile (375px):**
- [ ] Filter: 1 column per field
- [ ] Product grid: 1 column atau max 2
- [ ] Filter buttons stack vertically
- [ ] Product cards full width readable
- [ ] Prices clearly visible

### Data Verification
- [ ] Hydrating Essence: Rp 425.000 ✓
- [ ] CeraVe Moisturizer: Rp 185.000 ✓
- [ ] Sunscreen: Rp 95.000 ✓
- [ ] Tatcha Mist: Rp 375.000 ✓
- [ ] Cleanser: Rp 145.000 ✓
- [ ] Vitamin C: Rp 89.000 ✓

---

## 🧪 TEST SCENARIO 2: SKIN GUIDE PAGE

**URL:** http://localhost:8000/skin-guide

### Header Tests
- [ ] Title "Panduan Perawatan Kulit" terlihat prominent
- [ ] Subtitle terlihat di bawah
- [ ] Positioning dan alignment proper

### Featured Article Tests
- [ ] Featured article card terlihat dengan background white
- [ ] Image area menampilkan emoji 📚
- [ ] Content berisi:
  - [ ] Heading "Memahami Tipe Kulit Anda"
  - [ ] Deskripsi artikel
  - [ ] Tombol "Baca Selengkapnya →"
- [ ] Card hover effect: shadow bertambah, card naik
- [ ] Responsive layout: 2 columns desktop, 1 column mobile

### Category Tabs Tests
- [ ] 5 tabs terlihat: ✨ Semua, 🧴 Perawatan, 💡 Tips, 🌿 Ingredients, ❤️ Kesehatan
- [ ] Tab pertama "Semua Artikel" default active (background brown, text white)
- [ ] Hover pada tab: color berubah
- [ ] Klik tab: active state berubah (responsive, tapi mock data tidak filter)

### Article Grid Tests
- [ ] 6 articles terlihat dalam grid
- [ ] Setiap article card menampilkan:
  - [ ] Image placeholder (emoji)
  - [ ] Category badge
  - [ ] Reading time (top right)
  - [ ] Article title (heading)
  - [ ] Excerpt text (preview)
  - [ ] Author name + date (footer)
  - [ ] "Baca" link button
- [ ] Article data terlihat:
  1. [ ] "10 Kesalahan Skincare" - Dr. Siti (8 min)
  2. [ ] "Rutinitas Pagi" - Intan Beauty (6 min)
  3. [ ] "Hyaluronic Acid" - Prof. Dr. Kusuma (7 min)
  4. [ ] "Kulit Sensitif" - Dr. Ria (9 min)
  5. [ ] "SPF Sunscreen" - Dermatology (5 min)
  6. [ ] "Exfoliation" - Beauty Expert (6 min)

### Hover & Interaction Tests
- [ ] Hover pada article card → card naik (translateY -8px)
- [ ] Hover pada article card → shadow bertambah
- [ ] Klik "Baca" button → navigate (mock)

### Responsive Tests

**Desktop:**
- [ ] Featured article: 2 columns (image + text)
- [ ] Article grid: 3 columns
- [ ] Proper spacing & padding

**Tablet:**
- [ ] Featured article: 1 column (image atas, text bawah)
- [ ] Article grid: 2 columns

**Mobile:**
- [ ] Featured article: 1 column, smaller image
- [ ] Article grid: 1 column
- [ ] Text sizes optimized
- [ ] Footer properly aligned

---

## 🧪 TEST SCENARIO 3: CONSULTATION RESULT PAGE

**URL:** http://localhost:8000/consultation-result

### Header Tests
- [ ] Title "✨ Hasil Analisis Kulit Anda" terlihat
- [ ] Subtitle "Berdasarkan jawaban Anda..." terlihat
- [ ] Proper centering dan typography

### Skin Type Section Tests
- [ ] "Tipe Kulit Anda" label visible
- [ ] Badge menampilkan "🎭 Kulit Kombinasi"
- [ ] Badge styling: gradient background, white text, shadow
- [ ] Deskripsi skin type terlihat di bawah badge
- [ ] Teks deskripsi relate ke "kombinasi"

### Characteristics Section Tests
- [ ] Heading "Karakteristik Kulit Terdeteksi" visible
- [ ] Grid menampilkan 6 items:
  1. [ ] 🍃 Berminyak di T-Zone
  2. [ ] 💧 Normal di Pipi
  3. [ ] ⚠️ Sensitif
  4. [ ] ✨ Kulit Kering Musiman
  5. [ ] 🔴 Rawan Jerawat
  6. [ ] 🌙 Pori-Pori Besar
- [ ] Setiap item: icon + name dalam card
- [ ] Hover pada card: background berubah, border highlight

### Recommendations Section Tests
- [ ] Heading "Rekomendasi Perawatan" visible
- [ ] 5 recommendation items visible:
  1. [ ] 🧼 Cleanser yang Lembut
  2. [ ] 💦 Hydration Bertingkat
  3. [ ] ☀️ Sunscreen Wajib
  4. [ ] 🧪 Chemical Exfoliating
  5. [ ] 🎯 Targeted Treatments
- [ ] Setiap item: title + description
- [ ] Border left styling (peach background)
- [ ] Hover effect: background darker, card geser kanan

### Products Section Tests
- [ ] "Produk yang Kami Rekomendasikan" heading visible
- [ ] Gradient background section visible
- [ ] 6 product items dalam grid:
  - [ ] Hydrating Essence
  - [ ] Gel Moisturizer
  - [ ] Sunscreen SPF 50
  - [ ] BHA Serum
  - [ ] Clay Mask
  - [ ] Vitamin C Serum
- [ ] Setiap product: emoji + name + price
- [ ] Info text "Klik produk untuk..." visible
- [ ] Hover effect: card naik

### Confidence Meter Tests
- [ ] "Akurasi Analisis" label visible
- [ ] Progress bar terlihat dengan animation
- [ ] Percentage: 92%
- [ ] Text "92% - Analisis yang sangat akurat" visible
- [ ] Bar fill animates dari 0 ke 92% saat page load

### Action Buttons Tests
- [ ] 3 buttons visible:
  - [ ] 🛒 Lihat Katalog Produk (primary, brown)
  - [ ] 📥 Download Hasil (secondary, white)
  - [ ] 🔄 Konsultasi Ulang (secondary, white)
- [ ] Buttons hover: color/shadow changes
- [ ] Klik "Download Hasil" → file downloaded
  - [ ] File name: "hasil-analisis-skinquo.txt"
  - [ ] Content includes: tipe kulit, characteristics, recommendations
- [ ] Klik "Lihat Katalog" → navigate ke /catalog

### Responsive Tests
- [ ] Desktop: full layout dengan spacing
- [ ] Tablet: buttons 2+1 or stacked
- [ ] Mobile: buttons full width, stacked

---

## 🧪 TEST SCENARIO 4: FEEDBACK FORM PAGE

**URL:** http://localhost:8000/feedback

### Form Section Tests

#### Name & Email Fields
- [ ] "Nama" label visible dengan "required" indicator
- [ ] Name input accepts text input
- [ ] Name input focus: border color changes to brown
- [ ] Email label visible dengan "required" indicator
- [ ] Email input accepts email
- [ ] Email input focus: border color changes to brown

#### Product Selection
- [ ] "Produk yang Anda Beri Feedback" dropdown visible
- [ ] Dropdown contains 6 products + 1 "Produk Lainnya" option
- [ ] Default: "-- Pilih Produk --"
- [ ] Clicking dropdown: opens options list
- [ ] Each option selectable

#### Rating System - INTERACTIVE TEST
- [ ] 5 stars visible: ★ ★ ★ ★ ★
- [ ] "0 / 5" displayed initially
- [ ] Hover over star 1: only star 1 highlighted (opacity 1)
- [ ] Hover over star 3: stars 1-3 highlighted
- [ ] Click star 4: 4 stars filled, "4 / 5" displayed
- [ ] Visual feedback clear (opacity change, scale effect)

#### Feedback Type Radio Buttons
- [ ] Label visible: "Jenis Feedback *"
- [ ] 5 radio options visible:
  - [ ] Kualitas Produk
  - [ ] Kemasan & Pengiriman
  - [ ] Layanan Pelanggan
  - [ ] Website & Aplikasi
  - [ ] Lainnya
- [ ] Can select one option (radio behavior)
- [ ] Selected option shows proper styling

#### Textarea
- [ ] "Feedback / Saran *" label visible
- [ ] Textarea placeholder: "Ceritakan pengalaman Anda..."
- [ ] Helper text: "Minimal 10 karakter..."
- [ ] Focus state: border changes, shadow appears
- [ ] Can type multiple lines
- [ ] Min-height proper (120px)

#### Recommendation Radio Buttons
- [ ] Label: "Apakah Anda akan merekomendasikan produk ini? *"
- [ ] 3 options visible:
  - [ ] 👍 Ya, saya akan merekomendasikan
  - [ ] 🤔 Mungkin, tergantung kondisi
  - [ ] 👎 Tidak, ada produk yang lebih baik
- [ ] Can select one option

#### Form Buttons
- [ ] "📤 Kirim Feedback" button visible (brown, primary)
- [ ] "Bersihkan" button visible (white, secondary)
- [ ] Hover on submit: button color darker
- [ ] Klik Bersihkan: form resets to empty

### Form Validation Tests
- [ ] Try submit dengan feedback < 10 chars: alert "minimal 10 karakter"
- [ ] Try submit tanpa rating: alert "Silakan pilih rating"
- [ ] Try submit dengan semua fields: success!

### Success Message Tests
- [ ] Submit form dengan valid data: success message appears
- [ ] Message background: green (#10B981)
- [ ] Message text: "✅ Terima kasih! Feedback Anda telah berhasil dikirim..."
- [ ] Animation: slide in dari atas
- [ ] Auto-hide: message disappears after 5 seconds
- [ ] Form resets: all fields empty
- [ ] Stars reset: "0 / 5" displayed

### Recent Feedback Section Tests
- [ ] "Feedback Terbaru dari Pengguna Lain" heading visible
- [ ] 6 feedback items displayed in grid
- [ ] Each feedback item shows:
  - [ ] User name
  - [ ] ✓ Verified badge (for verified users)
  - [ ] 5-star rating (filled stars + text)
  - [ ] Product name (small text)
  - [ ] Feedback text in quotes
  - [ ] Date posted
  - [ ] 👍 Helpful badge/button

### Sample Feedback Data Verification
1. [ ] Siti Nurhaliza - 5★ - CeraVe - "Produk ini benar-benar..." - Verified
2. [ ] Intan Beauty - 4★ - Hydrating Essence - "Essence yang bagus..." - Verified
3. [ ] Ratih Kusuma - 5★ - Sunscreen - "Sunscreen terbaik..." - Verified
4. [ ] Dewi Santoso - 3★ - Vitamin C - "Cukup bagus..." - Unverified
5. [ ] Ayu Wijaya - 5★ - Cleanser - "Pembersih terbaik..." - Verified
6. [ ] Nisa Rahman - 4★ - Mist - "Mist yang menciptakan..." - Verified

### Responsive Tests
- [ ] Desktop: form full width, feedback grid 3 columns
- [ ] Tablet: form proper width, feedback grid 2 columns
- [ ] Mobile: form proper spacing, feedback grid 1 column, buttons full width

---

## 🧪 TEST SCENARIO 5: USER PROFILE PAGE

**URL:** http://localhost:8000/profile

### Pre-requirement
- [ ] Must be logged in (authenticate first)
- [ ] If not logged in: redirect to login page

### Profile Header Tests
- [ ] Avatar: 👩‍🦱 displayed (120px circle)
- [ ] User name: "Siti Nurhaliza" visible
- [ ] Email: "siti.nurhaliza@email.com" visible
- [ ] Phone: "+62 812-3456-7890" visible
- [ ] Metadata:
  - [ ] 📅 "Bergabung 15 Januari 2024"
  - [ ] 🔍 "5 Konsultasi"
- [ ] Buttons:
  - [ ] "✏️ Edit Profil" button
  - [ ] "⚙️ Pengaturan" link
- [ ] Hover on buttons: color changes, slight raise effect

### Tab Navigation Tests
- [ ] 3 tabs visible:
  - [ ] 👤 Informasi (default active)
  - [ ] 🔍 Riwayat Konsultasi
  - [ ] ⚙️ Preferensi
- [ ] Active tab: brown background, white text, bottom border
- [ ] Click on tab: switches to that tab content
- [ ] Previous tab content disappears

### Tab 1: Informasi Tests
- [ ] Content displays in grid (auto-fit columns)
- [ ] Shows 6 fields:
  - [ ] Nama Lengkap: "Siti Nurhaliza"
  - [ ] Email: "siti.nurhaliza@email.com"
  - [ ] Nomor Telepon: "+62 812-3456-7890"
  - [ ] Tipe Kulit: "Kombinasi"
  - [ ] Tanggal Bergabung: "15 Januari 2024"
  - [ ] Akun Status: "✓ Aktif" (green text)
- [ ] Labels: uppercase, gray text, small font
- [ ] Values: larger font, brown text, bold

### Tab 2: Riwayat Konsultasi Tests

#### If Consultations Exist (should be 5)
- [ ] Table header visible: Tanggal | Jenis Konsultasi | Hasil | Aksi
- [ ] 5 consultation rows visible:

**Row 1:**
- [ ] Date: "15 Januari 2025"
- [ ] Type: "Skin Analysis" (badge)
- [ ] Result: "Kulit Kombinasi"
- [ ] Buttons: 👁️ Lihat, 📥 Download

**Row 2:**
- [ ] Date: "8 Januari 2025"
- [ ] Type: "Problem Skin" (badge)
- [ ] Result: "Berminyak & Berjerawat"
- [ ] Buttons: 👁️ Lihat, 📥 Download

**Row 3:**
- [ ] Date: "2 Desember 2024"
- [ ] Type: "Skin Analysis" (badge)
- [ ] Result: "Kulit Normal"

**Row 4:**
- [ ] Date: "20 November 2024"
- [ ] Type: "Sensitivity Check" (badge)
- [ ] Result: "Sensitif terhadap Fragrance"

**Row 5:**
- [ ] Date: "5 November 2024"
- [ ] Type: "Initial Consultation" (badge)
- [ ] Result: "Kulit Kombinasi"

#### Table Interactions
- [ ] Hover on table row: slight background color change
- [ ] Click 👁️ button: navigate to consultation detail
- [ ] Click 📥 button: download consultation result

#### Empty State (if no consultations)
- [ ] Icon: 📭
- [ ] Heading: "Belum Ada Riwayat Konsultasi"
- [ ] Text: "Mulai konsultasi kulit Anda..."
- [ ] Button: "🔍 Mulai Konsultasi" link

### Tab 3: Preferensi Tests

#### Email Notification Section
- [ ] Heading: "📧 Notifikasi Email"
- [ ] 3 toggle switches visible:
  1. [ ] "Promosi dan Penawaran Spesial" - toggle ON
  2. [ ] "Update Produk Baru" - toggle ON
  3. [ ] "Tips & Panduan Skincare" - toggle OFF

#### Toggle Switch Functionality
- [ ] Click toggle: switches between on/off
- [ ] Visual change: background color (gray → brown)
- [ ] Inner circle moves: left → right
- [ ] Alert message: "✅ Preferensi telah disimpan!"

#### Privacy & Security Section
- [ ] Heading: "🔐 Privasi & Keamanan"
- [ ] 2 toggle switches visible:
  1. [ ] "Tampilkan Profil di Direktori" - toggle OFF
  2. [ ] "Izinkan Orang Lain Melihat Riwayat" - toggle OFF

#### Language Preference Section
- [ ] Heading: "🌐 Preferensi Bahasa"
- [ ] Dropdown visible with options:
  - [ ] Bahasa Indonesia (selected)
  - [ ] English
- [ ] Can change language selection

### Responsive Tests

**Desktop (> 900px):**
- [ ] Profile header: 3 columns (avatar | info | actions side by side)
- [ ] Actions buttons: flex direction column
- [ ] Table: full width, all columns visible

**Tablet (768px):**
- [ ] Profile header: grid-template-columns: 1fr
- [ ] Actions buttons: flex direction row
- [ ] Table: may scroll horizontally

**Mobile (< 768px):**
- [ ] Profile header: stacked vertically
- [ ] Avatar smaller (80px)
- [ ] Title smaller (1.3rem)
- [ ] Buttons full width, stacked
- [ ] Table: very compressed or scrollable
- [ ] Preferences: single column

---

## 🔧 DEBUGGING TESTS

### Console Check
- [ ] Press F12 to open Developer Tools
- [ ] Go to Console tab
- [ ] No red error messages should appear
- [ ] Warning messages acceptable (minor)

### Network Tab Check
- [ ] View all requests
- [ ] All responses: 200 OK (success)
- [ ] No 404 errors
- [ ] No CORS issues

### Performance Tests
- [ ] Page load time: < 1 second
- [ ] Interactions: instant response (< 100ms)
- [ ] Animations: smooth (no jank)
- [ ] Scrolling: smooth performance

### Accessibility Tests
- [ ] Tab navigation works (keyboard)
- [ ] Form labels associated with inputs
- [ ] Links have proper text
- [ ] Images have alt text or are decorative
- [ ] Color contrast sufficient
- [ ] Focus indicators visible

---

## 📱 DEVICE-SPECIFIC TESTS

### Desktop (Chrome/Edge)
```
Resolution: 1920x1080
Viewport: 1920px wide
Test all pages for proper layout
```

### Tablet (iPad simulation)
```
Resolution: 768x1024
Viewport: 768px wide
Test responsive layouts
Thumb-friendly button sizes
```

### Mobile (iPhone 12 simulation)
```
Resolution: 390x844
Viewport: 390px wide
Test mobile-first design
Stack test
Touch targets (44px+)
```

---

## ✅ FINAL ACCEPTANCE CRITERIA

**All pages must:**
- [ ] Load without errors
- [ ] Display all content properly
- [ ] Respond to user interactions
- [ ] Be responsive on all devices
- [ ] Have proper styling & colors
- [ ] Show animations smoothly
- [ ] Validate form inputs
- [ ] Display mock data correctly
- [ ] Have working buttons/links
- [ ] Be accessible via keyboard

---

## 🐛 ISSUE REPORTING

If you find issues:

1. **Describe the issue:** What's not working?
2. **Device/Browser:** What device and browser?
3. **Steps to reproduce:** How to trigger the issue?
4. **Expected vs actual:** What should happen vs what happened?
5. **Screenshot:** Visual proof if possible

Example:
```
Title: Rating stars not highlighting on hover
Device: Mobile Safari on iPhone 12
Steps:
  1. Go to /feedback
  2. Hover/tap on rating stars
  3. Expected: Stars highlight on hover
  4. Actual: No visual feedback
```

---

## 🎉 SUCCESS CRITERIA

Testing complete if:
✅ All 5 pages load properly
✅ All buttons work correctly
✅ Forms submit successfully
✅ Mock data displays correctly
✅ Responsive on all devices
✅ No console errors
✅ All interactions smooth
✅ Styling matches design system
✅ Animations work smoothly
✅ Performance acceptable

---

**Testing Version:** 1.0
**Frontend Build:** Complete
**Status:** Ready for QA Testing
