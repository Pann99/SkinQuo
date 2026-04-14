@extends('layouts.app')

@section('title', 'My Profile — SkinQuo')

@push('styles')
<style>
    /* ══════════════════════════════════
       PROFILE / DASHBOARD PAGE
    ══════════════════════════════════ */
    .pf-page {
        background: #FFEAC5;
        min-height: 100vh;
        padding-top: 7rem;
        padding-bottom: 6rem;
    }

    .pf-inner {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* ── Top Layout: Avatar + Info + Edit ── */
    .pf-header-card {
        background: #603F26;
        border-radius: 28px;
        padding: 2.5rem 3rem;
        display: flex;
        align-items: center;
        gap: 2.5rem;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
    }

    .pf-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .pf-avatar {
        width: 110px; height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 219, 181, 0.4);
    }

    .pf-avatar-placeholder {
        width: 110px; height: 110px;
        border-radius: 50%;
        background: rgba(255, 219, 181, 0.15);
        border: 3px solid rgba(255, 219, 181, 0.3);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #FFDBB5;
    }

    .pf-header-info {
        flex: 1;
    }

    .pf-eyebrow {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(255, 219, 181, 0.4);
        margin-bottom: 0.4rem;
    }

    .pf-name {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 700;
        color: #FFEAC5;
        margin-bottom: 0.5rem;
    }

    .pf-meta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .pf-meta-item {
        font-size: 0.8rem;
        color: rgba(255, 234, 197, 0.6);
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .pf-header-actions {
        display: flex;
        flex-direction: column;
        gap: 0.7rem;
        flex-shrink: 0;
    }

    .pf-edit-btn {
        background: rgba(255, 219, 181, 0.12);
        border: 1.5px solid rgba(255, 219, 181, 0.25);
        border-radius: 999px;
        padding: 0.6rem 1.5rem;
        font-size: 0.82rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        color: #FFDBB5;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
        text-align: center;
        display: block;
    }
    .pf-edit-btn:hover { background: rgba(255, 219, 181, 0.22); }

    .pf-logout-btn {
        background: none;
        border: none;
        font-size: 0.78rem;
        color: rgba(255, 234, 197, 0.4);
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        text-decoration: underline;
        text-align: center;
        padding: 0;
    }
    .pf-logout-btn:hover { color: rgba(255, 234, 197, 0.7); }

    /* ── Main 2-col layout ── */
    .pf-layout {
        display: grid;
        grid-template-columns: 340px 1fr;
        gap: 1.75rem;
        align-items: start;
    }

    @media (max-width: 920px) { .pf-layout { grid-template-columns: 1fr; } }
    @media (max-width: 640px) {
        .pf-header-card { padding: 2rem 1.75rem; }
        .pf-header-actions { flex-direction: row; flex-wrap: wrap; }
    }

    /* ── Card base ── */
    .pf-card {
        background: #fff;
        border-radius: 20px;
        padding: 1.75rem 2rem;
        border: 1.5px solid rgba(108, 78, 49, 0.08);
        margin-bottom: 1.5rem;
    }
    .pf-card:last-child { margin-bottom: 0; }

    .pf-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #603F26;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(96, 63, 38, 0.07);
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    /* ── Edit Profile Form ── */
    .pf-form-group {
        margin-bottom: 1.25rem;
    }
    .pf-form-group label {
        display: block;
        font-size: 0.78rem;
        font-weight: 700;
        color: rgba(96, 63, 38, 0.65);
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 0.45rem;
    }
    .pf-form-input,
    .pf-form-select {
        width: 100%;
        background: rgba(255, 219, 181, 0.35);
        border: 1.5px solid rgba(108, 78, 49, 0.12);
        border-radius: 10px;
        padding: 0.7rem 1rem;
        font-size: 0.875rem;
        font-family: 'Poppins', sans-serif;
        color: #603F26;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .pf-form-input:focus,
    .pf-form-select:focus {
        border-color: #603F26;
        box-shadow: 0 0 0 3px rgba(96, 63, 38, 0.1);
    }
    .pf-form-input::placeholder { color: rgba(96, 63, 38, 0.35); }

    .pf-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.9rem;
    }
    @media (max-width: 480px) { .pf-form-row { grid-template-columns: 1fr; } }

    .pf-form-btn {
        width: 100%;
        background: #603F26;
        color: #FFEAC5;
        border: none;
        border-radius: 10px;
        padding: 0.82rem;
        font-size: 0.875rem;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
        margin-top: 0.5rem;
    }
    .pf-form-btn:hover { opacity: 0.85; transform: translateY(-1px); }

    .pf-alert {
        border-radius: 10px;
        padding: 0.85rem 1rem;
        margin-bottom: 1.25rem;
        font-size: 0.82rem;
        line-height: 1.6;
    }
    .pf-alert-success {
        background: rgba(76, 175, 80, 0.08);
        border-left: 3px solid #66bb6a;
        color: #2e7d32;
    }
    .pf-alert-error {
        background: rgba(244, 67, 54, 0.07);
        border-left: 3px solid #ef5350;
        color: #b71c1c;
    }

    /* ── Consultation History ── */
    .pf-history-empty {
        text-align: center;
        padding: 2.5rem 1rem;
        color: rgba(96, 63, 38, 0.38);
    }
    .pf-history-empty-icon { font-size: 2.75rem; margin-bottom: 0.75rem; }

    .pf-history-table {
        width: 100%;
        border-collapse: collapse;
    }
    .pf-history-table th {
        text-align: left;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: rgba(96, 63, 38, 0.4);
        padding: 0 0.75rem 0.85rem;
        border-bottom: 1px solid rgba(96, 63, 38, 0.08);
    }
    .pf-history-table td {
        padding: 1rem 0.75rem;
        font-size: 0.83rem;
        color: #603F26;
        border-bottom: 1px solid rgba(96, 63, 38, 0.06);
        vertical-align: middle;
    }
    .pf-history-table tr:last-child td { border-bottom: none; }

    .pf-history-id {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .pf-history-story {
        font-size: 0.78rem;
        color: rgba(96, 63, 38, 0.55);
        max-width: 220px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pf-history-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .pf-status-pending { background: rgba(255, 152, 0, 0.12); color: #e65100; }
    .pf-status-processed { background: rgba(76, 175, 80, 0.12); color: #2e7d32; }

    .pf-history-view {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: #6C4E31;
        text-decoration: none;
        border: 1px solid rgba(108, 78, 49, 0.2);
        border-radius: 999px;
        padding: 0.3rem 0.85rem;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .pf-history-view:hover {
        background: #603F26;
        border-color: #603F26;
        color: #FFEAC5;
    }

    /* ── Tags in history ── */
    .pf-tag-pills {
        display: flex;
        gap: 0.3rem;
        flex-wrap: wrap;
    }
    .pf-tag-pill {
        background: rgba(96, 63, 38, 0.07);
        border-radius: 999px;
        padding: 0.18rem 0.6rem;
        font-size: 0.65rem;
        font-weight: 600;
        color: #6C4E31;
    }

    /* ── Account stats strip ── */
    .pf-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0;
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        border: 1.5px solid rgba(108, 78, 49, 0.08);
        margin-bottom: 1.5rem;
    }
    .pf-stat {
        padding: 1.25rem;
        text-align: center;
        border-right: 1px solid rgba(96, 63, 38, 0.07);
    }
    .pf-stat:last-child { border-right: none; }
    .pf-stat-number {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #603F26;
        line-height: 1;
        margin-bottom: 0.3rem;
    }
    .pf-stat-label {
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: rgba(96, 63, 38, 0.42);
    }
</style>
@endpush

@section('content')
<div class="pf-page">
<div class="pf-inner">

    {{-- Profile Header ── --}}
    <div class="pf-header-card">
        <div class="pf-avatar-wrap">
            @if($user->avatar ?? false)
                <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="pf-avatar">
            @else
                <div class="pf-avatar-placeholder">
                    {{ strtoupper(substr($user->first_name ?? $user->name ?? 'U', 0, 1)) }}
                </div>
            @endif
        </div>

        <div class="pf-header-info">
            <div class="pf-eyebrow">Member Since {{ ($user->created_at ?? now())->format('Y') }}</div>
            <div class="pf-name">{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}</div>
            <div class="pf-meta">
                <span class="pf-meta-item">✉ {{ $user->email ?? '—' }}</span>
                @if($user->mobile_number ?? false)
                    <span class="pf-meta-item">📱 {{ $user->mobile_number }}</span>
                @endif
                @if(($user->birth_date ?? false))
                    <span class="pf-meta-item">🎂 {{ \Carbon\Carbon::parse($user->birth_date)->format('d F Y') }}</span>
                @endif
            </div>
        </div>

        <div class="pf-header-actions">
            <a href="#edit-form" class="pf-edit-btn">Edit Profile</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="pf-logout-btn">Logout</button>
            </form>
        </div>
    </div>

    {{-- Stats Strip ── --}}
    <div class="pf-stats">
        <div class="pf-stat">
            <div class="pf-stat-number">{{ ($consultations ?? collect())->count() }}</div>
            <div class="pf-stat-label">Konsultasi</div>
        </div>
        <div class="pf-stat">
            <div class="pf-stat-number">{{ ($user->created_at ?? now())->diffInDays(now()) }}</div>
            <div class="pf-stat-label">Hari Bergabung</div>
        </div>
        <div class="pf-stat">
            <div class="pf-stat-number">{{ ($consultations ?? collect())->where('status', 'processed')->count() }}</div>
            <div class="pf-stat-label">Selesai</div>
        </div>
    </div>

    {{-- Main 2-col ── --}}
    <div class="pf-layout">

        {{-- LEFT: Edit Form ── --}}
        <div>
            <div class="pf-card" id="edit-form">
                <div class="pf-card-title">
                    ✏️ Edit Profile
                </div>

                @if (session('status'))
                    <div class="pf-alert pf-alert-success">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="pf-alert pf-alert-error">
                        <ul style="padding-left: 1.2rem; margin: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Name ── --}}
                    <div class="pf-form-row">
                        <div class="pf-form-group">
                            <label for="first_name">Nama Depan</label>
                            <input type="text" id="first_name" name="first_name" class="pf-form-input"
                                   value="{{ old('first_name', $user->first_name ?? '') }}" required>
                        </div>
                        <div class="pf-form-group">
                            <label for="last_name">Nama Belakang</label>
                            <input type="text" id="last_name" name="last_name" class="pf-form-input"
                                   value="{{ old('last_name', $user->last_name ?? '') }}" required>
                        </div>
                    </div>

                    {{-- Email & Mobile ── --}}
                    <div class="pf-form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="pf-form-input"
                               value="{{ old('email', $user->email ?? '') }}" required>
                    </div>
                    <div class="pf-form-group">
                        <label for="mobile_number">Nomor Telepon</label>
                        <input type="tel" id="mobile_number" name="mobile_number" class="pf-form-input"
                               placeholder="+62..."
                               value="{{ old('mobile_number', $user->mobile_number ?? '') }}">
                    </div>

                    {{-- Birth date & Gender ── --}}
                    <div class="pf-form-row">
                        <div class="pf-form-group">
                            <label for="birth_date">Tanggal Lahir</label>
                            <input type="date" id="birth_date" name="birth_date" class="pf-form-input"
                                   value="{{ old('birth_date', ($user->birth_date ?? null)?->format('Y-m-d')) }}">
                        </div>
                        <div class="pf-form-group">
                            <label for="gender">Jenis Kelamin</label>
                            <select id="gender" name="gender" class="pf-form-select">
                                <option value="" disabled {{ old('gender', $user->gender ?? '') ? '' : 'selected' }}>Pilih...</option>
                                @foreach(['female' => 'Perempuan', 'male' => 'Laki-laki', 'non_binary' => 'Non-binary', 'prefer_not' => 'Prefer not to say'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('gender', $user->gender ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Avatar ── --}}
                    <div class="pf-form-group">
                        <label for="avatar">Foto Profil</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="pf-form-input" style="padding: 0.5rem 1rem;">
                        <div style="font-size:0.7rem; color:rgba(96,63,38,0.42); margin-top:0.35rem;">JPG, PNG, WebP — maks. 2MB</div>
                    </div>

                    <button type="submit" class="pf-form-btn">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        {{-- RIGHT: Consultation History ── --}}
        <div>
            <div class="pf-card">
                <div class="pf-card-title">
                    🕑 History Konsultasi
                </div>

                @if(($consultations ?? collect())->isEmpty())
                    <div class="pf-history-empty">
                        <div class="pf-history-empty-icon">🌿</div>
                        <p style="font-size:0.9rem; font-weight:500; color:rgba(96,63,38,0.5);">Belum ada konsultasi.</p>
                        <a href="{{ route('consultation.index') }}"
                           style="display:inline-block; margin-top:1rem; background:#603F26; color:#FFEAC5; border-radius:999px; padding:0.65rem 1.5rem; font-size:0.82rem; font-weight:700; text-decoration:none; transition:opacity .2s;"
                           onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
                            Mulai Konsultasi →
                        </a>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table class="pf-history-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ringkasan</th>
                                    <th>Tags</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consultations as $c)
                                    <tr>
                                        <td class="pf-history-id">#{{ $c->id }}</td>
                                        <td>
                                            <div class="pf-history-story" title="{{ $c->skin_story }}">
                                                {{ Str::limit($c->skin_story, 55) }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($c->tags && count($c->tags) > 0)
                                                <div class="pf-tag-pills">
                                                    @foreach(array_slice($c->tags, 0, 2) as $tag)
                                                        <span class="pf-tag-pill">{{ $tag }}</span>
                                                    @endforeach
                                                    @if(count($c->tags) > 2)
                                                        <span class="pf-tag-pill">+{{ count($c->tags) - 2 }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span style="color:rgba(96,63,38,0.3); font-size:0.75rem;">—</span>
                                            @endif
                                        </td>
                                        <td style="font-size:0.75rem; color:rgba(96,63,38,0.5); white-space:nowrap;">
                                            {{ $c->created_at?->format('d M Y') }}
                                        </td>
                                        <td>
                                            <span class="pf-history-status {{ $c->status === 'processed' ? 'pf-status-processed' : 'pf-status-pending' }}">
                                                {{ ucfirst($c->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('consultation.result', $c->id) }}" class="pf-history-view">
                                                Lihat
                                                <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination ── --}}
                    @if($consultations->hasPages())
                        <div style="padding-top: 1.5rem; border-top: 1px solid rgba(96,63,38,0.07);">
                            {{ $consultations->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

    </div>
</div>
</div>
@endsection