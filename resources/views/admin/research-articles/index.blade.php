@extends('layouts.admin')

@section('title', 'Research Articles - Pivot Admin Dashboard')

@section('content')
<ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li><i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i></li>
    <li style="color: var(--text-heading); font-weight: 600;">Research Articles</li>
</ul>

@if(session('success'))
<div style="background: var(--success-bg); color: var(--success); padding: 0.875rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; border: 1px solid var(--success);">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.125rem; font-weight: 700; color: var(--text-heading);">Research Articles</h3>
        <button type="button" onclick="openModal('create-article-modal')" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Article
        </button>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.research-articles.index') }}" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search summary or fun facts..." class="form-control" style="max-width: 320px;">
        <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-filter"></i> Filter</button>
    </form>

    <!-- Data Table -->
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 35%;">Summary</th>
                    <th style="width: 25%;">Fun Facts</th>
                    <th>Video Link</th>
                    <th>Document File</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-heading);">
                            {{-- Strip HTML tags and limit text length --}}
                            {!! Str::limit(strip_tags($article->summary), 80) !!}
                        </div>
                    </td>
                    <td>
                        <span style="color: var(--text-muted); font-size: 0.85rem;">
                            {{ $article->fun_facts ? Str::limit(strip_tags($article->fun_facts), 50) : 'N/A' }}
                        </span>
                    </td>
                    <td>
                        @if($article->video_link)
                            <a href="{{ $article->video_link }}" target="_blank" class="badge badge-info" style="text-decoration: none;">
                                <i class="fa-solid fa-video"></i> Watch
                            </a>
                        @else
                            <span style="color: var(--text-muted);">None</span>
                        @endif
                    </td>
                    <td>
                        @if($article->files)
                            <a href="{{ asset('storage/' . $article->files) }}" target="_blank" class="badge badge-success" style="text-decoration: none;">
                                <i class="fa-solid fa-file-pdf"></i> Download
                            </a>
                        @else
                            <span style="color: var(--text-muted);">No File</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div class="action-btn-group">
                            {{-- View Details Icon Button --}}
                            <button type="button" onclick="viewArticle({{ json_encode($article) }})" class="btn btn-secondary btn-sm" title="View Article Details">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            {{-- Edit Icon Button --}}
                            <button type="button" onclick="editArticle({{ json_encode($article) }})" class="btn btn-secondary btn-sm" title="Edit Article">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            {{-- Delete Icon Button --}}
                            <button type="button" onclick="confirmDelete({{ $article->id }})" class="btn btn-danger btn-sm" title="Delete Article">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">No research articles found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <span style="color: var(--text-muted); font-size: 0.8125rem;">
            Showing {{ $articles->firstItem() ?? 0 }} to {{ $articles->lastItem() ?? 0 }} of {{ $articles->total() }} entries
        </span>
        {{ $articles->appends(request()->query())->links('partials.pagination') }}
    </div>
</div>

<!-- VIEW ARTICLE DETAILS MODAL -->
<div class="modal-backdrop" id="view-article-modal">
    <div class="modal-dialog" style="max-width: 640px; border-radius: var(--radius-lg);">
        <div class="modal-header" style="border-bottom: 1px solid var(--border-color); padding: 1.25rem 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 38px; height: 38px; border-radius: var(--radius-md); background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 700; color: var(--text-heading); margin: 0;">Research Article Details</h3>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Overview & attachments</span>
                </div>
            </div>
            <button type="button" onclick="closeModal('view-article-modal')" style="background: none; border: none; cursor: pointer; color: var(--text-muted); font-size: 1.1rem;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="modal-body" style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem;">
            <!-- Summary Block -->
            <div style="background: var(--bg-main); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--primary); display: flex; align-items: center; gap: 0.375rem; margin-bottom: 0.5rem;">
                    <i class="fa-solid fa-align-left"></i> Summary
                </span>
                <div id="view-summary" style="color: var(--text-heading); line-height: 1.6; font-size: 0.875rem;"></div>
            </div>

            <!-- Fun Facts Block -->
            <div style="background: var(--bg-main); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--olive); display: flex; align-items: center; gap: 0.375rem; margin-bottom: 0.5rem;">
                    <i class="fa-solid fa-lightbulb"></i> Fun Facts
                </span>
                <div id="view-fun-facts" style="color: var(--text-body); line-height: 1.6; font-size: 0.875rem;"></div>
            </div>

            <!-- Attachments & Media Grid -->
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <!-- Video Link -->
                <div style="padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-surface);">
                    <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Video Resource</span>
                    <div id="view-video-link"></div>
                </div>

                <!-- Document Link -->
                <div style="padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background: var(--bg-surface);">
                    <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Document Attachment</span>
                    <div id="view-file-link"></div>
                </div>
            </div>
        </div>

        <div class="modal-footer" style="padding: 1rem 1.5rem; background: var(--bg-main); border-top: 1px solid var(--border-color);">
            <button type="button" onclick="closeModal('view-article-modal')" class="btn btn-secondary" style="padding: 0.5rem 1.25rem;">Close</button>
        </div>
    </div>
