@extends('layouts.admin.admin')
@section('title', 'Create Article - The Sanctuary')
@section('content')

<div style="max-width:1000px; margin:0 auto; padding:40px 32px;">

  <div style="margin-bottom:40px;">
    <h1 style="font-family:'Playfair Display'; font-size:42px; font-style:italic; color:var(--brown-dark); margin-bottom:12px; font-weight:400;">
      Make your new article
    </h1>
    <p style="font-family:'Jost'; font-size:13px; color:var(--brown-mid); line-height:1.8; letter-spacing:0.04em;">
      Manage the narrative of your digital articles.<br>
      From skincare rituals to scientific breakthroughs, craft your story just right.
    </p>
  </div>

  <form method="POST" action="{{ route('admin.journal.store') }}" enctype="multipart/form-data" id="article-form" style="display:grid; grid-template-columns:1fr 340px; gap:36px;">
    @csrf

    <div style="display:flex; flex-direction:column; gap:32px;">
      <div style="background:rgba(255,255,255,0.95); border-radius:24px; padding:36px; box-shadow:0 40px 80px rgba(61,35,20,0.08);">

        <div style="margin-bottom:24px;">
          <label class="field-label">Judul Artikel *</label>
          <input type="text" name="title" class="admin-input" placeholder="Judul yang menarik..." required
                 style="padding:14px 18px; border-radius:14px; background:var(--white); border:1px solid #E8D5C4;">
        </div>

        <div style="margin-bottom:24px;">
          <label class="field-label">Slug URL</label>
          <input type="text" name="slug" class="admin-input" placeholder="Otomatis dari judul"
                 style="padding:14px 18px; border-radius:14px; background:var(--white); border:1px solid #E8D5C4;">
        </div>

        <div style="margin-bottom:24px;">
          <label class="field-label">Ringkasan Artikel *</label>
          <textarea name="subtitle" rows="4" class="admin-input" placeholder="Ringkasan singkat yang menarik untuk ditampilkan di halaman Skin Guide..."
                    style="padding:14px 18px; border-radius:14px; background:var(--white); border:1px solid #E8D5C4; resize:vertical;"></textarea>
        </div>

        <div style="margin-bottom:16px;">
          <label class="field-label">Isi Artikel *</label>
          <div style="border:1px solid #E8D5C4; border-radius:16px; overflow:hidden; background:#F5F0E8;">
            <div style="display:flex; align-items:center; gap:8px; padding:12px 16px; background:#F6E7D1;">
              <button type="button" style="border:none; background:transparent; color:var(--brown-dark); font-weight:700; cursor:pointer;">B</button>
              <button type="button" style="border:none; background:transparent; color:var(--brown-dark); font-style:italic; cursor:pointer;">I</button>
              <button type="button" style="border:none; background:transparent; color:var(--brown-dark); cursor:pointer;">H2</button>
              <button type="button" style="border:none; background:transparent; color:var(--brown-dark); cursor:pointer;">H3</button>
              <button type="button" style="border:none; background:transparent; color:var(--brown-dark); cursor:pointer;">Link</button>
              <button type="button" style="border:none; background:transparent; color:var(--brown-dark); cursor:pointer;">• • •</button>
              <button type="button" style="border:none; background:transparent; color:var(--brown-dark); cursor:pointer;">+</button>
              <button type="button" style="border:none; background:transparent; color:var(--brown-dark); cursor:pointer;">−</button>
            </div>
            <textarea name="content" rows="12" class="admin-input" placeholder="Tulis artikel Anda di sini..."
                      style="width:100%; min-height:320px; border:none; border-radius:0 0 16px 16px; padding:20px; background:var(--white); font-family:'Courier New', monospace; font-size:13px; line-height:1.7; resize:vertical;">
## Understanding Retinoids

At the heart of cellular regeneration lies a class of compounds...

> "Molecular precision for biological harmony."

