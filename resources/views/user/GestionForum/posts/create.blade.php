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