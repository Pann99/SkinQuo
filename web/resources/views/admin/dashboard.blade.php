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
                <img src="{{ asset('images/product.png') }}" alt="Products" class="stat-card-icon">
            </div>
        </article>

        <article class="dashboard-stat-card">
            <p class="stat-card-label">Skin Guide Articles</p>
            <div class="stat-card-bottom">
                <strong class="stat-card-value">{{ $totalArticles ?? 0 }}</strong>
                <img src="{{ asset('images/skinguide.png') }}" alt="Skin Guide" class="stat-card-icon">
            </div>
        </article>

        <article class="dashboard-stat-card">
            <p class="stat-card-label">Pending Feedback</p>
            <div class="stat-card-bottom">
                <strong class="stat-card-value">{{ $totalFeedback ?? 0 }}</strong>
                <img src="{{ asset('images/feedback.png') }}" alt="Feedback" class="stat-card-icon">
            </div>
        </article>

        <article class="dashboard-stat-card">
            <p class="stat-card-label">Total Users</p>
            <div class="stat-card-bottom">
                <strong class="stat-card-value">{{ $totalUsers ?? 0 }}</strong>
                <img src="{{ asset('images/users.png') }}" alt="Users" class="stat-card-icon">
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
