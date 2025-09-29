@extends('layouts.admin-layout')

@section('title', 'Category Details - Admin Panel')
@section('page-title', 'Category Details')

@section('content')
    <div class="admin-section">
        <div class="section-header">
            <h2 class="section-title">Category: {{ $category->name }}</h2>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn"
                   style="background: #6c757d; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="category-details" style="display: grid; gap: 20px;">
            <div class="detail-card" style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                <h3 style="margin-bottom: 15px; color: #333;">Category Information</h3>

                <div class="detail-row" style="display: grid; grid-template-columns: 150px 1fr; gap: 10px; margin-bottom: 10px;">
                    <strong>ID:</strong>
                    <span>{{ $category->id }}</span>
                </div>

                <div class="detail-row" style="display: grid; grid-template-columns: 150px 1fr; gap: 10px; margin-bottom: 10px;">
                    <strong>Name:</strong>
                    <span>{{ $category->name }}</span>
                </div>

                <div class="detail-row" style="display: grid; grid-template-columns: 150px 1fr; gap: 10px; margin-bottom: 10px;">
                    <strong>Description:</strong>
                    <span>{{ $category->description ?: 'No description provided' }}</span>
                </div>

                <div class="detail-row" style="display: grid; grid-template-columns: 150px 1fr; gap: 10px; margin-bottom: 10px;">
                    <strong>Books Count:</strong>
                    <span>
                    <span class="badge" style="background: #e3f2fd; color: #1976d2; padding: 4px 8px; border-radius: 12px;">
                        {{ $category->books_count ?? $category->books()->count() }} books
                    </span>
                </span>
                </div>

                <div class="detail-row" style="display: grid; grid-template-columns: 150px 1fr; gap: 10px; margin-bottom: 10px;">
                    <strong>Created:</strong>
                    <span>{{ $category->created_at->format('M d, Y \a\t H:i') }}</span>
                </div>

                <div class="detail-row" style="display: grid; grid-template-columns: 150px 1fr; gap: 10px;">
                    <strong>Last Updated:</strong>
                    <span>{{ $category->updated_at->format('M d, Y \a\t H:i') }}</span>
                </div>
            </div>

            @if($category->books()->count() > 0)
                <div class="books-section">
                    <h3 style="margin-bottom: 15px; color: #333;">Associated Books</h3>
                    <div class="books-list" style="display: grid; gap: 10px;">
                        @foreach($category->books()->latest()->take(5)->get() as $book)
                            <div class="book-item" style="background: white; padding: 15px; border-radius: 6px; border: 1px solid #eee;">
                                <strong>{{ $book->title }}</strong>
                                <div style="color: #666; font-size: 14px;">
                                    by {{ $book->author_name }} • ISBN: {{ $book->isbn }}
                                </div>
                            </div>
                        @endforeach

                        @if($category->books()->count() > 5)
                            <div style="text-align: center; padding: 10px;">
                    <span style="color: #666;">
                        And {{ $category->books()->count() - 5 }} more books...
                    </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .detail-card {
            border-left: 4px solid var(--main-color);
        }

        .book-item {
            transition: all 0.3s;
        }

        .book-item:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
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
