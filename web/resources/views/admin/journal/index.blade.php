@extends('layouts.admin.admin')
@section('title', 'Editorial Journal - The Sanctuary')
@section('content')

<div class="journal-page">

  <div class="journal-page-header">
    <div class="journal-title-block">
      <h1>Editorial Journal</h1>
      <p>Manage your digital atelier’s narrative. From artisanal skincare rituals to scientific breakthroughs, curate your stories with precision.</p>
    </div>
  </div>

  <div class="journal-stats-grid">
    <article class="journal-stat-card stat-card-light">
      <span class="stat-label">Total Circulation</span>
      <strong>128 Articles</strong>
      <p><i class="bi bi-graph-up-arrow" style="margin-right:4px;"></i>+12% from last quarter</p>
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
    <div class="journal-card-header" style="align-items: center;">
      <form class="journal-search-row" action="#" method="GET" style="flex: 1; margin-bottom: 0; max-width: 560px;">
        <label class="search-wrapper" style="width: 100%;">
          <i class="bi bi-search"></i>
          <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari artikel atau penulis..." aria-label="Cari artikel atau penulis" />
        </label>
      </form>
      <a href="{{ route('admin.journal.create') }}" class="btn-primary-admin" style="flex-shrink: 0;">
        <i class="bi bi-pencil-square"></i>
        Create Article
      </a>
    </div>

    <div class="journal-table-wrapper">
      <table class="journal-table">
        <thead>
          <tr>
            <th>Article Details</th>
            <th class="text-center">Category</th>
            <th class="text-center">Status</th>
            <th class="text-center">Date Created</th>
            <th class="text-center">Update Date</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div class="article-title">The Alchemy of Ceramides</div>
              <div class="author-meta">By Dr. Elena Vance • 12 min read</div>
            </td>
            <td class="text-center"><span class="journal-pill status-pill">Skin Science</span></td>
            <td class="text-center"><span class="journal-status"><span class="status-dot published"></span>Published</span></td>
            <td class="text-center">Oct 12, 2023</td>
            <td class="text-center">Oct 12, 2023</td>
            <td class="text-center">
              <div class="action-buttons">
                <a href="{{ route('admin.journal.edit', 1) }}" class="action-btn action-edit" aria-label="Edit article"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('admin.journal.destroy', 1) }}" method="POST" class="action-form delete-form" onsubmit="return confirmDelete(event, this)">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="action-btn action-delete" aria-label="Delete article"><i class="bi bi-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="article-title">Wildharvested Botanicals: A Guide</div>
              <div class="author-meta">By Marcus Thorn • 8 min read</div>
            </td>
            <td class="text-center"><span class="journal-pill status-draft">Ingredients</span></td>
            <td class="text-center"><span class="journal-status"><span class="status-dot draft"></span>Draft</span></td>
            <td class="text-center">Oct 14, 2023</td>
            <td class="text-center">Oct 14, 2023</td>
            <td class="text-center">
              <div class="action-buttons">
                <a href="{{ route('admin.journal.edit', 2) }}" class="action-btn action-edit" aria-label="Edit article"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('admin.journal.destroy', 2) }}" method="POST" class="action-form delete-form" onsubmit="return confirmDelete(event, this)">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="action-btn action-delete" aria-label="Delete article"><i class="bi bi-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="article-title">The Evening Unwinding Ritual</div>
              <div class="author-meta">By Sarah Chen • 15 min read</div>
            </td>
            <td class="text-center"><span class="journal-pill status-review">Lifestyle</span></td>
            <td class="text-center"><span class="journal-status"><span class="status-dot published"></span>Published</span></td>
            <td class="text-center">Oct 16, 2023</td>
            <td class="text-center">Oct 16, 2023</td>
            <td class="text-center">
              <div class="action-buttons">
                <a href="{{ route('admin.journal.edit', 3) }}" class="action-btn action-edit" aria-label="Edit article"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('admin.journal.destroy', 3) }}" method="POST" class="action-form delete-form" onsubmit="return confirmDelete(event, this)">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="action-btn action-delete" aria-label="Delete article"><i class="bi bi-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="journal-card-footer">
      <span>Showing 1 to 10 of 128 articles</span>
      <nav class="pagination">
        <button class="page-btn" disabled>‹</button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">3</button>
        <button class="page-btn">›</button>
      </nav>
    </div>
  </section>
</div>

<!-- Modal Konfirmasi Hapus Custom Premium -->
<div id="delete-confirm-modal" class="feedback-modal hidden" role="dialog" aria-modal="true" style="backdrop-filter: blur(4px);">
  <div class="feedback-modal-backdrop" onclick="closeDeleteModal()"></div>
  <div class="feedback-modal-card" style="max-width: 440px; padding: 40px; text-align: center; border-radius: 28px; background: #FFFFAF; border: 1px solid rgba(124, 90, 60, 0.15); box-shadow: 0 30px 80px rgba(50,30,14,0.25);">
    <div style="width: 72px; height: 72px; border-radius: 50%; background: #FFF0E9; display: grid; place-items: center; margin: 0 auto 24px; color: #C04444; font-size: 32px; box-shadow: inset 0 4px 8px rgba(0,0,0,0.05);">
      <i class="bi bi-exclamation-circle-fill"></i>
    </div>
    <h2 style="font-family: 'Playfair Display', serif; font-size: 26px; font-weight: 700; color: var(--brown-dark); margin-bottom: 12px; font-style: italic;">Hapus Artikel ini?</h2>
    <p style="font-family: 'Jost', sans-serif; font-size: 14px; color: #7A5C43; margin-bottom: 32px; line-height: 1.6;">Tindakan ini bersifat permanen. Artikel yang dipilih akan dihapus secara menyeluruh dari data.</p>
    <div style="display: flex; gap: 14px; justify-content: center;">
      <button type="button" onclick="closeDeleteModal()" class="btn-secondary-admin" style="min-width: 120px; justify-content: center; padding: 12px 24px;">Batal</button>
      <button type="button" id="confirm-delete-btn" class="btn-primary-admin" style="background: #C04444; min-width: 120px; justify-content: center; border: none; padding: 12px 24px;">Hapus</button>
    </div>
  </div>
</div>

@push('scripts')
<script>
  let activeDeleteForm = null;

  function confirmDelete(event, form) {
    event.preventDefault();
    activeDeleteForm = form;
    document.getElementById('delete-confirm-modal').classList.remove('hidden');
    return false;
  }

  function closeDeleteModal() {
    document.getElementById('delete-confirm-modal').classList.add('hidden');
    activeDeleteForm = null;
  }

  document.getElementById('confirm-delete-btn').addEventListener('click', function() {
    if (activeDeleteForm) {
      activeDeleteForm.submit();
    }
  });
</script>
@endpush

@endsection
