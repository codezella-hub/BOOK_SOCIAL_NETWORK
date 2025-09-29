@extends('admin.dashboard')

@section('title', $topic->title)
@section('page-title', $topic->title)

@section('content')
<div class="admin-content">
    <div class="content-header">
        <div class="header-title">
            <h2>Topic details</h2>
            <p>Complete informations on the topic</p>
        </div>
        <div class="header-actions ">
            <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i>
                Edit
            </a>
            <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Return
            </a>
        </div>
    </div>

    <div class="content-card">
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Title :</label>
                    <span class="detail-value">{{ $topic->title }}</span>
                </div>
                
                <div class="detail-item">
                    <label>Description :</label>
                    <span class="detail-value">
                        {{ $topic->description ?? 'Aucune description' }}
                    </span>
                </div>
                
                <div class="detail-item">
                    <label>Creation Date :</label>
                    <span class="detail-value">{{ $topic->T_created_at->format('d/m/Y H:i') }}</span>
                </div>
                
                <div class="detail-item">
                    <label>Last modification :</label>
                    <span class="detail-value">{{ $topic->updated_at->format('d/m/Y à H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions de suppression -->
    <div class="content-card danger-zone">
        <div class="card-header">
            <h3>Danger zone</h3>
        </div>
        <div class="card-body">
            <p>Deleting a topic is irreversible. All associated data will be lost.</p>
            <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce topic ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i>
                    Delete this topic
                </button>
            </form>
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
    /* Styles spécifiques à la vue détaillée */
    .detail-grid {
        display: grid;
        gap: 1rem;
    }

    .detail-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .detail-value {
        font-size: 1rem;
        color: #111827;
        line-height: 1.4;
        font-weight: 500;
    }

    .danger-zone {
        border: 1px solid #fecaca;
        background: #fef2f2;
        border-radius: 0.5rem;
    }

    .danger-zone .card-body {
        padding: 1.25rem;
    }

    .danger-zone p {
        margin-bottom: 1rem;
        line-height: 1.5;
        font-size: 0.9rem;
        color: #7f1d1d;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .header-actions {
            justify-content: flex-start;
        }
    }
</style>

@endsection