</div>

<!-- CREATE ARTICLE MODAL -->
<div class="modal-backdrop" id="create-article-modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>Add New Research Article</h3>
            <button type="button" onclick="closeModal('create-article-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.research-articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Summary *</label>
                    <textarea name="summary" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Fun Facts</label>
                    <textarea name="fun_facts" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Video URL Link</label>
                    <input type="url" name="video_link" class="form-control" placeholder="https://youtube.com/...">
                </div>
                <div class="form-group">
                    <label class="form-label">File Upload (PDF/Doc)</label>
                    <input type="file" name="files" class="form-control" accept=".pdf,.doc,.docx">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('create-article-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Article</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT ARTICLE MODAL -->
<div class="modal-backdrop" id="edit-article-modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>Edit Research Article</h3>
            <button type="button" onclick="closeModal('edit-article-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="edit-article-form" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Summary *</label>
                    <textarea name="summary" id="edit-summary" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Fun Facts</label>
                    <textarea name="fun_facts" id="edit-fun-facts" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Video URL Link</label>
                    <input type="url" name="video_link" id="edit-video-link" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">File Upload (Leave empty to keep existing)</label>
                    <input type="file" name="files" class="form-control" accept=".pdf,.doc,.docx">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('edit-article-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Article</button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div class="modal-backdrop" id="delete-modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>Confirm Delete</h3>
            <button type="button" onclick="closeModal('delete-modal')" style="background: none; border: none; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="delete-form" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <p>Are you sure you want to delete this research article record?</p>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('delete-modal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function viewArticle(article) {
        // Render Summary (Strip HTML tags for uniform display or keep clean text)
        let summaryText = article.summary ? article.summary.replace(/<\/?[^>]+(>|$)/g, "") : 'No summary provided.';
        let funFactsText = article.fun_facts ? article.fun_facts.replace(/<\/?[^>]+(>|$)/g, "") : 'No fun facts provided.';

        document.getElementById('view-summary').innerText = summaryText;
        document.getElementById('view-fun-facts').innerText = funFactsText;

        // Video Link Button
        if (article.video_link) {
            document.getElementById('view-video-link').innerHTML = `
                <a href="${article.video_link}" target="_blank" class="btn btn-secondary btn-sm" style="width: 100%; justify-content: center; gap: 0.5rem; color: var(--info); border-color: var(--border-color);">
                    <i class="fa-solid fa-circle-play"></i> Watch Video
                </a>`;
        } else {
            document.getElementById('view-video-link').innerHTML = `<span style="color: var(--text-muted); font-size: 0.8125rem;">None</span>`;
        }

        // File Attachment Button
        if (article.files) {
            document.getElementById('view-file-link').innerHTML = `
                <a href="/storage/${article.files}" target="_blank" class="btn btn-olive btn-sm" style="width: 100%; justify-content: center; gap: 0.5rem;">
                    <i class="fa-solid fa-file-arrow-down"></i> View Attachment
                </a>`;
        } else {
            document.getElementById('view-file-link').innerHTML = `<span style="color: var(--text-muted); font-size: 0.8125rem;">No File Attached</span>`;
        }

        openModal('view-article-modal');
    }

    function editArticle(article) {
        let updateUrl = "{{ route('admin.research-articles.update', ':id') }}".replace(':id', article.id);

        document.getElementById('edit-article-form').action = updateUrl;
        document.getElementById('edit-summary').value = article.summary ?? '';
        document.getElementById('edit-fun-facts').value = article.fun_facts ?? '';
        document.getElementById('edit-video-link').value = article.video_link ?? '';

        openModal('edit-article-modal');
    }

    function confirmDelete(id) {
        let deleteUrl = "{{ route('admin.research-articles.destroy', ':id') }}".replace(':id', id);
        document.getElementById('delete-form').action = deleteUrl;
        openModal('delete-modal');
    }
</script>
@endsection