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