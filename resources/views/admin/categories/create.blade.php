@extends('layouts.admin-layout')

@section('title', 'Create Category - Admin Panel')
@section('page-title', 'Create New Category')

@section('content')
    <div class="admin-section">
        <div class="section-header">
            <h2 class="section-title">Add New Category</h2>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Categories
            </a>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Category Name *</label>
                <input type="text" name="name" id="name" class="form-control"
                       value="{{ old('name') }}" required maxlength="255">
                @error('name')
                <span class="error-message" style="color: #e74c3c; font-size: 14px; margin-top: 5px;">
                    {{ $message }}
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control"
                          rows="4" maxlength="500">{{ old('description') }}</textarea>
                @error('description')
                <span class="error-message" style="color: #e74c3c; font-size: 14px; margin-top: 5px;">
                    {{ $message }}
                </span>
                @enderror
            </div>

            <div class="form-actions" style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn"
                   style="background: #6c757d; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none;">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <style>
        .form-control:focus {
            border-color: var(--main-color);
            box-shadow: 0 0 0 2px rgba(5, 7, 9, 0.1);
        }

        .error-message {
            display: block;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
@endsection
