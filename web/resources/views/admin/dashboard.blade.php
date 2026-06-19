@extends('layouts.admin.admin')

@section('title', 'Admin Dashboard — SkinQuo')

@section('content')
<div class="dashboard-page">
    <header class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Dashboard</h1>
            <p class="dashboard-subtitle">Welcome back to the atelier. Here is an overview of your editorial influence today.</p>
        </div>

        <div class="dashboard-timecard">
            <span class="timecard-label">CURRENT LOCAL TIME</span>
            <strong>{{ now()->setTimezone('Asia/Jakarta')->format('H:i') }} WIB, {{ now()->setTimezone('Asia/Jakarta')->format('d M Y') }}</strong>
        </div>
    </header>

    <div class="dashboard-metrics-grid">
        <article class="dashboard-stat-card">
            <p class="stat-card-label">Total Products</p>
            <div class="stat-card-bottom">
                <strong class="stat-card-value">{{ $totalProducts ?? 0 }}</strong>
                <span class="stat-card-icon-bi"><i class="bi bi-box-seam"></i></span>
            </div>
        </article>

        <article class="dashboard-stat-card">
            <p class="stat-card-label">Skin Guide Articles</p>
            <div class="stat-card-bottom">
                <strong class="stat-card-value">{{ $totalArticles ?? 0 }}</strong>
                <span class="stat-card-icon-bi"><i class="bi bi-journal-richtext"></i></span>
            </div>
        </article>

        <article class="dashboard-stat-card">
            <p class="stat-card-label">Pending Feedback</p>
            <div class="stat-card-bottom">
                <strong class="stat-card-value">{{ $pendingFeedback ?? 0 }}</strong>
                <span class="stat-card-icon-bi"><i class="bi bi-chat-square-text"></i></span>
            </div>
        </article>

        <article class="dashboard-stat-card">
            <p class="stat-card-label">Total Users</p>
            <div class="stat-card-bottom">
                <strong class="stat-card-value">{{ $totalUsers ?? 0 }}</strong>
                <span class="stat-card-icon-bi"><i class="bi bi-people"></i></span>
            </div>
        </article>
    </div>

    <section class="dashboard-spotlight-card">
        <div class="spotlight-copy">
            <span class="dashboard-eyebrow">Monthly Spotlight</span>
            <h2>Feedback Summary</h2>
            <p class="spotlight-note">Below are the latest user feedback entries. Click "Read the report" to open the full feedback page.</p>
            <a href="{{ route('admin.feedback') }}" class="btn-spotlight">Read the report</a>
        </div>

        <div class="spotlight-visual">
            <div class="spotlight-image-placeholder feedback-list">
                @if(isset($feedbacks) && $feedbacks->count() > 0)
                    <div class="feedback-list-scroll">
                        @foreach($feedbacks as $fb)
                            <div class="feedback-list-item">
                                <div class="feedback-meta">
                                    <strong class="feedback-author">{{ $fb['name'] ?? 'Anonymous' }}</strong>
                                    @if($fb['created_at'])
                                        <span class="feedback-date">{{ \Carbon\Carbon::parse($fb['created_at'])->format('M d, Y') }}</span>
                                    @endif
                                </div>
                                <div class="feedback-body">
                                    {{ \Illuminate\Support\Str::limit($fb['text'] ?? '-', 220, '...') }}
                                </div>
                                @if(isset($fb['rating']) && $fb['rating'])
                                    <div class="feedback-rating">
                                        <span class="rating-badge">★ {{ $fb['rating'] }}/5</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-feedback">
                        <p style="margin: 0; color: #8B6C50; font-size: 13px;">No feedback available yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
/* ===== DASHBOARD PAGE STYLES ===== */

.dashboard-page {
  padding: 46px 46px 80px;
  max-width: 1440px;
  margin: 0 auto;
}

.dashboard-header {
  display: grid;
  grid-template-columns: minmax(0, 1fr) auto;
  gap: 32px;
  align-items: start;
  margin-bottom: 34px;
}

.dashboard-eyebrow {
  display: inline-block;
  margin-bottom: 8px;
  font-family: 'Jost', sans-serif;
  font-size: 12px;
  letter-spacing: 0.24em;
  text-transform: uppercase;
  color: #7A5030;
  font-weight: 700;
}

.dashboard-title {
  margin: 0 0 20px 0;
  font-family: 'Playfair Display', serif;
  font-size: clamp(3.6rem, 4.5vw, 4.6rem);
  line-height: 0.95;
  color: var(--brown-dark);
}

.dashboard-subtitle {
  margin: 0;
  font-family: 'Jost', sans-serif;
  font-size: 14px;
  color: #7C5940;
  max-width: 760px;
  line-height: 1.8;
}

.dashboard-timecard {
  background: rgba(255, 255, 255, 0.92);
  border-radius: 32px;
  padding: 26px 28px;
  text-align: right;
  box-shadow: 0 24px 58px rgba(61, 35, 20, 0.12);
}

.timecard-label {
  display: block;
  font-size: 10px;
  letter-spacing: 0.28em;
  text-transform: uppercase;
  color: #A17A55;
  margin-bottom: 12px;
}

