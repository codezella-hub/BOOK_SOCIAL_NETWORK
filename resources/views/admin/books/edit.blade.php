@extends('layouts.admin-layout')

@section('title', 'Edit Book - Admin Panel')
@section('page-title', 'Edit Book')

@section('content')
    <div class="admin-section">
        <div class="section-header">
            <div class="header-content">
                <h2 class="section-title">Edit Book: {{ $book->title }}</h2>
                <p class="section-subtitle">Update the book details below</p>
            </div>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Books
            </a>
        </div>

        <div class="form-container">
            <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <!-- Left Column -->
                    <div class="form-column">
                        <div class="form-group">
                            <label for="title" class="form-label">Book Title *</label>
                            <input type="text" name="title" id="title" class="form-control"
                                   value="{{ old('title', $book->title) }}" required maxlength="255">
                            @error('title')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="author_name" class="form-label">Author Name *</label>
                            <input type="text" name="author_name" id="author_name" class="form-control"
                                   value="{{ old('author_name', $book->author_name) }}" required maxlength="255">
                            @error('author_name')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="isbn" class="form-label">ISBN *</label>
                            <input type="text" name="isbn" id="isbn" class="form-control"
                                   value="{{ old('isbn', $book->isbn) }}" required maxlength="255">
                            @error('isbn')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category_id" class="form-label">Category *</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $book->category_id) == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="user_id" class="form-label">Book Owner *</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">Select a user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (old('user_id', $book->user_id) == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="form-column">
                        <div class="form-group">
                            <label for="synopsis" class="form-label">Synopsis</label>
                            <textarea name="synopsis" id="synopsis" class="form-control"
                                      rows="6" maxlength="1000">{{ old('synopsis', $book->synopsis) }}</textarea>
                            @error('synopsis')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="book_cover" class="form-label">Book Cover</label>

                            @if($book->book_cover)
                                <div class="current-cover" style="margin-bottom: 15px;">
                                    <img src="{{ Storage::disk('public')->url($book->book_cover) }}"
                                         alt="Current cover"
                                         style="max-width: 120px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    <div style="font-size: 12px; color: #7f8c8d; margin-top: 5px;">Current cover</div>
                                </div>
                            @endif

                            <input type="file" name="book_cover" id="book_cover" class="form-control"
                                   accept="image/*">
                            <div class="file-info">Accepted formats: JPEG, PNG, JPG, GIF (Max: 2MB)</div>
                            @error('book_cover')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Book Settings</label>
                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="archived" value="1" {{ (old('archived', $book->archived) ? 'checked' : '') }}>
                                    <span class="checkbox-custom"></span>
                                    Archived Book
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="shareable" value="1" {{ (old('shareable', $book->shareable) ? 'checked' : '') }}>
                                    <span class="checkbox-custom"></span>
                                    Shareable Book
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Book
                    </button>
                    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Les styles sont les mêmes que pour la page create */
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .form-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--main-color);
            box-shadow: 0 0 0 3px rgba(5, 7, 9, 0.1);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
        }

        .file-info {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-weight: 500;
            color: #2c3e50;
        }

        .checkbox-label input[type="checkbox"] {
            display: none;
        }

        .checkbox-custom {
            width: 20px;
            height: 20px;
            border: 2px solid #bdc3c7;
            border-radius: 4px;
            position: relative;
            transition: all 0.3s ease;
        }

        .checkbox-label input[type="checkbox"]:checked + .checkbox-custom {
            background: var(--main-color);
            border-color: var(--main-color);
        }

        .checkbox-label input[type="checkbox"]:checked + .checkbox-custom::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 12px;
            font-weight: bold;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            font-weight: 500;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-start;
            padding-top: 20px;
            border-top: 1px solid #f1f3f4;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-primary {
            background: var(--main-color);
            color: white;
        }

        .btn-primary:hover {
            background: #1a1a1a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 7, 9, 0.2);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .current-cover {
            text-align: center;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
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
