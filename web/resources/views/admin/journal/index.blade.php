@extends('layouts.admin.admin')
@section('title', 'Editorial Journal - The Sanctuary')
@section('content')

<div class="journal-page">

  <div class="journal-page-header">
    <div class="journal-title-block">
      <h1>Editorial Journal</h1>
      <p>Manage your digital atelier’s narrative. From artisanal skincare rituals to scientific breakthroughs, curate your stories with precision.</p>
    </div>
    <div class="journal-page-actions">
      <button type="button" class="btn-secondary-admin">
        <i class="bi bi-funnel"></i>
        Filter
      </button>
      <a href="{{ route('admin.journal.create') }}" class="btn-primary-admin">
        <i class="bi bi-pencil-square"></i>
        Create Article
      </a>
    </div>
  </div>

  <div class="journal-stats-grid">
    <article class="journal-stat-card stat-card-light">
      <span class="stat-label">Total Circulation</span>
      <strong>128 Articles</strong>
      <p>+12% from last quarter</p>
    </article>

    <article class="journal-stat-card journal-stat-card-dark">
      <span class="stat-label">Most Read Category</span>
      <strong>Molecular Rituals</strong>
    </article>

    <article class="journal-stat-card stat-card-light">
      <span class="stat-label">Draft Capacity</span>
      <strong>85% Ready</strong>
      <div class="journal-progress">
        <div class="journal-progress-fill" style="width: 85%;"></div>
      </div>
    </article>
  </div>

  <section class="journal-card">
    <div class="journal-card-header">
      <h2>Content Repository</h2>
      <div class="journal-card-tools">
        <button type="button" class="icon-btn" aria-label="Grid view"><i class="bi bi-grid-3x3-gap"></i></button>
        <button type="button" class="icon-btn" aria-label="List view"><i class="bi bi-list"></i></button>
      </div>
    </div>

    <div class="journal-table-wrapper">
      <table class="journal-table">
        <thead>
          <tr>
            <th>Article</th>
            <th class="text-center">Category</th>
            <th class="text-center">Status</th>
            <th class="text-center">Created</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div class="author-cell">
                <div class="author-avatar">EV</div>
                <div>
                  <div class="article-title">The Alchemy of Ceramides</div>
                  <div class="author-meta">By Dr. Elena Vance • 12 min read</div>
                </div>
              </div>
            </td>
            <td class="text-center"><span class="journal-pill status-pill">Skin Science</span></td>
            <td class="text-center"><span class="journal-status"><span class="status-dot published"></span>Published</span></td>
            <td class="text-center">Oct 12, 2023</td>
            <td class="text-center"><a href="{{ route('admin.journal.edit', 1) }}" class="action-link">Edit</a></td>
          </tr>

          <tr>
            <td>
              <div class="author-cell">
                <div class="author-avatar">MT</div>
                <div>
                  <div class="article-title">Wildharvested Botanicals: A Guide</div>
                  <div class="author-meta">By Marcus Thorn • 8 min read</div>
                </div>
              </div>
            </td>
            <td class="text-center"><span class="journal-pill status-draft">Ingredients</span></td>
            <td class="text-center"><span class="journal-status"><span class="status-dot draft"></span>Draft</span></td>
            <td class="text-center">Oct 14, 2023</td>
            <td class="text-center"><a href="{{ route('admin.journal.edit', 2) }}" class="action-link">Edit</a></td>
          </tr>

          <tr>
            <td>
              <div class="author-cell">
                <div class="author-avatar">SC</div>
                <div>
                  <div class="article-title">The Evening Unwinding Ritual</div>
                  <div class="author-meta">By Sarah Chen • 15 min read</div>
                </div>
              </div>
            </td>
            <td class="text-center"><span class="journal-pill status-review">Lifestyle</span></td>
            <td class="text-center"><span class="journal-status"><span class="status-dot published"></span>Published</span></td>
            <td class="text-center">Oct 16, 2023</td>
            <td class="text-center"><a href="{{ route('admin.journal.edit', 3) }}" class="action-link">Edit</a></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="journal-card-footer">
      <span>Showing 1 to 10 of 128 articles</span>
      <nav class="pagination">
        <button class="page-btn">←</button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">3</button>
        <button class="page-btn">→</button>
      </nav>
    </div>
  </section>
</div>

@endsection
