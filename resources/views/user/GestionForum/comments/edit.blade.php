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
@section('scripts')
<script>
    // Compteur pour les commentaires (create/edit)
    const contentC = document.getElementById('content_C');
    const charCountComment = document.getElementById('charCount');
    
    if (contentC && charCountComment) {
        contentC.addEventListener('input', function() {
            const length = this.value.length;
            charCountComment.textContent = length;
        });
    }

</script>
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

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
    }

    /* === STYLES SPÉCIFIQUES FORMULAIRES === */
    .comment-form-container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        padding: 2rem;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #374151;
    }

    .form-label.required::after {
        content: " *";
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 1rem;
        resize: vertical;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .char-count {
        text-align: right;
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }

    @media (max-width: 768px) {
        .forum-container {
            margin: 1rem auto;
            padding: 0 0.5rem;
        }
        
        .comment-form-container {
            padding: 1.5rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@endsection