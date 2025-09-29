@extends('layouts.user-layout')

@section('title', 'Modifier le Commentaire')
@section('content')
<div class="forum-container">
    <div class="forum-header">
        <h1>Edit Comment</h1>
        <p>Modify your comment</p>
        <a href="{{ route('user.posts.show', $comment->post) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Post
        </a>
    </div>

    <div class="comment-form-container">
        <form action="{{ route('user.comments.update', $comment) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="content_C" class="form-label required">Your Comment</label>
                <textarea name="content_C" id="content_C" rows="4" class="form-control" 
                          placeholder="Modify your comment..." 
                          required>{{ old('content_C', $comment->content_C) }}</textarea>
                @error('content_C')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="char-count">
                    <span id="charCount">{{ strlen($comment->content_C) }}</span>/500 characters
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i>
                    Save Comment
                </button>
                <a href="{{ route('user.posts.show', $comment->post) }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
