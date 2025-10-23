@extends('admin.dashboard')

@section('title', 'Report Management')
@section('page-title', 'Report Management')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <div class="header-title">
            <h2>Report List</h2>
            <p>Manage all post reports</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="content-card">
        <div class="card-body">
            @if($reports->count() > 0)
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Reason</th>
                                <th>Reported By</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td>{{ $report->id }}</td>
                                    <td>{{ $report->reason }}</td>
                                    <td>{{ $report->user->name }}</td>
                                    <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" title="Delete"
                                                    data-bs-toggle="modal" data-bs-target="#deleteReportModal"
                                                    data-report-id="{{ $report->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-flag-alt empty-icon"></i> 
                    <h3>No Reports Found</h3>
                    <p>Reported posts will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal DELETE REPORT -->
<div class="modal fade" id="deleteReportModal" tabindex="-1" aria-labelledby="deleteReportLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteReportLabel"><i class="fas fa-trash me-2"></i>Confirm Deletion</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to <strong>delete this report</strong>? This action is <span class="text-danger">irreversible</span>.</p>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteReportForm" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Yes, delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('styles')
<style>
.admin-content {
    padding: 2rem;
}

.content-header {
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
}

.content-card {
    margin-bottom: 2rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    background: white;
}

.card-body {
    padding: 2rem;
}

.header-title h2 {
    font-size: 1.875rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.5rem;
}

.header-title p {
    font-size: 1.125rem;
    color: #6b7280;
    margin: 0;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.875rem 1.75rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    font-size: 0.95rem;
    gap: 0.5rem;
    min-height: 3rem;
}

.btn-sm {
    padding: 0.625rem 1.25rem;
    font-size: 0.875rem;
    min-height: 2.5rem;
    gap: 0.375rem;
}

.btn-info { background-color: #06b6d4; color: white; }
.btn-info:hover { background-color: #0891b2; transform: translateY(-1px); }

.btn-danger { background-color: #ef4444; color: white; }
.btn-danger:hover { background-color: #dc2626; transform: translateY(-1px); }

.action-buttons {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

/* Table styles */
.admin-table {
    border-collapse: collapse;
    width: 100%;
    border-radius: 0.75rem;
    overflow: hidden;
}

.admin-table thead th {
    border-bottom: 2px solid #e5e7eb;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background: #f9fafb;
    padding: 1.25rem 1.5rem;
    color: #374151;
    text-align: left; 
}

.admin-table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background-color 0.2s ease;
}

.admin-table tbody tr:hover {
    background-color: #f8fafc;
}

.admin-table tbody td {
    padding: 1.5rem 1.5rem;
    vertical-align: middle;
    color: #374151;
}

.empty-state {
    text-align: center;
    padding: 5rem 2rem;
}

.empty-icon {
    color: #9ca3af;
    font-size: 5rem;
    margin-bottom: 2rem;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #374151;
    font-weight: 600;
}

.empty-state p {
    font-size: 1.125rem;
    margin-bottom: 2.5rem;
    color: #6b7280;
    line-height: 1.6;
}

/* Modal styles */
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
    const deleteReportModal = document.getElementById('deleteReportModal');
    
    if (deleteReportModal) {
        deleteReportModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const reportId = button.getAttribute('data-report-id');
            const form = document.getElementById('deleteReportForm');
            
            // CORRECTION : Utiliser la bonne syntaxe pour construire l'URL
            form.action = "{{ route('admin.reports.destroy', ':id') }}".replace(':id', reportId);
        });
    }
});
</script>
@endsection