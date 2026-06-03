@extends('layouts.admin.admin')
@section('title', 'Edit Skin Guide — SkinQuo Admin')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Jost:wght@300;400;500;600&family=DM+Mono:wght@400&display=swap" rel="stylesheet">
<style>
:root{
    --brown-dark:#3D2314;
    --brown-dk:#3D2314;
    --brown-md:#5A3923;
    --brown:#7A5030;
    --brown-lt:#8B6A50;

    --cream:#FFF9F3;
    --peach:#F8EBDD;
    --peach-lt:#FBF4EC;
    --peach-mid:#E8D5C4;

    --border:#E8D5C4;

    --green:#2E7D32;
    --green-bg:#E8F5E9;

    --amber:#D4841C;
    --red:#C84B31;

    --shadow:0 8px 24px rgba(61,35,20,.08);
}

.sge-header{
    margin-bottom:30px;
}

.sge-eyebrow{
    font-size:11px;
    text-transform:uppercase;
    letter-spacing:.15em;
    color:#7A5030;
    margin-bottom:8px;
}

.sge-title{
    font-family:'Playfair Display', serif;
    font-size:42px;
    color:#3D2314;
    margin:0;
}

.sge-subtitle{
    margin-top:10px;
    color:#7A5C43;
    font-size:14px;
}

.sge-status-badge{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 14px;
    border-radius:999px;
    font-size:11px;
    font-weight:700;
}

/* ===== SKIN GUIDE EDIT PAGE STYLES ===== */
.skin-guide-edit-page {
  width: 100%;
  padding: 28px 40px 40px 40px;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  margin: 0;
  max-width: 100%;
  background: white;
}

/* Header */
.edit-header-grid {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 34px;
  align-items: flex-start;
  margin-bottom: 34px;
  width: 100%;
  box-sizing: border-box;
}

.skin-guide-edit-page .eyebrow {
  margin: 0 0 10px;
  font-size: 11px;
  letter-spacing: 0.28em;
  text-transform: uppercase;
  color: #7A5030;
  font-weight: 700;
}

.skin-guide-edit-page h1 {
  margin: 0;
  font-family: 'Playfair Display', serif;
  font-size: clamp(2.2rem, 2.8vw, 3.2rem);
  line-height: 0.95;
  color: var(--brown-dark);
  display: flex;
  align-items: center;
  gap: 12px;
}

.skin-guide-edit-page .page-description {
  margin: 14px 0 0;
  font-size: 14px;
  color: #7C5940;
  max-width: 720px;
  line-height: 1.6;
}

/* Status Badge */
.status-badge-header {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  border-radius: 999px;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  white-space: nowrap;
}

.status-badge-header--pub {
  background: #E8F5E9;
  color: #4CAF50;
}

.status-badge-header--draft {
  background: #FDF0E0;
  color: #D4841C;
}

/* Form Layout */
.edit-layout {
  display: grid;
  grid-template-columns: 1fr 280px;
  gap: 24px;
  align-items: start;
}

@media (max-width: 1000px) {
  .edit-layout {
    grid-template-columns: 1fr;
  }
}

/* Form Card */
.form-card {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 20px;
  padding: 24px;
  border: 1px solid #E8D5C4;
  box-shadow: 0 8px 24px rgba(61, 35, 20, 0.06);
  margin-bottom: 16px;
}

.form-card:last-child {
  margin-bottom: 0;
}

