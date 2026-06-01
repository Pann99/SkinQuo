@extends('layouts.admin.admin')
@section('title', 'Create Skin Guide — SkinQuo Admin')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500;600&family=DM+Mono:wght@400&display=swap" rel="stylesheet">
<style>
  /* ===== CSS VARIABLES ===== */
  :root {
    --brown-dark: #3D2314;
  }

  /* ===== CREATE SKIN GUIDE PAGE ===== */
  .skin-guide-create-page {
    padding: 48px 52px 64px;
    max-width: 1440px;
    margin: 0 auto;
    font-family: 'Jost', sans-serif;
  }

  /* ===== PAGE HEADER ===== */
  .skin-guide-create-page .page-header {
    margin-bottom: 48px;
    position: relative;
  }

  .skin-guide-create-page .page-header::after {
    content: '';
    display: block;
    width: 64px;
    height: 2px;
    background: linear-gradient(90deg, var(--brown-dark), transparent);
    margin-top: 20px;
  }

  .skin-guide-create-page .breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    font-size: 12px;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #A67C52;
  }

  .skin-guide-create-page .breadcrumb-nav a {
    color: #A67C52;
    text-decoration: none;
    transition: color 0.15s;
  }

  .skin-guide-create-page .breadcrumb-nav a:hover {
    color: var(--brown-dark);
  }

  .skin-guide-create-page .breadcrumb-nav .separator {
    opacity: 0.5;
    font-size: 10px;
  }

  .skin-guide-create-page .page-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.4rem, 3.2vw, 3.6rem);
    font-weight: 400;
    color: var(--brown-dark);
    line-height: 1.1;
    margin: 0;
  }

  .skin-guide-create-page .page-title em {
    font-style: italic;
    font-weight: 400;
    color: #A67C52;
  }

  .skin-guide-create-page .page-subtitle {
    margin: 12px 0 0;
    font-size: 14px;
    color: #7A5C43;
    line-height: 1.6;
    max-width: 580px;
  }

  /* ===== MAIN LAYOUT ===== */
  .skin-guide-create-page .form-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 320px;
    gap: 32px;
    align-items: start;
  }

  .skin-guide-create-page .form-main {
    min-width: 0;
  }

  /* ===== CARD BASE ===== */
  .skin-guide-create-page .form-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 4px 24px rgba(61, 35, 20, 0.07);
    overflow: hidden;
    margin-bottom: 24px;
  }

  .skin-guide-create-page .form-card:last-child {
    margin-bottom: 0;
  }

  .skin-guide-create-page .form-card-header {
    padding: 24px 32px 20px;
    border-bottom: 1px solid #F0E8DC;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .skin-guide-create-page .form-card-header .card-icon {
    width: 36px;
    height: 36px;
    background: #F5EDE3;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--brown-dark);
    font-size: 16px;
    flex-shrink: 0;
  }

  .skin-guide-create-page .form-card-header .card-title {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--brown-dark);
    margin: 0;
  }

  .skin-guide-create-page .form-card-body {
    padding: 28px 32px;
  }

  /* ===== FORM ELEMENTS ===== */
  .skin-guide-create-page .field-group {
    margin-bottom: 24px;
  }

  .skin-guide-create-page .field-group:last-child {
    margin-bottom: 0;
  }

  .skin-guide-create-page label {
    display: block;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #7A5C43;
    margin-bottom: 8px;
  }

  .skin-guide-create-page label .required-dot {
    display: inline-block;
    width: 5px;
    height: 5px;
    background: #C0392B;
    border-radius: 50%;
    margin-left: 4px;
    vertical-align: middle;
    position: relative;
    top: -1px;
  }

  .skin-guide-create-page .form-input,
  .skin-guide-create-page .form-select,
  .skin-guide-create-page .form-textarea {
    width: 100%;
    padding: 13px 16px;
    background: #FAFAF8;
    border: 1.5px solid #E8D5C4;
    border-radius: 12px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    color: #3D2314;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    outline: none;
    box-sizing: border-box;
    appearance: none;
    -webkit-appearance: none;
  }

  .skin-guide-create-page .form-input:focus,
  .skin-guide-create-page .form-select:focus,
  .skin-guide-create-page .form-textarea:focus {
    border-color: var(--brown-dark);
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(74, 36, 19, 0.08);
  }

  .skin-guide-create-page .form-input::placeholder,
  .skin-guide-create-page .form-textarea::placeholder {
    color: #C4A98E;
    font-style: italic;
  }

  .skin-guide-create-page .form-input.is-invalid,
  .skin-guide-create-page .form-select.is-invalid,
  .skin-guide-create-page .form-textarea.is-invalid {
    border-color: #C0392B;
    background: #FFF8F8;
  }

  .skin-guide-create-page .error-msg {
    display: block;
    font-size: 11px;
    color: #C0392B;
    margin-top: 6px;
    letter-spacing: 0.02em;
  }

  /* Select Wrapper */
  .skin-guide-create-page .select-wrapper {
    position: relative;
  }

  .skin-guide-create-page .select-wrapper::after {
    content: '';
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid #A67C52;
    pointer-events: none;
  }

  /* Textarea */
  .skin-guide-create-page .form-textarea {
    resize: vertical;
    min-height: 120px;
  }

  .skin-guide-create-page .form-textarea--content {
    font-family: 'DM Mono', monospace;
    font-size: 12px;
    min-height: 280px;
    line-height: 1.7;
  }

  /* Image Upload */
  .skin-guide-create-page .image-upload-area {
    border: 2px dashed #E8D5C4;
    border-radius: 16px;
    padding: 32px 24px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    background: #FAFAF8;
  }

  .skin-guide-create-page .image-upload-area:hover {
    border-color: var(--brown-dark);
    background: #FFF8F3;
  }

  .skin-guide-create-page .upload-icon {
    font-size: 32px;
    color: #D4B896;
    margin-bottom: 12px;
    display: block;
  }

  .skin-guide-create-page .upload-label {
    font-size: 13px;
    color: #A67C52;
    line-height: 1.5;
    display: block;
  }

  .skin-guide-create-page .upload-label strong {
    color: var(--brown-dark);
    font-weight: 600;
  }

  #image_preview_wrap { margin-top: 10px; display: none; }
  #image_preview_wrap img { width: 100%; height: 150px; object-fit: cover; border-radius: 10px; }

  /* Markdown toolbar */
  .skin-guide-create-page .md-toolbar {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
    padding: 8px 10px;
    background: #F5EDE3;
    border: 1.5px solid #E8D5C4;
    border-bottom: none;
    border-radius: 12px 12px 0 0;
  }

  .skin-guide-create-page .md-toolbar + .form-textarea {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }

  .skin-guide-create-page .md-btn {
    padding: 4px 9px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    border: 1px solid #E8D5C4;
    background: #fff;
    color: #7A5C43;
    cursor: pointer;
    transition: all 0.15s;
    font-family: 'DM Mono', monospace;
  }

  .skin-guide-create-page .md-btn:hover {
    background: var(--brown-dark);
    color: #fff;
    border-color: var(--brown-dark);
  }

  /* Tags with Scrollable Container */
  .skin-guide-create-page .tags-container {
    border: 1.5px solid #E8D5C4;
    border-radius: 12px;
    background: #FAFAF8;
    max-height: 220px;
    overflow-y: auto;
    padding: 12px;
  }

  .skin-guide-create-page .tags-container::-webkit-scrollbar {
    width: 6px;
  }

  .skin-guide-create-page .tags-container::-webkit-scrollbar-track {
    background: transparent;
  }

  .skin-guide-create-page .tags-container::-webkit-scrollbar-thumb {
    background: #D4B896;
    border-radius: 3px;
  }

  .skin-guide-create-page .tags-container::-webkit-scrollbar-thumb:hover {
    background: #A67C52;
  }

  .skin-guide-create-page .tags-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 8px;
  }

  .skin-guide-create-page .tag-check {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 10px;
    border: 1.5px solid #E8D5C4;
    background: #ffffff;
    cursor: pointer;
    transition: all 0.15s;
    font-size: 12px;
    color: #3D2314;
    user-select: none;
  }

  .skin-guide-create-page .tag-check:hover {
    border-color: #A67C52;
    background: #FFF5EC;
  }

  .skin-guide-create-page .tag-check input[type="checkbox"] {
    display: none;
  }

  .skin-guide-create-page .tag-box {
    width: 14px;
    height: 14px;
    border-radius: 4px;
    border: 1.5px solid #C4A98E;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s;
  }

  .skin-guide-create-page .tag-check input:checked ~ .tag-box {
    background: var(--brown-dark);
    border-color: var(--brown-dark);
  }

  .skin-guide-create-page .tag-check input:checked ~ .tag-box::after {
    content: '✓';
    color: white;
    font-size: 10px;
    line-height: 1;
  }

  .skin-guide-create-page .tag-check:has(input:checked) {
    border-color: var(--brown-dark);
    background: #FFF8F3;
  }

  /* Status */
  .skin-guide-create-page .status-toggle {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .skin-guide-create-page .radio-opt {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 14px;
    border-radius: 12px;
    border: 1.5px solid #E8D5C4;
    cursor: pointer;
    transition: all 0.15s;
    font-size: 13px;
    color: #3D2314;
    user-select: none;
  }

  .skin-guide-create-page .radio-opt input[type="radio"] {
    display: none;
  }

  .skin-guide-create-page .radio-dot {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 1.5px solid #C4A98E;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.15s;
  }

  .skin-guide-create-page .radio-opt input:checked ~ .radio-dot {
    border-color: var(--brown-dark);
    background: var(--brown-dark);
  }

  .skin-guide-create-page .radio-opt input:checked ~ .radio-dot::after {
    content: '';
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #fff;
  }

  .skin-guide-create-page .radio-opt:has(input:checked) {
    border-color: var(--brown-dark);
    background: #FFF5EC;
  }

  .skin-guide-create-page .radio-label {
    flex: 1;
  }

  .skin-guide-create-page .radio-label strong {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 1px;
    color: #3D2314;
  }

  .skin-guide-create-page .radio-label small {
    font-size: 11px;
    color: #A67C52;
  }

  /* ===== RIGHT SIDEBAR ===== */
  .skin-guide-create-page .sidebar-stack {
    display: flex;
    flex-direction: column;
    gap: 20px;
    position: sticky;
    top: 32px;
    align-self: start;
    height: fit-content;
    max-height: none;
    overflow: visible;
  }

  /* Action Buttons Card */
  .skin-guide-create-page .action-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 4px 24px rgba(61, 35, 20, 0.07);
    padding: 24px 28px;
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .skin-guide-create-page .btn-save-primary {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 15px 24px;
    background: var(--brown-dark);
    color: #ffffff;
    border: none;
    border-radius: 12px;
    font-family: 'Jost', sans-serif;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    cursor: pointer;
    transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
  }

  .skin-guide-create-page .btn-save-primary:hover {
    background: #5C2E10;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(61, 35, 20, 0.25);
  }

  .skin-guide-create-page .btn-save-primary:active {
    transform: translateY(0);
  }

  .skin-guide-create-page .btn-cancel-outline {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 13px 24px;
    background: transparent;
    color: #7A5C43;
    border: 1.5px solid #E8D5C4;
    border-radius: 12px;
    font-family: 'Jost', sans-serif;
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
  }

  .skin-guide-create-page .btn-cancel-outline:hover {
    background: #F5EDE3;
    border-color: #D4B896;
    color: var(--brown-dark);
    text-decoration: none;
  }

  /* Preview toggle */
  .skin-guide-create-page .preview-toggle {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 8px;
    font-family: 'Jost', sans-serif;
    font-size: 12px;
    font-weight: 500;
    color: #7A5C43;
    background: #F5EDE3;
    border: 1px solid #E8D5C4;
    cursor: pointer;
    margin-top: 10px;
    transition: all 0.15s;
  }

  .skin-guide-create-page .preview-toggle:hover {
    background: #F0E8DC;
    border-color: #D4B896;
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 1200px) {
    .skin-guide-create-page .form-layout {
      grid-template-columns: minmax(0, 1fr) 280px;
    }
  }

  @media (max-width: 992px) {
    .skin-guide-create-page .form-layout {
      grid-template-columns: 1fr;
    }
    .skin-guide-create-page .sidebar-stack {
      position: static;
      flex-direction: row;
    }
    .skin-guide-create-page .action-card {
      flex-direction: row;
    }
    .skin-guide-create-page .btn-save-primary,
    .skin-guide-create-page .btn-cancel-outline {
      flex: 1;
    }
  }

  @media (max-width: 768px) {
    .skin-guide-create-page {
      padding: 32px 24px 48px;
    }
    .skin-guide-create-page .form-card-body {
      padding: 22px 20px;
    }
    .skin-guide-create-page .form-card-header {
      padding: 20px 20px 16px;
    }
    .skin-guide-create-page .sidebar-stack {
      flex-direction: column;
    }
    .skin-guide-create-page .action-card {
      flex-direction: column;
    }
  }
</style>
@endpush

@section('content')
<div class="skin-guide-create-page">

  {{-- PAGE HEADER --}}
  <div class="page-header">
    <nav class="breadcrumb-nav">
      <a href="{{ route('admin.skin-guide.index') }}">Skin Guide</a>
      <span class="separator">›</span>
      <span>New Article</span>
    </nav>
    <h1 class="page-title">Make your new <em>Article</em></h1>
    <p class="page-subtitle">Complete the details below to create a new skin guide article for your community.</p>
  </div>

  {{-- FORM --}}
  <form action="{{ route('admin.skin-guide.store') }}" method="POST" id="sgcForm">
    @csrf

    <div class="form-layout">

      {{-- LEFT COLUMN: FORM FIELDS --}}
      <div class="form-main">

        {{-- CARD 1: Article Content --}}
        <div class="form-card">
          <div class="form-card-header">
            <div class="card-icon"><i class="bi bi-file-text"></i></div>
            <h3 class="card-title">Article Content</h3>
          </div>
          <div class="form-card-body">

            <div class="field-group">
              <label for="title">Article Title <span class="required-dot"></span></label>
              <input type="text" id="title" name="title" class="form-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
                     placeholder="Enter an engaging title..."
                     value="{{ old('title') }}" required>
              @error('title')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="field-group">
              <label for="slug">URL Slug <span class="required-dot"></span></label>
              <input type="text" id="slug" name="slug" class="form-input {{ $errors->has('slug') ? 'is-invalid' : '' }}"
                     placeholder="Auto-generated from title"
                     value="{{ old('slug') }}" required>
              @error('slug')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="field-group">
              <label for="excerpt">Article Summary <span class="required-dot"></span></label>
              <textarea id="excerpt" name="excerpt" class="form-textarea {{ $errors->has('excerpt') ? 'is-invalid' : '' }}"
                        placeholder="Brief summary for preview on Skin Guide listing page...">{{ old('excerpt') }}</textarea>
              @error('excerpt')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="field-group">
              <label for="content">Article Content <span class="required-dot"></span></label>
              <div class="md-toolbar">
                <button type="button" class="md-btn" onclick="insertMd('**','**')"><b>B</b></button>
                <button type="button" class="md-btn" onclick="insertMd('*','*')"><i>I</i></button>
                <button type="button" class="md-btn" onclick="insertMd('## ','')">H2</button>
                <button type="button" class="md-btn" onclick="insertMd('### ','')">H3</button>
                <button type="button" class="md-btn" onclick="insertMd('[','](url)')">Link</button>
                <button type="button" class="md-btn" onclick="insertMd('> ','')">Quote</button>
                <button type="button" class="md-btn" onclick="insertMd('- ','')">List</button>
              </div>
              <textarea id="content" name="content" class="form-textarea form-textarea--content {{ $errors->has('content') ? 'is-invalid' : '' }}"
                        placeholder="## Article Title&#10;&#10;Write your article content here using Markdown...&#10;&#10;### Section Title&#10;Add engaging content that educates and inspires your community." required>{{ old('content') }}</textarea>
              @error('content')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <button type="button" class="preview-toggle" onclick="togglePreview()">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              Preview
            </button>
            <div id="previewBox" style="display:none; margin-top:14px; padding:20px; background:#FAFAF8; border-radius:12px; border:1px solid #E8D5C4; font-size:13px; line-height:1.8; color:#3D2314;"></div>

          </div>
        </div>

        {{-- CARD 2: Article Image & Metadata --}}
        <div class="form-card">
          <div class="form-card-header">
            <div class="card-icon"><i class="bi bi-image"></i></div>
            <h3 class="card-title">Featured Image & Category</h3>
          </div>
          <div class="form-card-body">

            <div class="field-group">
              <label for="image_url">Featured Image</label>
              <div class="image-upload-area">
                <span class="upload-icon">🖼</span>
                <span class="upload-label"><strong>Click to select</strong> or paste image URL below</span>
              </div>
              <input type="url" id="image_url" name="image_url" class="form-input {{ $errors->has('image_url') ? 'is-invalid' : '' }}"
                     placeholder="https://images.unsplash.com/..."
                     value="{{ old('image_url') }}"
                     oninput="previewImage(this.value)"
                     style="margin-top: 8px;">
              <div id="image_preview_wrap">
                <img id="image_preview" src="" alt="Preview">
              </div>
              @error('image_url')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

            <div class="field-group">
              <label for="category">Category <span class="required-dot"></span></label>
              <div class="select-wrapper">
                <select id="category" name="category" class="form-select {{ $errors->has('category') ? 'is-invalid' : '' }}" required>
                  <option value="" disabled {{ old('category') ? '' : 'selected' }}>Select a category…</option>
                  @foreach(['TIPS & TRIK','PERAWATAN DASAR','BAHAN AKTIF','MASALAH KULIT','ANTI AGING','KULIT SENSITIF','HYDRATION & MOISTURE','LIFESTYLE'] as $cat)
                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                  @endforeach
                </select>
              </div>
              @error('category')<span class="error-msg">{{ $message }}</span>@enderror
            </div>

          </div>
        </div>

      </div>

      {{-- RIGHT COLUMN: SIDEBAR --}}
      <div>
        <div class="sidebar-stack">

          {{-- Status Card --}}
          <div class="form-card">
            <div class="form-card-header">
              <div class="card-icon"><i class="bi bi-toggle-on"></i></div>
              <h3 class="card-title">Publication Status</h3>
            </div>
            <div class="form-card-body">

              <div class="status-toggle">
                <label class="radio-opt">
                  <input type="radio" name="is_published" value="1" {{ old('is_published','0') == '1' ? 'checked' : '' }}>
                  <span class="radio-dot"></span>
                  <span class="radio-label">
                    <strong>Publish Now</strong>
                    <small>Visible to all</small>
                  </span>
                </label>
                <label class="radio-opt">
                  <input type="radio" name="is_published" value="0" {{ old('is_published','0') == '0' ? 'checked' : '' }}>
                  <span class="radio-dot"></span>
                  <span class="radio-label">
                    <strong>Save as Draft</strong>
                    <small>Hidden from public</small>
                  </span>
                </label>
              </div>

            </div>
          </div>

          {{-- Tags Card --}}
          <div class="form-card">
            <div class="form-card-header">
              <div class="card-icon"><i class="bi bi-tags"></i></div>
              <h3 class="card-title">Tags</h3>
            </div>
            <div class="form-card-body">

              @if(isset($tags) && $tags->count() > 0)
                <div class="tags-container">
                  <div class="tags-grid">
                    @foreach($tags as $tag)
                      <label class="tag-check">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                               {{ in_array($tag->id, old('tags',[])) ? 'checked' : '' }}>
                        <span class="tag-box"></span>
                        {{ $tag->name }}
                      </label>
                    @endforeach
                  </div>
                </div>
              @else
                <p style="font-size: 12px; color: #A67C52;">No tags available. Create tags first.</p>
              @endif

            </div>
          </div>

          {{-- Action Buttons --}}
          <div class="action-card">
            <button type="submit" class="btn-save-primary">
              <i class="bi bi-check-circle"></i>
              Save Article
            </button>
            <a href="{{ route('admin.skin-guide.index') }}" class="btn-cancel-outline">
              <i class="bi bi-x-circle"></i>
              Cancel
            </a>
          </div>

        </div>
      </div>

    </div>
  </form>

</div>

<script>
// Auto slug
document.getElementById('title').addEventListener('input', function() {
  const s = document.getElementById('slug');
  if (!s._edited) {
    s.value = this.value.toLowerCase()
      .replace(/[^a-z0-9\s-]/g,'').trim()
      .replace(/\s+/g,'-').replace(/-+/g,'-');
  }
});
document.getElementById('slug').addEventListener('input', function() { this._edited = true; });

// Image preview
function previewImage(url) {
  const w = document.getElementById('image_preview_wrap');
  const i = document.getElementById('image_preview');
  if (url && url.startsWith('http')) { i.src = url; w.style.display='block'; }
  else { w.style.display='none'; }
}
if (document.getElementById('image_url').value) previewImage(document.getElementById('image_url').value);

// Markdown insert
function insertMd(before, after) {
  const ta = document.getElementById('content');
  const s = ta.selectionStart, e = ta.selectionEnd;
  const sel = ta.value.substring(s, e);
  ta.value = ta.value.substring(0,s) + before + sel + after + ta.value.substring(e);
  ta.selectionStart = s + before.length;
  ta.selectionEnd = s + before.length + sel.length;
  ta.focus();
}

// Preview toggle (basic markdown render)
function togglePreview() {
  const box = document.getElementById('previewBox');
  if (box.style.display === 'none') {
    let html = document.getElementById('content').value
      .replace(/^### (.+)/gm,'<h3 style="font-family:Playfair Display,serif;font-size:16px;margin:12px 0 6px">$1</h3>')
      .replace(/^## (.+)/gm,'<h2 style="font-family:Playfair Display,serif;font-size:18px;margin:16px 0 8px">$1</h2>')
      .replace(/\*\*(.+?)\*\*/g,'<strong>$1</strong>')
      .replace(/\*(.+?)\*/g,'<em>$1</em>')
      .replace(/^> (.+)/gm,'<blockquote style="border-left:3px solid #C9A882;padding-left:12px;margin:8px 0;font-style:italic;color:#8B5E3C">$1</blockquote>')
      .replace(/^- (.+)/gm,'<li style="margin:3px 0;padding-left:4px">$1</li>')
      .replace(/\n/g,'<br>');
    box.innerHTML = html;
    box.style.display = 'block';
  } else {
    box.style.display = 'none';
  }
}
</script>
@endsection