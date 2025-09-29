@extends('layouts.user-layout')

@section('title', 'Créer un Post')
@section('content')
<div class="forum-container">
    <div class="forum-header">
        <h1>Create a new post</h1>
        <p>Share your thoughts with the community</p>
        <a href="{{ route('user.posts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="post-form-container">
        <form action="{{ route('user.posts.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="topic_id" class="form-label required">Post Topic</label>
                <select name="topic_id" id="topic_id" class="form-control" required>
                    <option value="">Select a topic</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
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
                          placeholder="Share your thoughts, questions, or experiences..." 
                          required>{{ old('content_P') }}</textarea>
                @error('content_P')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                <div class="char-count">
                    <span id="charCount">0</span>/1000 characters
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Publish Post
                </button>
                <a href="{{ route('user.posts.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Compteur pour les posts (create/edit)
    const contentP = document.getElementById('content_P');
    const charCount = document.getElementById('charCount');
    
    if (contentP && charCount) {
        contentP.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;
        });
    }
});
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

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
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