.form-card-title {
  font-family: 'Playfair Display', serif;
  font-size: 16px;
  font-weight: 400;
  color: var(--brown-dark);
  margin: 0 0 20px;
  padding-bottom: 12px;
  border-bottom: 1px solid #E8D5C4;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Form Field */
.form-field {
  margin-bottom: 16px;
}

.form-field:last-child {
  margin-bottom: 0;
}

.form-label {
  display: block;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: #805F44;
  margin-bottom: 8px;
}

.form-required {
  color: #D4841C;
  margin-left: 2px;
}

.form-hint {
  font-size: 11px;
  color: #7A5C43;
  margin-top: 6px;
  line-height: 1.4;
}

/* Form Inputs */
.form-input,
.form-select,
.form-textarea {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid #E8D5C4;
  border-radius: 12px;
  font-family: 'Jost', sans-serif;
  font-size: 13px;
  color: var(--brown-dark);
  background: #FFFAF5;
  outline: none;
  transition: border-color 0.2s, background-color 0.2s, box-shadow 0.2s;
  box-sizing: border-box;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  border-color: #7A5030;
  background: white;
  box-shadow: 0 0 0 2px rgba(196, 160, 122, 0.2);
}

.form-input::placeholder,
.form-textarea::placeholder {
  color: #7A5C43;
}

.form-input.is-error,
.form-textarea.is-error {
  border-color: #D4841C;
  background: #FDF0E0;
}

.form-error {
  font-size: 11px;
  color: #D4841C;
  margin-top: 6px;
}

.form-textarea {
  resize: vertical;
  min-height: 100px;
  font-family: 'Jost', sans-serif;
  line-height: 1.6;
}

.form-textarea--content {
  font-family: 'DM Mono', monospace;
  font-size: 12px;
  min-height: 300px;
  line-height: 1.7;
}

/* Select */
.form-select {
  appearance: none;
  cursor: pointer;
  padding-right: 36px;
  background-image: linear-gradient(45deg, transparent 50%, #7A5030 50%), linear-gradient(135deg, #7A5030 50%, transparent 50%);
  background-position: right 14px center, right 9px center;
  background-repeat: no-repeat;
  background-size: 5px 5px;
}

/* Image Upload */
.img-upload-area {
  border: 2px dashed #E8D5C4;
  border-radius: 12px;
  padding: 24px;
  text-align: center;
  cursor: pointer;
  transition: border-color 0.2s, background-color 0.2s;
  background: #FFFAF5;
}

.img-upload-area:hover {
  border-color: #7A5030;
  background: #FDF0E0;
}

.img-current-image {
  margin-bottom: 16px;
  text-align: center;
}

.img-current-image img {
  max-width: 100%;
  height: 140px;
  object-fit: cover;
  border-radius: 10px;
  border: 1px solid #E8D5C4;
}

.img-upload-icon {
  font-size: 32px;
  margin-bottom: 8px;
  color: #7A5C43;
}

.img-upload-text {
  font-size: 12px;
  color: #7A5C43;
  line-height: 1.5;
}

.img-upload-text strong {
  color: #7A5030;
  display: block;
  margin-bottom: 3px;
  font-size: 13px;
  font-weight: 600;
}

#image_preview_wrap {
  margin-top: 12px;
  display: none;
}

#image_preview_wrap img {
  width: 100%;
  height: 140px;
  object-fit: cover;
  border-radius: 10px;
  border: 1px solid #E8D5C4;
}

/* Markdown Toolbar */
.md-toolbar {
  display: flex;
  gap: 6px;
  flex-wrap: wrap;
  padding: 10px 12px;
  background: #FBF1E5;
  border: 1px solid #E8D5C4;
  border-bottom: none;
  border-radius: 12px 12px 0 0;
}

.md-toolbar + .form-textarea {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

.md-btn {
  padding: 6px 12px;
  border-radius: 8px;
  font-size: 11px;
  font-weight: 600;
  border: 1px solid #D4C4B0;
  background: white;
  color: #7A5030;
  cursor: pointer;
  transition: all 0.2s;
  font-family: 'DM Mono', monospace;
}

.md-btn:hover {
  background: #7A5030;
  color: white;
  border-color: #7A5030;
}

/* Tags Grid */
.tags-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
  gap: 8px;
}

.tag-checkbox-label {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid #E8D5C4;
  background: #FFFAF5;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 12px;
  color: var(--brown-dark);
  user-select: none;
}

.tag-checkbox-label:hover {
  border-color: #7A5030;
  background: #FDF0E0;
}