.dashboard-timecard strong {
  display: block;
  font-size: 1.25rem;
  color: var(--brown-dark);
}

.dashboard-metrics-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 16px;
  margin-bottom: 24px;
}

.dashboard-stat-card {
  background: rgba(255, 255, 255, 0.96);
  border-radius: 32px;
  padding: 24px 20px;
  box-shadow: 0 28px 64px rgba(61, 35, 20, 0.08);
  min-height: 120px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.stat-card-icon {
  width: 36px;
  height: 36px;
  display: block;
  object-fit: contain;
  flex-shrink: 0;
  opacity: 0.9;
}

.stat-card-icon-bi {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 12px;
  background: rgba(60, 32, 16, 0.07);
  color: var(--brown-dark);
  flex-shrink: 0;
}
.stat-card-icon-bi i {
  font-size: 20px;
  opacity: 0.85;
}

.stat-card-label {
  margin: 0 0 12px;
  font-size: 10px;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: #8b6c50;
  font-weight: 700;
  line-height: 1.2;
}

.stat-card-value {
  display: block;
  font-size: 2rem;
  line-height: 1;
  color: var(--brown-dark);
  margin-bottom: 0;
  font-weight: 700;
}

.stat-card-bottom {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 12px;
  padding: 0 8px;
}

/* Feedback list inside spotlight */
.feedback-list-scroll {
  max-height: 300px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 14px;
  padding: 16px;
}

.feedback-list-item {
  background: rgba(255, 255, 255, 0.96);
  border-radius: 18px;
  padding: 18px 16px;
  box-shadow: 0 12px 32px rgba(61, 35, 20, 0.08);
  transition: box-shadow 0.2s ease;
}

.feedback-list-item:hover {
  box-shadow: 0 16px 40px rgba(61, 35, 20, 0.12);
}

.feedback-meta {
  display: flex;
  justify-content: space-between;
  gap: 12px;
  align-items: baseline;
  margin-bottom: 10px;
}

.feedback-author {
  color: var(--brown-dark);
  font-size: 13px;
  font-weight: 600;
}

.feedback-date {
  color: #A17A55;
  font-size: 12px;
  white-space: nowrap;
}

.feedback-body {
  color: #5E402C;
  font-size: 13px;
  line-height: 1.5;
  margin: 0;
}

.feedback-rating {
  margin-top: 8px;
}

.dashboard-page .rating-badge {
  display: inline-block;
  background: #FFF2E6;
  color: #7A5030;
  font-size: 11px;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 999px;
  letter-spacing: 0.06em;
}

.no-feedback {
  color: #8B6C50;
  padding: 48px 28px;
  text-align: center;
  font-size: 13px;
  line-height: 1.6;
}

.dashboard-spotlight-card {
  background: rgba(255, 255, 255, 0.92);
  border-radius: 42px;
  padding: 42px;
  display: grid;
  grid-template-columns: minmax(0, 1.2fr) minmax(320px, 1fr);
  gap: 28px;
  box-shadow: 0 38px 88px rgba(61, 35, 20, 0.12);
  align-items: center;
}

.spotlight-copy h2 {
  margin: 0 0 26px;
  font-family: 'Playfair Display', serif;
  font-size: clamp(2.4rem, 3.2vw, 3.2rem);
  line-height: 0.96;
  color: var(--brown-dark);
}

.btn-spotlight {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 190px;
  padding: 18px 28px;
  border-radius: 999px;
  background: var(--brown-dark);
  color: var(--white);
  font-family: 'Jost', sans-serif;
  font-size: 12px;
  letter-spacing: 0.26em;
  text-transform: uppercase;
  text-decoration: none;
  transition: background 0.2s ease;
}

.btn-spotlight:hover {
  background: var(--brown-mid);
}

.spotlight-visual {
  display: flex;
  justify-content: flex-end;
}

.spotlight-note {
  margin: 0 0 20px;
  font-size: 13px;
  color: #7C5940;
  line-height: 1.6;
}

.spotlight-image-placeholder {
  width: 100%;
  min-height: 340px;
  border-radius: 32px;
  background: linear-gradient(180deg, #F9EFE7 0%, #EFD9C2 100%);
  position: relative;
  overflow: hidden;
  box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.5);
}

.spotlight-image-circle {
  position: absolute;
  width: 180px;
  height: 180px;
  border-radius: 999px;
  background: rgba(196, 160, 122, 0.24);
  top: 20px;
  right: -32px;
}

.spotlight-image-block {
  position: absolute;
  width: 56%;
  height: 76%;
  background: rgba(124, 90, 60, 0.12);
  bottom: 24px;
  left: 18px;
  border-radius: 28px;
}

@media (max-width: 1100px) {
  .dashboard-metrics-grid,
  .dashboard-spotlight-card {
    grid-template-columns: 1fr;
  }

  .dashboard-header {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 720px) {
  .dashboard-page {
    padding: 28px 20px 60px;
  }

  .dashboard-timecard {
    text-align: left;
  }
}
</style>
@endpush

@push('scripts')
@endpush