### The Multifaceted Benefits
- Epidermal Thickening
- Collagen Synthesis
- Stimulating fibroblasts
            </textarea>
          </div>
        </div>

        <button type="button" style="background:transparent; border:none; color:var(--brown-dark); font-family:'Jost'; font-size:12px; letter-spacing:0.08em; text-decoration:underline; cursor:pointer;">
          Lihat Preview
        </button>

      </div>
    </div>

    <div style="display:flex; flex-direction:column; gap:24px;">
      <div style="background:rgba(255,255,255,0.95); border-radius:24px; padding:28px; box-shadow:0 40px 80px rgba(61,35,20,0.08);">
        <label class="field-label">Featured Image</label>
        <div id="upload-area" style="position:relative; overflow:hidden; border:2px dashed #C4A07A; border-radius:16px; padding:28px; text-align:center; background:#FAE8CE; cursor:pointer; transition:background 0.2s ease;"
             onmouseover="this.style.background='#F6D9B7'" onmouseout="this.style.background='#FAE8CE'">
          <input type="file" name="featured_image" id="featured_image" accept="image/*" style="display:none;" onchange="handleImageUpload(event)">
          <div style="font-family:'Jost'; font-size:13px; color:var(--brown-mid); margin-bottom:10px;">Klik untuk pilih gambar</div>
          <div style="font-family:'Jost'; font-size:11px; color:var(--brown-light);">atau drag gambar ke sini</div>
          <div id="image-preview" style="margin-top:16px; display:none;"></div>
        </div>
      </div>

      <div style="background:rgba(255,255,255,0.95); border-radius:24px; padding:28px; box-shadow:0 40px 80px rgba(61,35,20,0.08);">
        <label class="field-label">Kategori</label>
        <select name="category" class="admin-input" style="padding:12px 16px; border-radius:12px; background:var(--white); border:1px solid #E8D5C4; font-family:'Jost'; font-size:12px;">
          <option value="ingredients" selected>Ingredients</option>
          <option value="science">Molecular Science</option>
          <option value="ritual">Skincare Rituals</option>
          <option value="technique">Application Techniques</option>
          <option value="trend">Trending Topics</option>
          <option value="guide">How-To Guides</option>
        </select>
      </div>

      <div style="background:rgba(255,255,255,0.95); border-radius:24px; padding:28px; box-shadow:0 40px 80px rgba(61,35,20,0.08);">
        <div style="margin-bottom:18px;"><label class="field-label">Nama Penulis</label>
          <input type="text" name="author_name" class="admin-input" placeholder="Dr. Elara Vance"
                 style="padding:12px 16px; border-radius:12px; background:var(--white); border:1px solid #E8D5C4;">
        </div>
        <div><label class="field-label">Peran Penulis</label>
          <input type="text" name="author_role" class="admin-input" placeholder="Dermatological Research Lead"
                 style="padding:12px 16px; border-radius:12px; background:var(--white); border:1px solid #E8D5C4;">
        </div>
      </div>

      <div style="background:rgba(255,255,255,0.95); border-radius:24px; padding:28px; box-shadow:0 40px 80px rgba(61,35,20,0.08);">
        <label class="field-label">Meta Description</label>
        <textarea name="meta_description" rows="3" class="admin-input" placeholder="Deskripsi untuk mesin pencari"
                  style="padding:12px 16px; border-radius:12px; background:var(--white); border:1px solid #E8D5C4; resize:vertical; font-family:'Jost'; font-size:11px;"></textarea>
        <div style="margin-top:18px;"><label class="field-label">Meta Keywords</label>
          <textarea name="keywords" rows="2" class="admin-input" placeholder="retinoid, skincare, vitamin a"
                    style="padding:12px 16px; border-radius:12px; background:var(--white); border:1px solid #E8D5C4; resize:vertical; font-family:'Jost'; font-size:11px;"></textarea>
        </div>
      </div>

      <div style="background:rgba(255,255,255,0.95); border-radius:24px; padding:28px; box-shadow:0 40px 80px rgba(61,35,20,0.08);">
        <div style="display:flex; flex-direction:column; gap:16px;">
          <label style="display:flex; align-items:center; gap:12px; cursor:pointer;">
            <input type="radio" name="status" value="publish" checked style="width:16px; height:16px; cursor:pointer;">
            <span style="font-family:'Jost'; font-size:12px; color:var(--brown-dark);">Publish Sekarang</span>
          </label>
          <label style="display:flex; align-items:center; gap:12px; cursor:pointer;">
            <input type="radio" name="status" value="draft" style="width:16px; height:16px; cursor:pointer;">
            <span style="font-family:'Jost'; font-size:12px; color:var(--brown-dark);">Simpan sebagai Draft</span>
          </label>
        </div>
      </div>
    </div>
  </form>

  <div style="display:flex; justify-content:flex-end; gap:14px; margin-top:40px; padding-top:26px; border-top:1px solid #E8D5C4;">
    <button type="button" onclick="document.getElementById('article-form').reset();"
            style="background:transparent; border:1px solid var(--brown-mid); color:var(--brown-mid); padding:12px 28px; border-radius:999px; font-family:'Jost'; font-size:11px; letter-spacing:0.12em; text-transform:uppercase; cursor:pointer;">
      Batal
    </button>
    <button type="button" onclick="document.getElementById('article-form').submit();"
            style="background:transparent; border:1px solid var(--brown-dark); color:var(--brown-dark); padding:12px 28px; border-radius:999px; font-family:'Jost'; font-size:11px; letter-spacing:0.12em; text-transform:uppercase; cursor:pointer;">
      Simpan Draft
    </button>
    <button type="submit" form="article-form"
            style="background:var(--brown-dark); color:white; border:none; padding:12px 28px; border-radius:999px; font-family:'Jost'; font-size:11px; letter-spacing:0.12em; text-transform:uppercase; cursor:pointer; font-weight:600;">
      Publish Artikel
    </button>
  </div>

</div>

<script>
  const uploadArea = document.getElementById('upload-area');
  const fileInput = document.getElementById('featured_image');
  const imagePreview = document.getElementById('image-preview');

  uploadArea.addEventListener('click', () => fileInput.click());
  uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.style.background = '#F6D9B7'; });
  uploadArea.addEventListener('dragleave', () => { uploadArea.style.background = '#FAE8CE'; });
  uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.style.background = '#FAE8CE';
    fileInput.files = e.dataTransfer.files;
    handleImageUpload({ target: fileInput });
  });

  function handleImageUpload(event) {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = (e) => {
        document.getElementById('image-preview').innerHTML = '<img src="'+e.target.result+'" style="max-width:100%; max-height:180px; border-radius:12px;">';
        imagePreview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    }
  }
</script>

@endsection
