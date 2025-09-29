@extends('layouts.user-layout')

@section('title', 'Forum - Posts')
@section('content')
<div class="forum-container">
    <div class="forum-header">
        <h1>Forum Discussions</h1>
        <p>Share your thoughts and discuss with the community</p>
        <a href="{{ route('user.posts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            New Post
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="posts-list">
        @forelse($posts as $post)
            <div class="post-card">
                <div class="post-header">
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-details">
                            <h4>{{ $post->user->name ?? 'Utilisateur' }}</h4>
                            <span class="post-time">{{ $post->P_created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="post-topic">
                        <span class="topic-badge">{{ $post->topic->title }}</span>
                    </div>
                </div>
                
                <div class="post-content">
                    <p>{{ $post->content_P }}</p>
                </div>

                <div class="post-actions">
                    <a href="{{ route('user.posts.show', $post) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i>
                        Show
                    </a>
                    
                   
                    @if($post->created_by == auth()->user()->id)
                        <a href="{{ route('user.posts.edit', $post) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                            Edit
                        </a>
                        <form action="{{ route('user.posts.destroy', $post) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce post?')">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-comments empty-icon"></i>
                <h3>No posts found</h3>
                <p>Be the first to share your thoughts!</p>
                <a href="{{ route('user.posts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create a post
                </a>
            </div>
        @endforelse
    </div>
</div>


@endsection

@section('styles')
<style>
    /* === STYLES GLOBAUX USER === */
    .forum-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .forum-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .forum-header h1 {
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .forum-header p {
        color: #718096;
        margin-bottom: 1.5rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.375rem;
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
    }

    .btn-info {
        background: #06b6d4;
        color: white;
    }

    .btn-info:hover {
        background: #0891b2;
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .alert {
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    /* === STYLES SPÉCIFIQUES LISTE POSTS === */
    .posts-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .post-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
    }

    .post-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }


    .user-details h4 {
        margin: 0;
        color: #2d3748;
        font-size: 1rem;
    }

    .post-time {
        color: #718096;
        font-size: 0.875rem;
    }

    .topic-badge {
        background: #e2e8f0;
        color: #4a5568;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .post-content {
        margin-bottom: 1rem;
    }

    .post-content p {
        color: #4a5568;
        line-height: 1.6;
        margin: 0;
    }

    .post-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-icon {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: #4a5568;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #718096;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .forum-container {
            margin: 1rem auto;
            padding: 0 0.5rem;
        }
        
        .post-header {
            flex-direction: column;
            gap: 1rem;
        }
        
        .post-actions {
            justify-content: center;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@endsection