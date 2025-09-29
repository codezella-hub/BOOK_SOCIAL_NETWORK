@extends('layouts.user-layout')

@section('title', $post->title)
@section('content')
<div class="forum-container">
    <div class="forum-header">
        <div class="header-content">
            <h1>Post Details</h1>
            <p>View the complete information of the post</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('user.posts.edit', $post) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i>
                Edit
            </a>
            <a href="{{ route('user.posts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="post-detail-container">
        <!-- Post Principal -->
        <div class="main-post">
            <div class="post-header">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-details">
                        <h3>{{ $post->user->name ?? 'Utilisateur' }}</h3>
                        <span class="post-time">
                            {{ $post->P_created_at->diffForHumans() }}
                            • in <span class="topic-badge">{{ $post->topic->title }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="post-content">
                <p>{{ $post->content_P }}</p>
            </div>

            <div class="post-stats">
                <div class="stat-item">
                    <i class="fas fa-heart"></i>
                    <span>{{ $post->likes->count() }} likes</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-comment"></i>
                    <span>{{ $post->comments->count() }} comments</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-eye"></i>
                    <span>0 views</span>
                </div>
            </div>
        </div>

        <!-- Section Commentaires -->
        <div class="comments-section">
            <h3>Comments ({{ $post->comments->count() }})</h3>
            
            @if($post->comments->count() > 0)
                <div class="comments-list">
                    @foreach($post->comments as $comment)
                        <div class="comment-item">
                            <div class="comment-header">
                                <div class="user-info">
                                    <div class="user-avatar-sm">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="user-details">
                                        <h4>{{ $comment->user->name ?? 'Utilisateur' }}</h4>
                                        <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="comment-actions">
                                    @if($comment->created_by == 1) {{-- Temporaire --}}
                                        <a href="{{ route('user.comments.edit', $comment) }}" class="btn btn-xs btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('user.comments.destroy', $comment) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger" 
                                                    onclick="return confirm('Supprimer ce commentaire?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="comment-content">
                                <p>{{ $comment->content_C }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-comments">
                    <i class="fas fa-comments"></i>
                    <p>No comments yet</p>
                    <p>Be the first to comment on this post!</p>
                </div>
            @endif

            <!-- Comment Form -->
            <div class="comment-form">
                <h4>Add a Comment</h4>
                <form action="{{ route('user.comments.store', $post) }}" method="POST">
                    @csrf
                    <textarea name="content_C" rows="3" placeholder="Write your comment..." 
                            class="form-control" required></textarea>
                    @error('content_C')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Comment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Actions du post -->
    <div class="post-actions-section">
        <div class="action-buttons">
            <form action="{{ route('user.likes.toggle', $post) }}" method="POST" class="like-form d-inline">
                @csrf
                <button type="submit" class="btn btn-outline like-btn" 
                        data-post-id="{{ $post->id }}"
                        data-liked="{{ $post->likes->contains('liked_by', 1) ? 'true' : 'false' }}">
                    <i class="fas fa-heart like-icon"></i>
                    <span class="like-text">
                        {{ $post->likes->contains('liked_by', 1) ? 'Unlike' : 'Like' }}
                    </span>
                    <span class="likes-count">({{ $post->likes->count() }})</span>
                </button>
            </form>
            
            <button class="btn btn-outline" onclick="document.getElementById('comment-form').scrollIntoView()">
                <i class="fas fa-comment"></i>
                Comment
            </button>
            
            <button class="btn btn-outline">
                <i class="fas fa-share"></i>
                Share
            </button>
        </div>

        <!-- Zone de danger pour le propriétaire du post -->
        @if($post->created_by == 1)
            <div class="danger-zone">
                <h4>Danger Zone</h4>
                <p>Deleting this post is irreversible.</p>
                <form action="{{ route('user.posts.destroy', $post) }}" method="POST" 
                    onsubmit="return confirm('Are you sure you want to delete this post?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Delete this post
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

@endsection