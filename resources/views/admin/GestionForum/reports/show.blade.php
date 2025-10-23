@extends('admin.dashboard')

@section('title', 'Report Details')
@section('page-title', 'Report Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-10">
            <div class="card shadow-sm report-details-card">
                
                {{-- Card Header: Titre et bouton de retour --}}
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="fas fa-flag text-primary me-2"></i>Report Details #{{ $report->id }}
                            </h5>
                            <p class="text-muted small mb-0">Complete information about the report</p>
                        </div>
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm mt-3 mt-md-0">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>

                {{-- Card Body: Informations détaillées --}}
                <div class="card-body p-4 p-md-5">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row g-5">
                        {{-- Colonne 1: Informations sur le signalement --}}
                        <div class="col-lg-6 border-end-lg">
                            <h6 class="info-header"><i class="fas fa-info-circle text-primary me-2"></i>Report Information</h6>
                            <dl class="dl-horizontal">
                                <dt>Report ID</dt>
                                <dd><span class="badge text-bg-secondary fw-normal">#{{ $report->id }}</span></dd>

                                <dt>Reason</dt>
                                <dd><span class="badge text-bg-light border fw-normal">{{ $report->reason }}</span></dd>

                                <dt>Reported by</dt>
                                <dd>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-primary text-white rounded-circle">
                                                {{ strtoupper(substr($report->user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $report->user->name }}</div>
                                            <small class="text-muted">User</small>
                                        </div>
                                    </div>
                                </dd>

                                <dt>Date</dt>
                                <dd>{{ $report->created_at->format('d/m/Y \a\t H:i') }}</dd>

                                <dt>Status</dt>
                                <dd>
                                    <span class="badge text-bg-warning">
                                        <i class="fas fa-hourglass-half me-1"></i> Pending
                                    </span>
                                </dd>
                            </dl>
                        </div>

                        {{-- Colonne 2: Informations sur le post signalé --}}
                        <div class="col-lg-6">
                            <h6 class="info-header"><i class="fas fa-file-alt text-primary me-2"></i>Reported Post</h6>
                            @if($report->post)
                                <dl class="dl-horizontal">
                                    <dt>Post Author</dt>
                                    <dd>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title bg-secondary-subtle text-secondary-emphasis rounded-circle">
                                                    {{ strtoupper(substr($report->post->user->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $report->post->user->name }}</div>
                                                <small class="text-muted">Author</small>
                                            </div>
                                        </div>
                                    </dd>
                                
                                    <dt>Topic</dt>
                                    <dd><span class="badge text-bg-info fw-normal">{{ $report->post->topic->title }}</span></dd>

                                    <dt>Post Date</dt>
                                    <dd>{{ $report->post->created_at->format('d/m/Y \a\t H:i') }}</dd>
                                
                                    <dt>Content</dt>
                                    <dd>
                                        <div class="post-content">
                                            <p class="mb-0">{{ $report->post->content_P }}</p>
                                        </div>
                                    </dd>
                                </dl>
                            @else
                                <div class="text-center py-4 text-muted d-flex flex-column align-items-center justify-content-center h-100">
                                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                                    <p class="mb-0">This post has been deleted.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Card Footer: Actions de l'administrateur (logique simplifiée) --}}
                <div class="card-footer bg-light p-3">
                    <div class="d-flex justify-content-end align-items-center flex-wrap gap-2">
                    
                        {{-- Action si le signalement est VALIDE (supprime le post et résout le signalement) --}}
                        @if($report->post)
                            <form action="{{ route('admin.reports.deletePost', $report) }}" method="POST" id="deletePostForm">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deletePostModal">
                                    <i class="fas fa-trash me-1"></i> Delete Post
                                </button>
                            </form>
                        @endif

                        {{-- Action si le signalement est INVALIDE (ignore le signalement, le post reste) --}}
                        <form action="{{ route('admin.reports.ignore', $report) }}" method="POST" id="dismissReportForm">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#dismissReportModal">
                                <i class="fas fa-times me-1"></i> Dismiss Report
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Modal DELETE -->
<div class="modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deletePostLabel"><i class="fas fa-trash me-2"></i>Confirm Deletion</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to <strong>delete this post</strong>? This action is <span class="text-danger">irreversible</span> and will resolve the report.</p>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Yes, delete</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal DISMISS -->
<div class="modal fade" id="dismissReportModal" tabindex="-1" aria-labelledby="dismissReportLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title" id="dismissReportLabel"><i class="fas fa-ban me-2"></i>Dismiss Report</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Do you want to <strong>dismiss this report</strong>? The post will <span class="text-success">not be affected</span>.</p>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmDismissBtn">Yes, dismiss</button>
      </div>
    </div>
  </div>
</div>


{{-- CSS pour le style de la page --}}
<style>
    :root {
        --bs-border-color: #e5e7eb;
        --card-bg: #ffffff;
        --card-body-bg: #f9fafb;
        --text-color-primary: #1f2937;
        --text-color-secondary: #6b7280;
    }

    body {
        background-color: #f3f4f6; /* Fond général plus doux */
    }

    .report-details-card {
        border: 1px solid var(--bs-border-color);
        border-radius: 0.75rem;
        overflow: hidden;
    }
    
    .card-title {
        color: var(--text-color-primary);
        font-weight: 600;
    }

    .info-header {
        font-weight: 600;
        color: var(--text-color-primary);
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--bs-border-color);
    }
    
    .dl-horizontal dt {
        font-weight: 500;
        color: var(--text-color-secondary);
        margin-bottom: 0.35rem;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .dl-horizontal dd {
        color: var(--text-color-primary);
        margin-bottom: 1.25rem;
        padding-left: 0;
    }
    .dl-horizontal dd:last-of-type {
        margin-bottom: 0;
    }

    @media (min-width: 992px) {
        .border-end-lg {
            border-right: 1px solid var(--bs-border-color);
        }
    }

    .avatar-sm {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
    }

    .avatar-title {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-weight: 600;
        font-size: 1rem;
    }

    .post-content {
        background-color: #f8fafc;
        border: 1px solid var(--bs-border-color);
        border-radius: 0.5rem;
        padding: 1rem;
        color: #374151;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    
    .badge.text-bg-light {
        background-color: #f3f4f6 !important;
        color: #374151 !important;
        border-color: #d1d5db !important;
    }
    .badge.text-bg-warning {
        background-color: #fef3c7 !important;
        color: #92400e !important;
    }
    .badge.text-bg-info {
        background-color: #dbeafe !important;
        color: #1e40af !important;
    }
    .modal-content {
    border-radius: 0.75rem;
    overflow: hidden;
    }
    .modal-header {
        border-bottom: none;
    }
    .modal-footer {
        border-top: none;
    }
</style>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteBtn = document.getElementById('confirmDeleteBtn');
    const dismissBtn = document.getElementById('confirmDismissBtn');

    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            const form = document.getElementById('deletePostForm');
            if(form) form.submit();
        });
    }

    if (dismissBtn) {
        dismissBtn.addEventListener('click', function() {
            const form = document.getElementById('dismissReportForm');
            if(form) form.submit();
        });
    }
});
</script>
@endsection
