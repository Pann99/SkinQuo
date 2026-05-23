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
            <div class="stat-card-icon">🛍️</div>
            <p class="stat-card-label">Total Products</p>
            <strong class="stat-card-value">0</strong>
            <p class="stat-card-meta">Active products in catalog</p>
        </article>

        <article class="dashboard-stat-card">
            <div class="stat-card-icon">📚</div>
            <p class="stat-card-label">Skin Guide Articles</p>
            <strong class="stat-card-value">0</strong>
            <p class="stat-card-meta">Published articles</p>
        </article>

        <article class="dashboard-stat-card">
            <div class="stat-card-icon">💬</div>
            <p class="stat-card-label">Pending Feedback</p>
            <strong class="stat-card-value">0</strong>
            <p class="stat-card-meta">Awaiting review</p>
        </article>

        <article class="dashboard-stat-card">
            <div class="stat-card-icon">👥</div>
            <p class="stat-card-label">Total Users</p>
            <strong class="stat-card-value">0</strong>
            <p class="stat-card-meta">Registered users</p>
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
                @if(isset($feedbacks) && $feedbacks->count())
                    <div class="feedback-list-scroll">
                        @foreach($feedbacks as $fb)
                            <div class="feedback-list-item">
                                <div class="feedback-meta">
                                    <strong class="feedback-author">{{ $fb->name ?? $fb->user_name ?? 'Anonymous' }}</strong>
                                    <span class="feedback-date">{{ isset($fb->created_at) ? $fb->created_at->format('M d, Y') : '' }}</span>
                                </div>
                                <div class="feedback-body">{{ \Illuminate\Support\Str::limit($fb->message ?? $fb->feedback ?? $fb->content ?? '-', 220) }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-feedback">No feedback available yet.</div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
