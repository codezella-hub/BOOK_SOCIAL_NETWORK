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
                    
                    {{-- Solution temporaire : afficher les boutons pour tous les posts de l'utilisateur 1 --}}
                    @if($post->created_by == 1)
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