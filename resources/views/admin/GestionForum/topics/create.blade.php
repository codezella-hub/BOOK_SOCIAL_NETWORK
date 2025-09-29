@extends('admin.dashboard')

@section('title', 'Create a Topic')
@section('page-title', 'Create a Topic')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <div class="header-title">
            <h2>Create a new topic</h2>
            <p>Fill in the topic information</p>
        </div>
        <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Return
        </a>
    </div>

    <div class="content-card">
        <div class="card-body">
            <form action="{{ route('admin.topics.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="title" class="form-label">
                        Topic Title *
                        @error('title')
                            <span class="error-message">- {{ $message }}</span>
                        @enderror
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title') }}" 
                           class="form-control @error('title') error @enderror"
                           placeholder="Entrez le titre du topic"
                           required>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">
                        Description
                        @error('description')
                            <span class="error-message">- {{ $message }}</span>
                        @enderror
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="5" 
                              class="form-control @error('description') error @enderror"
                              placeholder="Décrivez le sujet de ce topic (optionnel)">{{ old('description') }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Create the topic
                    </button>
                    <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection