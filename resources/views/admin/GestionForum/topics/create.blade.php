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

@section('styles')
<style>
      /* Styles GLOBAUX admin seulement */
    .admin-content {
        padding: 2rem;
    }

    .content-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }

    .content-card {
        margin-bottom: 2rem;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        background: white;
    }

    .card-body {
        padding: 2rem;
    }

    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .card-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
        margin: 0;
    }

    .header-title h2 {
        font-size: 1.875rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .header-title p {
        font-size: 1.125rem;
        color: #6b7280;
        margin: 0;
    }

    /* Boutons de base */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.875rem 1.75rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        font-size: 0.95rem;
        gap: 0.5rem;
        min-height: 3rem;
    }

    .btn-sm {
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        min-height: 2.5rem;
        gap: 0.375rem;
    }

    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-1px);
    }

    .btn-warning {
        background-color: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background-color: #d97706;
        transform: translateY(-1px);
    }

    .btn-danger {
        background-color: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background-color: #dc2626;
        transform: translateY(-1px);
    }

    .btn-info {
        background-color: #06b6d4;
        color: white;
    }

    .btn-info:hover {
        background-color: #0891b2;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .admin-content {
            padding: 1rem;
        }
        
        .content-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
    /* Styles spécifiques à la création */
    .admin-form .form-group {
        margin-bottom: 2rem;
    }

    .admin-form .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #374151;
        font-size: 0.95rem;
    }

    .admin-form .form-label.required::after {
        content: " *";
        color: #ef4444;
    }

    .admin-form .form-control {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        transition: all 0.2s ease-in-out;
        font-size: 1rem;
        line-height: 1.5;
    }

    .admin-form .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .admin-form .form-control.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .admin-form .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    .form-actions {
        padding-top: 2.5rem;
        margin-top: 2.5rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        align-items: center;
    }

    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@endsection