.tag-checkbox-label input[type="checkbox"] {
  display: none;
}

.tag-checkbox-box {
  width: 16px;
  height: 16px;
  border-radius: 6px;
  border: 1.5px solid #7A5C43;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.tag-checkbox-label input[type="checkbox"]:checked ~ .tag-checkbox-box {
  background: #7A5030;
  border-color: #7A5030;
}

.tag-checkbox-label input[type="checkbox"]:checked ~ .tag-checkbox-box::after {
  content: '✓';
  color: white;
  font-size: 12px;
  line-height: 1;
}

.tag-checkbox-label:has(input[type="checkbox"]:checked) {
  border-color: #7A5030;
  background: #F5EFE6;
}

/* Status / Status Toggle */
.status-group {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.status-radio-label {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 14px;
  border-radius: 12px;
  border: 1px solid #E8D5C4;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 13px;
  color: var(--brown-dark);
  user-select: none;
}

.status-radio-label input[type="radio"] {
  display: none;
}

.status-radio-dot {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  border: 1.5px solid #7A5C43;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: all 0.2s;
}

.status-radio-label input[type="radio"]:checked ~ .status-radio-dot {
  border-color: #7A5030;
  background: #7A5030;
}

.status-radio-label input[type="radio"]:checked ~ .status-radio-dot::after {
  content: '';
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: white;
}

.status-radio-label:has(input[type="radio"]:checked) {
  border-color: #7A5030;
  background: #FDF0E0;
}

.status-radio-text strong {
  display: block;
  font-size: 13px;
  font-weight: 600;
  margin-bottom: 2px;
}

.status-radio-text small {
  font-size: 11px;
  color: #7A5C43;
}

/* Buttons */
.btn-group {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 24px;
}

.btn-form {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 13px 20px;
  border-radius: 999px;
  font-family: 'Jost', sans-serif;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  cursor: pointer;
  text-decoration: none;
  border: none;
  transition: all 0.2s;
  width: 100%;
  text-align: center;
}

.btn-form--primary {
  background: var(--brown-dark);
  color: white;
}

.btn-form--primary:hover {
  background: #2C1808;
}

.btn-form--secondary {
  background: #F5EFE6;
  color: #7A5030;
  border: 1px solid #E8D5C4;
}

.btn-form--secondary:hover {
  background: #E8DCCE;
}

.btn-form--ghost {
  background: transparent;
  color: #7A5C43;
  border: 1px solid #E8D5C4;
}

.btn-form--ghost:hover {
  background: #FFFAF5;
  border-color: #7A5030;
}

/* Danger Zone */
.danger-zone {
  background: #FFF5F5;
  border: 1px solid #FFE5E5;
  border-radius: 12px;
  padding: 16px;
  margin-top: 20px;
}

.danger-zone-title {
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: #D4841C;
  margin: 0 0 12px;
}

.danger-zone-text {
  font-size: 12px;
  color: #7A5C43;
  margin-bottom: 12px;
}

.btn-delete {
  background: #D4841C;
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-delete:hover {
  background: #B8614F;
}

/* Meta Info */
.meta-info-box {
  background: #F5EFE6;
  border: 1px solid #E8D5C4;
  border-radius: 12px;
  padding: 12px;
  margin-bottom: 12px;
}

.meta-info-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 11px;
  color: #7A5C43;
  margin-bottom: 8px;
}

.meta-info-item:last-child {
  margin-bottom: 0;
}

.meta-info-label {
  font-weight: 600;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

.meta-info-value {
  color: var(--brown-dark);
  font-weight: 500;
}

/* Sidebar */
.sidebar-card {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 20px;
  padding: 20px;
  border: 1px solid #E8D5C4;
  box-shadow: 0 8px 24px rgba(61, 35, 20, 0.06);
  margin-bottom: 16px;
  position: sticky;
  top: 20px;
}

.sidebar-card:last-child {
  margin-bottom: 0;
}

.sidebar-card-title {
  font-family: 'Jost', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: #805F44;
  margin: 0 0 14px;
  padding-bottom: 10px;
  border-bottom: 1px solid #E8D5C4;
}

/* Preview Toggle */
.preview-toggle-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 14px;
  border-radius: 8px;
  font-family: 'Jost', sans-serif;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: #7A5030;
  background: #FDF0E0;
  border: 1px solid #E8D5C4;
  cursor: pointer;
  margin-top: 10px;
  transition: all 0.2s;
}

.preview-toggle-btn:hover {
  background: #F5EFE6;
  border-color: #7A5030;
}

.sge-status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; }
.sge-status-pub   { background: var(--green-bg); color: var(--green); }
.sge-status-pub::before { background: var(--green); }
.sge-status-draft { background: #FDF0E0; color: var(--amber); }
.sge-status-draft::before { background: var(--amber); }

/* Layout */
.sge-layout {
  display: grid;
  grid-template-columns: 1fr 320px;
  gap: 20px; align-items: start;
}
@media (max-width: 920px) { .sge-layout { grid-template-columns: 1fr; } }

/* Card */
.sge-card {
  background: #fff; border-radius: 24px; padding: 28px;
  border: 1px solid var(--border); box-shadow: var(--shadow);
  margin-bottom: 18px;
}
.sge-card:last-child { margin-bottom: 0; }
.sge-card-title {
  font-family: 'Playfair Display', serif;
  font-size: 17px; font-weight: 400; font-style: italic;
  color: var(--brown-dk); margin: 0 0 22px;
  padding-bottom: 14px; border-bottom: 1px solid var(--border);
  display: flex; align-items: center; gap: 8px;
}

/* Fields */
.sge-field { margin-bottom: 18px; }
.sge-field:last-child { margin-bottom: 0; }
.sge-label {
  display: block; font-size: 10px; font-weight: 600;
  letter-spacing: 0.13em; text-transform: uppercase;
  color: var(--brown); margin-bottom: 7px;
}
.sge-required { color: var(--red); margin-left: 2px; }
.sge-hint { font-size: 11px; color: var(--brown-lt); margin-top: 5px; line-height: 1.5; }

.sge-input,
.sge-select,
.sge-textarea {
  width: 100%; padding: 11px 14px;
  border: 1.5px solid var(--peach-mid); border-radius: 12px;
  font-family: 'Jost', sans-serif; font-size: 13px;
  color: var(--brown-dk); background: var(--cream);
  outline: none; transition: border-color 0.2s, background 0.2s;
  box-sizing: border-box;
}
.sge-input:focus,
.sge-select:focus,
.sge-textarea:focus {
  border-color: var(--brown); background: #fff;
  box-shadow: 0 0 0 3px rgba(139,94,60,0.07);
}
.sge-input::placeholder,
.sge-textarea::placeholder { color: var(--brown-lt); }
.sge-input.is-error,
.sge-textarea.is-error { border-color: var(--red); }
.sge-error { font-size: 11px; color: var(--red); margin-top: 5px; }
.sge-textarea { resize: vertical; min-height: 100px; }
.sge-textarea--content {
  font-family: 'DM Mono', monospace; font-size: 12px;
  min-height: 280px; line-height: 1.7;
}

.sge-select {
  appearance: none; cursor: pointer; padding-right: 34px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23C9A882' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat: no-repeat; background-position: right 12px center;
}

/* Image */
#image_preview_wrap { margin-top: 8px; display: none; }
#image_preview_wrap img { width: 100%; height: 150px; object-fit: cover; border-radius: 10px; }
.sge-img-current { margin-bottom: 10px; border-radius: 12px; overflow: hidden; }
.sge-img-current img { width: 100%; height: 160px; object-fit: cover; display: block; }
.sge-img-label {
  font-size: 10px; color: var(--brown-lt); text-align: center;
  padding: 6px 0; background: var(--peach-lt);
  border-top: 1px solid var(--border);
}

/* Markdown toolbar */
.sge-md-toolbar {
  display: flex; gap: 4px; flex-wrap: wrap;
  padding: 8px 10px; background: var(--peach);
  border: 1.5px solid var(--peach-mid);
  border-bottom: none; border-radius: 12px 12px 0 0;
}
.sge-md-toolbar + .sge-textarea { border-top-left-radius: 0; border-top-right-radius: 0; }
.sge-md-btn {
  padding: 4px 9px; border-radius: 6px; font-size: 11px; font-weight: 600;
  border: 1px solid var(--peach-mid); background: #fff; color: var(--brown);
  cursor: pointer; transition: all 0.15s; font-family: 'DM Mono', monospace;
}
.sge-md-btn:hover { background: var(--brown-dk); color: #fff; border-color: var(--brown-dk); }

/* Tags */
.sge-tags-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px,1fr)); gap: 8px; }
.sge-tag-check {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 12px; border-radius: 10px;
  border: 1.5px solid var(--border); background: var(--cream);
  cursor: pointer; transition: all 0.15s;
  font-size: 12px; color: var(--brown-dk); user-select: none;
}
.sge-tag-check:hover { border-color: var(--brown-lt); background: var(--peach); }
.sge-tag-check input[type="checkbox"] { display: none; }
.sge-tag-box {
  width: 14px; height: 14px; border-radius: 4px;
  border: 1.5px solid var(--brown-lt); flex-shrink: 0;
  display: flex; align-items: center; justify-content: center; transition: all 0.15s;
}
.sge-tag-check input:checked ~ .sge-tag-box { background: var(--brown-dk); border-color: var(--brown-dk); }
.sge-tag-check input:checked ~ .sge-tag-box::after {
  content: ''; width: 5px; height: 3px;
  border-left: 1.5px solid #fff; border-bottom: 1.5px solid #fff;
  transform: rotate(-45deg) translateY(-1px);
}
.sge-tag-check:has(input:checked) { border-color: var(--brown); background: var(--peach); }

