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