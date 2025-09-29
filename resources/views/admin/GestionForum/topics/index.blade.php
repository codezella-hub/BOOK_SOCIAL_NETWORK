@extends('admin.dashboard')

@section('title', 'Topic Management')
@section('page-title', 'Topic Management')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <div class="header-title">
            <h2>List of Topics</h2>
            <p>Manage all forum topics</p>
        </div>
        <a href="{{ route('admin.topics.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            New Topic
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="content-card">
        <div class="card-body">
            @if($topics->count() > 0)
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Creation Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topics as $topic)
                                <tr>
                                    <td>
                                        <strong><div class="font-semibold text-gray-900 text-base"> </div>{{ $topic->title }}</strong>
                                    </td>
                                    <td>
                                        
                                        {{ $topic->description ? Str::limit($topic->description, 80) : 'Aucune description' }}
                                    </td>
                                    <td>
                                        {{ $topic->T_created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.topics.show', $topic) }}" class="btn btn-info btn-sm" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-warning btn-sm" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce topic ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-comments empty-icon"></i>
                    <h3>Aucun topic trouvé</h3>
                    <p>Commencez par créer votre premier topic</p>
                    <a href="{{ route('admin.topics.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Créer un topic
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
      /* Styles GLOBAUX admin seulement */
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
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        background: white;
    }

    .card-body {
        padding: 2rem;
    }

    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .card-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
        margin: 0;
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

    /* Boutons de base */
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

    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-1px);
    }

    .btn-warning {
        background-color: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background-color: #d97706;
        transform: translateY(-1px);
    }

    .btn-danger {
        background-color: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background-color: #dc2626;
        transform: translateY(-1px);
    }

    .btn-info {
        background-color: #06b6d4;
        color: white;
    }

    .btn-info:hover {
        background-color: #0891b2;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .admin-content {
            padding: 1rem;
        }
        
        .content-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
    /* Styles spécifiques à la liste */
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
    }

    .admin-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.2s ease;
    }

    .admin-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .admin-table tbody tr:last-child {
        border-bottom: none;
    }

    .admin-table tbody td {
        padding: 1.5rem 1.5rem;
        vertical-align: top;
        color: #374151;
    }

    .admin-table td strong {
        font-size: 1.125rem;
        color: #111827;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
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

    .alert {
        padding: 1.5rem;
        border-radius: 0.75rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border: 1px solid;
    }

    .alert-success {
        background-color: #f0fdf4;
        color: #166534;
        border-color: #bbf7d0;
    }

    .alert .close {
        margin-left: auto;
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
        transition: opacity 0.2s;
    }

    .alert .close:hover {
        opacity: 1;
    }

    @media (max-width: 768px) {
        .admin-table tbody td,
        .admin-table thead th {
            padding: 1rem;
        }
        
        .action-buttons {
            justify-content: center;
        }
    }
</style>

@endsection