/* Status */
.sge-status-toggle { display: flex; flex-direction: column; gap: 8px; }
.sge-radio-opt {
  display: flex; align-items: center; gap: 10px;
  padding: 11px 14px; border-radius: 12px;
  border: 1.5px solid var(--border); cursor: pointer;
  transition: all 0.15s; font-size: 13px; color: var(--brown-dk); user-select: none;
}
.sge-radio-opt input[type="radio"] { display: none; }
.sge-radio-dot {
  width: 16px; height: 16px; border-radius: 50%;
  border: 1.5px solid var(--brown-lt);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; transition: all 0.15s;
}
.sge-radio-opt input:checked ~ .sge-radio-dot { border-color: var(--brown-dk); background: var(--brown-dk); }
.sge-radio-opt input:checked ~ .sge-radio-dot::after { content: ''; width: 5px; height: 5px; border-radius: 50%; background: #fff; }
.sge-radio-opt:has(input:checked) { border-color: var(--brown); background: var(--peach); }
.sge-radio-label strong { display: block; font-size: 13px; font-weight: 600; margin-bottom: 1px; }
.sge-radio-label small { font-size: 11px; color: var(--brown-lt); }

/* Meta info */
.sge-meta-row {
  display: flex; gap: 12px; flex-wrap: wrap;
  padding: 12px 14px; background: var(--cream);
  border-radius: 10px; border: 1px solid var(--border);
  margin-bottom: 16px;
}
.sge-meta-item { font-size: 11px; color: var(--brown-lt); }
.sge-meta-item strong { color: var(--brown-dk); font-weight: 600; }

/* Buttons */
.sge-btn-group { display: flex; flex-direction: column; gap: 8px; margin-top: 20px; }
.sge-btn {
  display: flex; align-items: center; justify-content: center; gap: 7px;
  padding: 13px 20px; border-radius: 40px;
  font-family: 'Jost', sans-serif; font-size: 13px; font-weight: 600;
  cursor: pointer; text-decoration: none; border: none;
  transition: all 0.2s; width: 100%; text-align: center;
}
.sge-btn--primary  { background: var(--brown-dk); color: #fff; }
.sge-btn--primary:hover { background: var(--brown-md); }
.sge-btn--secondary { background: var(--peach); color: var(--brown-dk); border: 1.5px solid var(--peach-mid); }
.sge-btn--secondary:hover { background: var(--peach-mid); }
.sge-btn--ghost { background: transparent; color: var(--brown-lt); border: 1.5px solid var(--border); }
.sge-btn--ghost:hover { background: var(--peach); color: var(--brown-dk); }
.sge-btn--danger { background: #FDECEA; color: var(--red); border: 1.5px solid #F5B8B4; }
.sge-btn--danger:hover { background: #F5C6C3; }

/* Preview toggle */
.sge-preview-toggle {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 14px; border-radius: 8px;
  font-family: 'Jost', sans-serif; font-size: 12px; font-weight: 500;
  color: var(--brown); background: var(--peach); border: 1.5px solid var(--peach-mid);
  cursor: pointer; margin-top: 10px; transition: all 0.15s;
}
.sge-preview-toggle:hover { background: var(--peach-mid); }

/* Alert */
.sge-alert {
  padding: 12px 18px; border-radius: 12px; font-size: 13px; font-weight: 500;
  margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
}
.sge-alert--success { background: var(--green-bg); color: var(--green); border: 1px solid #B8DFC3; }
.sge-alert--error   { background: #FDECEA; color: var(--red); border: 1px solid #F5B8B4; }
</style>
@endpush


@section('content')
<div class="skin-guide-edit-page">

  {{-- Flash --}}
  @if(session('success'))
    <div class="sge-alert sge-alert--success">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="sge-alert sge-alert--error">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
      {{ session('error') }}
    </div>
  @endif

  {{-- Header --}}
  <div class="sge-header">
    <div class="sge-eyebrow">
      <a href="{{ route('admin.skin-guide.index') }}">← Skin Guide</a>
      &nbsp;·&nbsp; Edit Article
    </div>
    <h1 class="sge-title">Edit your article</h1>
    <p class="sge-subtitle">Update the narrative of your digital articles. Refine your story with precision.</p>
    <div>
      @if($article->is_published)
        <span class="sge-status-badge sge-status-pub">Published</span>
      @else
        <span class="sge-status-badge sge-status-draft">Draft</span>
      @endif
    </div>
  </div>


  <form action="{{ route('admin.skin-guide.update', ['skin_guide' => $article->id]) }}"
      method="POST"
      id="sgeForm">
    @csrf
    @method('PUT')

    <div class="sge-layout">

      {{-- LEFT --}}
      <div>
        <div class="sge-card">
          <h3 class="sge-card-title"><span>✦</span> Konten Artikel</h3>

          <div class="sge-field">
            <label class="sge-label" for="title">Judul Artikel <span class="sge-required">*</span></label>
            <input class="sge-input {{ $errors->has('title') ? 'is-error' : '' }}"
                   type="text" id="title" name="title"
                   placeholder="Judul yang menarik..."
                   value="{{ old('title', $article->title) }}" required>
            @error('title') <p class="sge-error">{{ $message }}</p> @enderror
          </div>

          <div class="sge-field">
            <label class="sge-label" for="slug">Slug URL <span class="sge-required">*</span></label>
            <input class="sge-input {{ $errors->has('slug') ? 'is-error' : '' }}"
                   type="text" id="slug" name="slug"
                   placeholder="url-artikel"
                   value="{{ old('slug', $article->slug) }}" required>
            <p class="sge-hint">Edit slug dengan hati-hati — mengubahnya akan mempengaruhi URL publik.</p>
            @error('slug') <p class="sge-error">{{ $message }}</p> @enderror
          </div>

          <div class="sge-field">
            <label class="sge-label" for="excerpt">Ringkasan Artikel</label>
            <textarea class="sge-textarea {{ $errors->has('excerpt') ? 'is-error' : '' }}"
                      id="excerpt" name="excerpt" rows="3"
                      placeholder="Ringkasan singkat yang menarik...">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
            @error('excerpt') <p class="sge-error">{{ $message }}</p> @enderror
          </div>

          <div class="sge-field">
            <label class="sge-label" for="content">Isi Artikel <span class="sge-required">*</span></label>
            <div class="sge-md-toolbar">
              <button type="button" class="sge-md-btn" onclick="insertMd('**','**')"><b>B</b></button>
              <button type="button" class="sge-md-btn" onclick="insertMd('*','*')"><i>I</i></button>
              <button type="button" class="sge-md-btn" onclick="insertMd('## ','')">H2</button>
              <button type="button" class="sge-md-btn" onclick="insertMd('### ','')">H3</button>
              <button type="button" class="sge-md-btn" onclick="insertMd('[','](url)')">Link</button>
              <button type="button" class="sge-md-btn" onclick="insertMd('> ','')">··</button>
              <button type="button" class="sge-md-btn" onclick="insertMd('---\n','')">—</button>
            </div>
            <textarea class="sge-textarea sge-textarea--content {{ $errors->has('content') ? 'is-error' : '' }}"
                      id="content" name="content" required>{{ old('content', $article->content) }}</textarea>
            <p class="sge-hint">Markdown didukung: heading, bold, list, blockquote, link.</p>
            @error('content') <p class="sge-error">{{ $message }}</p> @enderror
          </div>

          <button type="button" class="sge-preview-toggle" onclick="togglePreview()">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            Lihat Preview
          </button>
          <div id="previewBox" style="display:none; margin-top:14px; padding:20px; background:var(--cream); border-radius:12px; border:1px solid var(--border); font-size:13px; line-height:1.8; color:var(--brown-dk);"></div>
        </div>
      </div>

      {{-- RIGHT --}}
      <div>
        <div class="sge-card">
          <h3 class="sge-card-title"><span>◈</span> Pengaturan Artikel</h3>

          {{-- Meta info --}}
          <div class="sge-meta-row">
            <div class="sge-meta-item">Dibuat: <strong>{{ $article->created_at?->format('d M Y') ?? '—' }}</strong></div>
            <div class="sge-meta-item">Diperbarui: <strong>{{ $article->updated_at?->format('d M Y') ?? '—' }}</strong></div>
          </div>

          {{-- Featured Image --}}
          <div class="sge-field">
            <label class="sge-label">Featured Image</label>
            @if($article->image_url)
              <div class="sge-img-current">
                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" onerror="this.parentElement.style.display='none'">
                <div class="sge-img-label">Gambar saat ini</div>
              </div>
            @endif
            <input class="sge-input" type="url" id="image_url" name="image_url"
                   placeholder="https://images.unsplash.com/..."
                   value="{{ old('image_url', $article->image_url) }}"
                   oninput="previewImage(this.value)">
            <p class="sge-hint">Kosongkan untuk tidak mengubah gambar.</p>
            <div id="image_preview_wrap">
              <img id="image_preview" src="" alt="Preview">
            </div>
            @error('image_url') <p class="sge-error">{{ $message }}</p> @enderror
          </div>

          {{-- Category --}}
          <div class="sge-field">
            <label class="sge-label" for="category">Kategori <span class="sge-required">*</span></label>
            <select class="sge-select {{ $errors->has('category') ? 'is-error' : '' }}"
                    id="category" name="category" required>
              <option value="">Pilih kategori...</option>
              @foreach(['TIPS & TRIK','PERAWATAN DASAR','BAHAN AKTIF','MASALAH KULIT','ANTI AGING','KULIT SENSITIF','HYDRATION & MOISTURE','LIFESTYLE'] as $cat)
                <option value="{{ $cat }}" {{ old('category', $article->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
              @endforeach
            </select>
            @error('category') <p class="sge-error">{{ $message }}</p> @enderror
          </div>

          {{-- Status --}}
          <div class="sge-field">
            <label class="sge-label">Status <span class="sge-required">*</span></label>
            <div class="sge-status-toggle">
              <label class="sge-radio-opt">
                <input type="radio" name="is_published" value="1"
                       {{ old('is_published', $article->is_published ? '1' : '0') == '1' ? 'checked' : '' }}>
                <span class="sge-radio-dot"></span>
                <span class="sge-radio-label">
                  <strong>Publish Sekarang</strong>
                  <small>Terlihat oleh semua pengunjung</small>
                </span>
              </label>
              <label class="sge-radio-opt">
                <input type="radio" name="is_published" value="0"
                       {{ old('is_published', $article->is_published ? '1' : '0') == '0' ? 'checked' : '' }}>
                <span class="sge-radio-dot"></span>
                <span class="sge-radio-label">
                  <strong>Simpan sebagai Draft</strong>
                  <small>Tersembunyi dari publik</small>
                </span>
              </label>
            </div>
          </div>

          <div class="sge-btn-group">
            <button type="submit" class="sge-btn sge-btn--primary">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              Save Article
            </button>
            <a href="{{ route('admin.skin-guide.index') }}" class="sge-btn sge-btn--ghost">Batal</a>
          </div>
        </div>

        {{-- Tags --}}
        <div class="sge-card">
          <h3 class="sge-card-title"><span>◉</span> Tags</h3>
          @if(isset($tags) && $tags->count() > 0)
            <div class="sge-tags-grid">
              @foreach($tags as $tag)
                <label class="sge-tag-check">
                  <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                         {{ in_array($tag->id, old('tags', $selectedTagIds ?? [])) ? 'checked' : '' }}>
                  <span class="sge-tag-box"></span>
                  {{ $tag->name }}
                </label>
              @endforeach
            </div>
          @else
            <p style="font-size:12px;color:var(--brown-lt);">Belum ada tag tersedia.</p>
          @endif
        </div>

        {{-- Danger Zone --}}
        <div class="sge-card">
          <h3 class="sge-card-title" style="color:var(--red);">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <polyline points="3 6 5 6 21 6"/>
              <path d="M19 6l-1 14H6L5 6"/>
              <path d="M9 6V4h6v2"/>
            </svg>
            Danger Zone
          </h3>
          <p style="font-size:12px;color:var(--brown-lt);margin:0 0 14px;line-height:1.6;">
            Menghapus artikel ini akan menghapus semua relasi tag secara permanen. Tindakan ini tidak bisa dibatalkan.
          </p>
          <button type="button"
                  class="sge-btn sge-btn--danger"
                  onclick="if(confirm('Hapus artikel ini secara permanen?')) document.getElementById('deleteForm').submit();">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M9 6V4h6v2"/></svg>
            Delete Article
          </button>
        </div>

      </div>
    </div>
  </form>

  {{-- Delete form terpisah di luar sgeForm, tidak nested --}}
  <form id="deleteForm"
        action="{{ route('admin.skin-guide.destroy', $article->id) }}"
        method="POST"
        style="display:none;">
    @csrf
    @method('DELETE')
  </form>

</div>

<script>
// Slug auto-gen (only if user hasn't manually edited)
const titleInput = document.getElementById('title');
const slugInput  = document.getElementById('slug');
titleInput.addEventListener('input', function() {
  if (!slugInput._edited) {
    slugInput.value = this.value.toLowerCase()
      .replace(/[^a-z0-9\s-]/g,'').trim()
      .replace(/\s+/g,'-').replace(/-+/g,'-');
  }
});
slugInput.addEventListener('input', function() { this._edited = true; });

// Image preview
function previewImage(url) {
  const w = document.getElementById('image_preview_wrap');
  const i = document.getElementById('image_preview');
  if (url && url.startsWith('http')) { i.src = url; w.style.display='block'; }
  else { w.style.display='none'; }
}
const imgVal = document.getElementById('image_url').value;
if (imgVal) previewImage(imgVal);

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

// Preview
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