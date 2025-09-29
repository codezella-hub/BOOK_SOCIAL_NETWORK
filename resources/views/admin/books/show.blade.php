@extends('layouts.admin-layout')

@section('title', 'Book Details - Admin Panel')
@section('page-title', 'Book Details')

@section('content')
    <div class="admin-section">
        <div class="section-header">
            <div class="header-content">
                <h2 class="section-title">{{ $book->title }}</h2>
                <p class="section-subtitle">Complete book information</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Book
                </a>
                <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Books
                </a>
            </div>
        </div>

        <div class="book-details-grid">
            <!-- Left Column - Book Info -->
            <div class="details-column">
                <div class="detail-card">
                    <h3 class="detail-card-title">
                        <i class="fas fa-info-circle"></i>
                        Book Information
                    </h3>
                    <div class="detail-list">
                        <div class="detail-item">
                            <span class="detail-label">Title:</span>
                            <span class="detail-value">{{ $book->title }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Author:</span>
                            <span class="detail-value">{{ $book->author_name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">ISBN:</span>
                            <span class="detail-value isbn">{{ $book->isbn }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Category:</span>
                            <span class="detail-value category-badge">{{ $book->category->name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Synopsis:</span>
                            <div class="detail-value synopsis">
                                {{ $book->synopsis ?: 'No synopsis provided' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detail-card">
                    <h3 class="detail-card-title">
                        <i class="fas fa-cog"></i>
                        Book Settings
                    </h3>
                    <div class="status-badges-large">
                        <div class="status-badge-large {{ $book->archived ? 'archived' : 'active' }}">
                            <i class="fas {{ $book->archived ? 'fa-archive' : 'fa-box-open' }}"></i>
                            <div class="status-content">
                                <strong>{{ $book->archived ? 'Archived' : 'Active' }}</strong>
                                <span>{{ $book->archived ? 'Book is archived' : 'Book is active' }}</span>
                            </div>
                        </div>
                        <div class="status-badge-large {{ $book->shareable ? 'shareable' : 'private' }}">
                            <i class="fas {{ $book->shareable ? 'fa-share-alt' : 'fa-lock' }}"></i>
                            <div class="status-content">
                                <strong>{{ $book->shareable ? 'Shareable' : 'Private' }}</strong>
                                <span>{{ $book->shareable ? 'Can be shared with others' : 'Private book' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Owner & Cover -->
            <div class="details-column">
                <div class="detail-card">
                    <h3 class="detail-card-title">
                        <i class="fas fa-user"></i>
                        Book Owner
                    </h3>
                    <div class="user-card">
                        <div class="user-avatar-large">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($book->user->name) }}&background=random"
                                 alt="{{ $book->user->name }}">
                        </div>
                        <div class="user-info-large">
                            <h4>{{ $book->user->name }}</h4>
                            <p>{{ $book->user->email }}</p>
                            <div class="user-meta">
                            <span class="meta-item">
                                <i class="fas fa-calendar"></i>
                                Joined {{ $book->user->created_at->format('M Y') }}
                            </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detail-card">
                    <h3 class="detail-card-title">
                        <i class="fas fa-image"></i>
                        Book Cover
                    </h3>
                    <div class="book-cover-large">
                        @if($book->book_cover)
                            <img src="{{ Storage::disk('public')->url($book->book_cover) }}"
                                 alt="{{ $book->title }}"
                                 onerror="this.src='https://via.placeholder.com/300x400/667eea/ffffff?text=Cover+Not+Found'">
                        @else
                            <div class="cover-placeholder-large">
                                <i class="fas fa-book"></i>
                                <span>No Cover Image</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="detail-card">
                    <h3 class="detail-card-title">
                        <i class="fas fa-history"></i>
                        Timestamps
                    </h3>
                    <div class="detail-list">
                        <div class="detail-item">
                            <span class="detail-label">Created:</span>
                            <span class="detail-value">{{ $book->created_at->format('M d, Y \a\t H:i') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Last Updated:</span>
                            <span class="detail-value">{{ $book->updated_at->format('M d, Y \a\t H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .book-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .details-column {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .detail-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }

        .detail-card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
        }

        .detail-card-title i {
            color: var(--main-color);
        }

        .detail-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f1f3f4;
        }

        .detail-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: #2c3e50;
            min-width: 100px;
            font-size: 14px;
        }

        .detail-value {
            color: #7f8c8d;
            flex: 1;
            font-size: 14px;
            line-height: 1.5;
        }

        .detail-value.isbn {
            font-family: 'Courier New', monospace;
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #e9ecef;
        }

        .detail-value.synopsis {
            white-space: pre-line;
            line-height: 1.6;
        }

        .category-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            border: 1px solid #bbdefb;
        }

        /* Status Badges Large */
        .status-badges-large {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .status-badge-large {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .status-badge-large.active {
            background: #e8f5e8;
            border-left: 4px solid #4caf50;
        }

        .status-badge-large.archived {
            background: #ffebee;
            border-left: 4px solid #f44336;
        }

        .status-badge-large.shareable {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
        }

        .status-badge-large.private {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
        }

        .status-badge-large i {
            font-size: 24px;
            width: 40px;
            text-align: center;
        }

        .status-content {
            flex: 1;
        }

        .status-content strong {
            display: block;
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .status-content span {
            font-size: 13px;
            color: #7f8c8d;
        }

        /* User Card */
        .user-card {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-avatar-large img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f1f3f4;
        }

        .user-info-large {
            flex: 1;
        }

        .user-info-large h4 {
            margin: 0 0 5px 0;
            color: #2c3e50;
            font-size: 18px;
        }

        .user-info-large p {
            margin: 0 0 10px 0;
            color: #7f8c8d;
            font-size: 14px;
        }

        .user-meta {
            display: flex;
            gap: 15px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: #95a5a6;
        }

        .meta-item i {
            font-size: 11px;
        }

        /* Book Cover Large */
        .book-cover-large {
            text-align: center;
        }

        .book-cover-large img {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .cover-placeholder-large {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            gap: 15px;
        }

        .cover-placeholder-large i {
            font-size: 64px;
        }

        .cover-placeholder-large span {
            font-size: 16px;
            font-weight: 500;
        }

        /* Header Actions */
        .header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
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

        /* Responsive */
        @media (max-width: 968px) {
            .book-details-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .user-card {
                flex-direction: column;
                text-align: center;
            }

            .user-meta {
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .section-header {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            .header-actions {
                justify-content: stretch;
            }

            .btn {
                flex: 1;
                justify-content: center;
            }

            .detail-item {
                flex-direction: column;
                gap: 5px;
            }

            .detail-label {
                min-width: auto;
            }
        }
    </style>
@endsection
