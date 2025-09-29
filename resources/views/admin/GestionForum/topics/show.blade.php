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