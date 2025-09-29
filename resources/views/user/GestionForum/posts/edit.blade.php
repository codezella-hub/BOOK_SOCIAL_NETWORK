@extends('layouts.user-layout')

@section('title', 'Modifier le Post')
@section('content')
<div class="forum-container">
    <div class="forum-header">
        <h1>Edit Post</h1>
        <p>Modify the content of your post</p>
        <a href="{{ route('user.posts.index', $post) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="post-form-container">
        <form action="{{ route('user.posts.update', $post) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="topic_id" class="form-label required">Post Topic</label>
                <select name="topic_id" id="topic_id" class="form-control" required>
                    <option value="">Select a topic</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" 
                            {{ $post->topic_id == $topic->id ? 'selected' : '' }}>
                            {{ $topic->title }}
                        </option>
                    @endforeach
                </select>
                @error('topic_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="content_P" class="form-label required">Post Content</label>
                <textarea name="content_P" id="content_P" rows="6" class="form-control" 
                          placeholder="Modify the content of your post..." 
                          required>{{ old('content_P', $post->content_P) }}</textarea>
                @error('content_P')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="char-count">
                    <span id="charCount">{{ strlen($post->content_P) }}</span>/1000 characters
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i>
                    Edit Post
                </button>
                <a href="{{ route('user.posts.index', $post) }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
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

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    /* === STYLES SPÉCIFIQUES FORMULAIRES === */
    .post-form-container {
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
        
        .post-form-container {
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