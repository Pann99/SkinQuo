@extends('layouts.admin.admin')
@section('title', 'Skin Guide - The Sanctuary')

@section('content')

<style>
  :root {
    --cream-bg:   #FEF3E2;
    --cream-card: #FFF8EE;
    --brown-dark: #3B1F0E;
    --brown-mid:  #7A4B2A;
    --brown-light:#C4906A;
    --brown-border:#E8D5BE;
  }
  .sg-page {
    background: var(--cream-bg);
    min-height: 100vh;
    padding: 48px 48px;
    font-family: 'Jost', sans-serif;
  }
  .sg-header { margin-bottom: 32px; }
  .sg-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 52px;
    font-weight: 400;
    color: var(--brown-dark);
    line-height: 1.1;
    margin: 0 0 12px;
  }
  .sg-header h1 em { font-style: italic; font-weight: 400; }
  .sg-header p {
    font-size: 14px;
    color: var(--brown-mid);
    max-width: 500px;
    line-height: 1.6;
    margin: 0;
  }
  .sg-tabs {
    display: inline-flex;
    background: #F5E8D0;
    border-radius: 999px;
    padding: 5px;
    gap: 4px;
    margin-bottom: 36px;
  }
  .sg-tab {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 22px;
    border-radius: 999px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: var(--brown-mid);
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
    border: none;
    background: transparent;
    cursor: pointer;
  }
  .sg-tab.active, .sg-tab:hover {
    background: #fff;
    color: var(--brown-dark);
    text-decoration: none;
  }
  .sg-tab i { font-size: 15px; }
  .sg-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 32px;
  }
  .sg-upload-card {
    background: var(--cream-card);
    border-radius: 20px;
    padding: 24px;
  }
  .sg-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }
  .sg-card-header-left {
    display: flex;
    align-items: center;
    gap: 14px;
  }
  .sg-card-icon {
    width: 44px;
    height: 44px;
    background: #F0DFC8;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: var(--brown-mid);
    flex-shrink: 0;
  }
  .sg-card-title {
    font-family: 'Jost', sans-serif;
    font-size: 15px;
    font-weight: 700;
    color: var(--brown-dark);
    margin: 0 0 3px;
  }
  .sg-card-updated {
    font-size: 12px;
    color: var(--brown-light);
    margin: 0;
  }
  .sg-badge-csv {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    background: #F0DFC8;
    color: var(--brown-mid);
    padding: 4px 10px;
    border-radius: 999px;
    text-transform: uppercase;
    white-space: nowrap;
  }
  .sg-dropzone {
    border: 2px dashed var(--brown-border);
    border-radius: 14px;
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    position: relative;
    background: transparent;
  }
  .sg-dropzone:hover, .sg-dropzone.dragover {
    border-color: var(--brown-mid);
    background: #FFF3E5;
  }
  .sg-dropzone input[type=file] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
  }
  .sg-dropzone-icon {
    width: 52px;
    height: 52px;
    background: #F0DFC8;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: var(--brown-mid);
    margin: 0 auto 14px;
  }
  .sg-dropzone-title {
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: var(--brown-dark);
    margin: 0 0 4px;
  }
  .sg-dropzone-sub {
    font-size: 12px;
    color: var(--brown-light);
    margin: 0;
  }
  .sg-upload-progress {
    background: #F5EAD8;
    border-radius: 12px;
    padding: 14px 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    position: relative;
  }
  .sg-progress-icon {
    width: 36px;
    height: 36px;
    background: var(--brown-dark);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 16px;
    flex-shrink: 0;
  }
  .sg-progress-info { flex: 1; min-width: 0; }
  .sg-progress-filename {
    font-size: 13px;
    font-weight: 600;
    color: var(--brown-dark);
    margin: 0 0 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .sg-progress-meta {
    font-size: 11px;
    color: var(--brown-light);
    margin: 0 0 8px;
  }
  .sg-progress-bar-wrap {
    background: #E8D5BE;
    border-radius: 999px;
    height: 4px;
    width: 100%;
    margin-bottom: 6px;
  }
  .sg-progress-bar {
    background: var(--brown-dark);
    border-radius: 999px;
    height: 4px;
    width: 0%;
    transition: width 0.3s;
  }
  .sg-progress-status {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.06em;
    color: var(--brown-light);
    text-align: right;
  }
  .sg-progress-close {
    position: absolute;
    top: 10px;
    right: 12px;
    background: none;
    border: none;
    font-size: 14px;
    color: var(--brown-light);
    cursor: pointer;
    padding: 0;
    line-height: 1;
  }
  .sg-progress-close:hover { color: var(--brown-dark); }
  .sg-bottom-actions {
    display: flex;
    justify-content: flex-end;
    gap: 14px;
    align-items: center;
  }
  .btn-download-tpl {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    color: var(--brown-dark);
    border: 1.5px solid var(--brown-border);
    border-radius: 999px;
    padding: 12px 24px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.15s;
  }
  .btn-download-tpl:hover { background: #F5E8D0; color: var(--brown-dark); text-decoration: none; }
  .btn-process-all {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--brown-dark);
    color: #fff;
    border: none;
    border-radius: 999px;
    padding: 12px 28px;
    font-family: 'Jost', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s, transform 0.15s;
  }
  .btn-process-all:hover { background: var(--brown-mid); transform: translateY(-1px); }
  .sg-dot {
    width: 8px;
    height: 8px;
    background: #E74C3C;
    border-radius: 50%;
    position: absolute;
    top: 8px;
    left: 8px;
    z-index: 1;
  }
  @media (max-width: 900px) {
    .sg-grid { grid-template-columns: 1fr; }
    .sg-page { padding: 32px 24px; }
    .sg-header h1 { font-size: 36px; }
  }
</style>

<div class="sg-page">

  {{-- HEADER --}}
  <div class="sg-header">
    <h1>Upload <em>Dictionary File</em></h1>
    <p>Drag and drop CSV files to update system dictionaries. Ensure
       formats match the required schema to maintain data integrity.</p>
  </div>

  {{-- TABS --}}
  <div class="sg-tabs">
    <a href="{{ route('admin.inventory') }}" class="sg-tab">
      <i class="bi bi-archive"></i>
      Product Catalog
    </a>
    <a href="{{ route('admin.skin-guide.index') }}" class="sg-tab active">
      <i class="bi bi-journal-bookmark"></i>
      Skin Guide Articles
    </a>
  </div>

  {{-- GRID --}}
  <div class="sg-grid">

    {{-- Card 1: Product Dictionary --}}
    <div class="sg-upload-card">
      <div class="sg-card-header">
        <div class="sg-card-header-left">
          <div class="sg-card-icon"><i class="bi bi-bag"></i></div>
          <div>
            <div class="sg-card-title">Product Dictionary</div>
            <div class="sg-card-updated">Last updated: Today, 09:41 AM</div>
          </div>
        </div>
        <span class="sg-badge-csv">CSV Only</span>
      </div>
      <div class="sg-dropzone" id="zone-product"
           ondragover="handleDragOver(event,'zone-product')"
           ondragleave="handleDragLeave('zone-product')"
           ondrop="handleDrop(event,'zone-product','progress-product')">
        <input type="file" accept=".csv" onchange="handleFileSelect(this,'progress-product')">
        <div class="sg-dropzone-icon"><i class="bi bi-file-earmark-arrow-up"></i></div>
        <div class="sg-dropzone-title">Click to upload or drag and drop</div>
        <div class="sg-dropzone-sub">Max file size: 50MB</div>
      </div>
      <div id="progress-product" style="display:none; margin-top:12px;"></div>
    </div>

    {{-- Card 2: Constraint Dictionary --}}
    <div class="sg-upload-card">
      <div class="sg-card-header">
        <div class="sg-card-header-left">
          <div class="sg-card-icon"><i class="bi bi-exclamation-triangle"></i></div>
          <div>
            <div class="sg-card-title">Constraint Dictionary</div>
            <div class="sg-card-updated">Last updated: 1 week ago</div>
          </div>
        </div>
        <span class="sg-badge-csv">CSV Only</span>
      </div>
      <div style="position:relative;">
        <div class="sg-dot"></div>
        <div class="sg-dropzone" id="zone-constraint"
             ondragover="handleDragOver(event,'zone-constraint')"
             ondragleave="handleDragLeave('zone-constraint')"
             ondrop="handleDrop(event,'zone-constraint','progress-constraint')">
          <input type="file" accept=".csv" onchange="handleFileSelect(this,'progress-constraint')">
          <div class="sg-dropzone-icon"><i class="bi bi-file-earmark-arrow-up"></i></div>
          <div class="sg-dropzone-title">Click to upload or drag and drop</div>
          <div class="sg-dropzone-sub">Max file size: 50MB</div>
        </div>
      </div>
      <div id="progress-constraint" style="display:none; margin-top:12px;"></div>
    </div>

    {{-- Card 3: Skin Type Dictionary --}}
    <div class="sg-upload-card">
      <div class="sg-card-header">
        <div class="sg-card-header-left">
          <div class="sg-card-icon"><i class="bi bi-droplet"></i></div>
          <div>
            <div class="sg-card-title">Skin Type Dictionary</div>
            <div class="sg-card-updated">Last updated: Yesterday, 04:20 PM</div>
          </div>
        </div>
        <span class="sg-badge-csv">CSV Only</span>
      </div>
      <div class="sg-dropzone" id="zone-skintype"
           ondragover="handleDragOver(event,'zone-skintype')"
           ondragleave="handleDragLeave('zone-skintype')"
           ondrop="handleDrop(event,'zone-skintype','progress-skintype')">
        <input type="file" accept=".csv" onchange="handleFileSelect(this,'progress-skintype')">
        <div class="sg-dropzone-icon"><i class="bi bi-file-earmark-arrow-up"></i></div>
        <div class="sg-dropzone-title">Click to upload or drag and drop</div>
        <div class="sg-dropzone-sub">Max file size: 50MB</div>
      </div>
      <div id="progress-skintype" style="display:none; margin-top:12px;"></div>
    </div>

    {{-- Card 4: Ingredient Dictionary --}}
    <div class="sg-upload-card">
      <div class="sg-card-header">
        <div class="sg-card-header-left">
          <div class="sg-card-icon"><i class="bi bi-shield-check"></i></div>
          <div>
            <div class="sg-card-title">Ingredient Dictionary</div>
            <div class="sg-card-updated">Last updated: 1 week ago</div>
          </div>
        </div>
        <span class="sg-badge-csv">CSV Only</span>
      </div>
      <div class="sg-dropzone" id="zone-ingredient"
           ondragover="handleDragOver(event,'zone-ingredient')"
           ondragleave="handleDragLeave('zone-ingredient')"
           ondrop="handleDrop(event,'zone-ingredient','progress-ingredient')">
        <input type="file" accept=".csv" onchange="handleFileSelect(this,'progress-ingredient')">
        <div class="sg-dropzone-icon"><i class="bi bi-file-earmark-arrow-up"></i></div>
        <div class="sg-dropzone-title">Click to upload or drag and drop</div>
        <div class="sg-dropzone-sub">Max file size: 50MB</div>
      </div>
      <div id="progress-ingredient" style="display:none; margin-top:12px;"></div>
    </div>

  </div>{{-- end sg-grid --}}

  {{-- BOTTOM ACTIONS --}}
  <div class="sg-bottom-actions">
    <a href="#" class="btn-download-tpl" onclick="event.preventDefault(); alert('Downloading CSV templates...')">
      <i class="bi bi-download"></i>
      Download Templates
    </a>
    <button type="button" class="btn-process-all" onclick="alert('Processing all uploaded dictionaries...')">
      <i class="bi bi-play-fill"></i>
      Process All
    </button>
  </div>

</div>{{-- end sg-page --}}

@push('scripts')
<script>
  function handleDragOver(e, zoneId) {
    e.preventDefault();
    document.getElementById(zoneId).classList.add('dragover');
  }
  function handleDragLeave(zoneId) {
    document.getElementById(zoneId).classList.remove('dragover');
  }
  function handleDrop(e, zoneId, progressId) {
    e.preventDefault();
    document.getElementById(zoneId).classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file) showProgress(progressId, file);
  }
  function handleFileSelect(input, progressId) {
    const file = input.files[0];
    if (file) showProgress(progressId, file);
  }
  function showProgress(containerId, file) {
    const container = document.getElementById(containerId);
    const sizeMB = (file.size / 1024 / 1024).toFixed(1);
    container.style.display = 'block';
    container.innerHTML = `
      <div class="sg-upload-progress">
        <div class="sg-progress-icon"><i class="bi bi-file-earmark-text"></i></div>
        <div class="sg-progress-info">
          <div class="sg-progress-filename">${file.name}</div>
          <div class="sg-progress-meta">${sizeMB} MB &bull; uploading...</div>
          <div class="sg-progress-bar-wrap">
            <div class="sg-progress-bar" id="bar-${containerId}" style="width:0%"></div>
          </div>
          <div class="sg-progress-status" id="status-${containerId}">UPLOADING...</div>
        </div>
        <button class="sg-progress-close" onclick="dismissProgress('${containerId}')">&times;</button>
      </div>`;
    simulateProgress(`bar-${containerId}`, `status-${containerId}`);
  }
  function simulateProgress(barId, statusId) {
    let pct = 0;
    const iv = setInterval(() => {
      pct += Math.random() * 12;
      if (pct >= 100) {
        pct = 100;
        clearInterval(iv);
        const s = document.getElementById(statusId);
        if (s) { s.textContent = 'COMPLETE ✓'; s.style.color = '#27AE60'; }
      }
      const b = document.getElementById(barId);
      if (b) b.style.width = pct + '%';
    }, 300);
  }
  function dismissProgress(containerId) {
    const el = document.getElementById(containerId);
    if (el) { el.style.display = 'none'; el.innerHTML = ''; }
  }
</script>
@endpush

@endsection