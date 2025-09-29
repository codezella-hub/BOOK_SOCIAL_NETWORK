@extends('layouts.admin-layout')

@section('title', 'Manage Categories - Admin Panel')
@section('page-title', 'Manage Categories')

@section('content')
    <div class="admin-section">
        <div class="section-header">
            <div class="header-content">
                <h2 class="section-title">All Categories</h2>
                <p class="section-subtitle">Manage your book categories and their organization</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Category
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-cards" style="margin-bottom: 30px;">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Total Categories</h3>
                    <p>{{ $categories->total() }}</p>
                </div>
                <div class="stat-icon categories-icon">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Active Categories</h3>
                    <p>{{ $categories->total() }}</p>
                </div>
                <div class="stat-icon active-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <div class="alert-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="alert-content">
                    <h4>Success!</h4>
                    <p>{{ session('success') }}</p>
                </div>
                <button class="alert-close" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="alert-content">
                    <h4>Error!</h4>
                    <p>{{ session('error') }}</p>
                </div>
                <button class="alert-close" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Categories Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th class="column-id">ID</th>
                        <th class="column-name">Name</th>
                        <th class="column-description">Description</th>
                        <th class="column-books">Books</th>
                        <th class="column-date">Created</th>
                        <th class="column-actions">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $category)
                        <tr class="table-row">
                            <td class="column-id">
                                <span class="id-badge">#{{ $category->id }}</span>
                            </td>
                            <td class="column-name">
                                <div class="name-content">
                                    <strong class="category-name">{{ $category->name }}</strong>
                                </div>
                            </td>
                            <td class="column-description">
                                <div class="description-content">
                                    {{ $category->description ? Str::limit($category->description, 60) : 'No description' }}
                                </div>
                            </td>
                            <td class="column-books">
                            <span class="books-badge">
                                <i class="fas fa-book"></i>
                                {{ $category->books_count ?? $category->books()->count() }}
                            </span>
                            </td>
                            <td class="column-date">
                                <div class="date-content">
                                    <div class="date">{{ $category->created_at->format('M d, Y') }}</div>
                                    <div class="time">{{ $category->created_at->format('H:i') }}</div>
                                </div>
                            </td>
                            <td class="column-actions">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.categories.show', $category) }}" class="action-btn view-btn" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="action-btn edit-btn" title="Edit Category">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="action-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete-btn" title="Delete Category"
                                                onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-tags"></i>
                                    <h3>No Categories Found</h3>
                                    <p>Get started by creating your first category</p>
                                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Create Category
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Custom Pagination -->
        @if($categories->hasPages())
            <div class="pagination-container">
                <div class="pagination-info">
                    Showing <strong>{{ $categories->firstItem() ?? 0 }}</strong> to
                    <strong>{{ $categories->lastItem() ?? 0 }}</strong> of
                    <strong>{{ $categories->total() }}</strong> categories
                </div>

                <div class="pagination-wrapper">
                    <!-- Previous Page -->
                    @if($categories->onFirstPage())
                        <span class="pagination-arrow disabled">
                    <i class="fas fa-chevron-left"></i>
                    <span>Previous</span>
                </span>
                    @else
                        <a href="{{ $categories->previousPageUrl() }}" class="pagination-arrow">
                            <i class="fas fa-chevron-left"></i>
                            <span>Previous</span>
                        </a>
                    @endif

                    <!-- Page Numbers -->
                    <div class="pagination-numbers">
                        @php
                            $current = $categories->currentPage();
                            $last = $categories->lastPage();
                            $start = max(1, $current - 2);
                            $end = min($last, $current + 2);
                        @endphp

                        @if($start > 1)
                            <a href="{{ $categories->url(1) }}" class="pagination-number">1</a>
                            @if($start > 2)
                                <span class="pagination-ellipsis">...</span>
                            @endif
                        @endif

                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $current)
                                <span class="pagination-number active">{{ $page }}</span>
                            @else
                                <a href="{{ $categories->url($page) }}" class="pagination-number">{{ $page }}</a>
                            @endif
                        @endfor

                        @if($end < $last)
                            @if($end < $last - 1)
                                <span class="pagination-ellipsis">...</span>
                            @endif
                            <a href="{{ $categories->url($last) }}" class="pagination-number">{{ $last }}</a>
                        @endif
                    </div>

                    <!-- Next Page -->
                    @if($categories->hasMorePages())
                        <a href="{{ $categories->nextPageUrl() }}" class="pagination-arrow">
                            <span>Next</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="pagination-arrow disabled">
                    <span>Next</span>
                    <i class="fas fa-chevron-right"></i>
                </span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <style>
        /* Section Header */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-content {
            flex: 1;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .section-subtitle {
            color: #7f8c8d;
            font-size: 14px;
            margin: 0;
        }

        /* Stats Cards */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-left: 4px solid var(--main-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }

        .stat-info h3 {
            margin: 0;
            font-size: 14px;
            color: #7f8c8d;
            font-weight: 500;
        }

        .stat-info p {
            margin: 8px 0 0;
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .categories-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .active-icon {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            color: white;
        }

        /* Alerts */
        .alert {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            position: relative;
            animation: slideIn 0.3s ease;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .alert-content {
            flex: 1;
        }

        .alert-content h4 {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: 600;
        }

        .alert-content p {
            margin: 0;
            font-size: 14px;
        }

        .alert-close {
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
            color: inherit;
            opacity: 0.7;
            transition: opacity 0.3s;
            padding: 5px;
        }

        .alert-close:hover {
            opacity: 1;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .admin-table th {
            background: #f8f9fa;
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #e9ecef;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .admin-table td {
            padding: 20px;
            border-bottom: 1px solid #f1f3f4;
            vertical-align: top;
        }

        .table-row:hover {
            background: #f8f9fa;
        }

        /* Column Specific Styles */
        .column-id {
            width: 80px;
        }

        .id-badge {
            background: #f8f9fa;
            color: #6c757d;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #e9ecef;
        }

        .column-name {
            min-width: 180px;
        }

        .category-name {
            color: #2c3e50;
            font-weight: 600;
        }

        .column-description {
            min-width: 250px;
            max-width: 300px;
        }

        .description-content {
            color: #7f8c8d;
            font-size: 14px;
            line-height: 1.4;
        }

        .column-books {
            width: 100px;
        }

        .books-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #bbdefb;
        }

        .column-date {
            width: 120px;
        }

        .date-content {
            text-align: left;
        }

        .date {
            font-weight: 500;
            color: #2c3e50;
            font-size: 13px;
        }

        .time {
            color: #7f8c8d;
            font-size: 12px;
            margin-top: 2px;
        }

        .column-actions {
            width: 150px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-start;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 14px;
        }

        .view-btn {
            background: #e8f5e8;
            color: #4caf50;
        }

        .view-btn:hover {
            background: #4caf50;
            color: white;
            transform: translateY(-2px);
        }

        .edit-btn {
            background: #e3f2fd;
            color: #2196f3;
        }

        .edit-btn:hover {
            background: #2196f3;
            color: white;
            transform: translateY(-2px);
        }

        .delete-btn {
            background: #ffebee;
            color: #f44336;
        }

        .delete-btn:hover {
            background: #f44336;
            color: white;
            transform: translateY(-2px);
        }

        .action-form {
            display: inline;
            margin: 0;
        }

        /* Empty State */
        .empty-row td {
            padding: 60px 20px;
            text-align: center;
        }

        .empty-state {
            max-width: 400px;
            margin: 0 auto;
        }

        .empty-state i {
            font-size: 64px;
            color: #e0e0e0;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #7f8c8d;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .empty-state p {
            color: #bdc3c7;
            margin-bottom: 25px;
        }

        /* Pagination */
        .pagination-container {
            margin-top: 40px;
            padding: 25px 0;
            border-top: 1px solid #f1f3f4;
        }

        .pagination-info {
            text-align: center;
            margin-bottom: 20px;
            color: #7f8c8d;
            font-size: 14px;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .pagination-arrow {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            color: var(--main-color);
            text-decoration: none;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            font-weight: 600;
            transition: all 0.3s ease;
            min-width: 120px;
            justify-content: center;
        }

        .pagination-arrow:hover:not(.disabled) {
            background: var(--main-color);
            color: white;
            border-color: var(--main-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 7, 9, 0.15);
        }

        .pagination-arrow.disabled {
            background: #f8f9fa;
            color: #bdc3c7;
            border-color: #e9ecef;
            cursor: not-allowed;
        }

        .pagination-numbers {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-number {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
            height: 44px;
            background: white;
            color: #555;
            text-decoration: none;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .pagination-number:hover:not(.active) {
            background: #f8f9fa;
            border-color: var(--main-color);
            color: var(--main-color);
            transform: translateY(-2px);
        }

        .pagination-number.active {
            background: var(--main-color);
            color: white;
            border-color: var(--main-color);
            box-shadow: 0 4px 12px rgba(5, 7, 9, 0.2);
        }

        .pagination-ellipsis {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
            height: 44px;
            color: #bdc3c7;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .section-header {
                flex-direction: column;
                align-items: stretch;
            }

            .header-content {
                text-align: center;
            }

            .stats-cards {
                grid-template-columns: 1fr;
            }

            .pagination-wrapper {
                flex-direction: column;
                gap: 15px;
            }

            .pagination-numbers {
                order: -1;
            }

            .admin-table th,
            .admin-table td {
                padding: 12px 15px;
            }

            .action-buttons {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .section-title {
                font-size: 20px;
            }

            .pagination-arrow {
                min-width: 100px;
                padding: 8px 16px;
                font-size: 14px;
            }

            .pagination-number {
                min-width: 40px;
                height: 40px;
                font-size: 13px;
            }
        }
    </style>
@endsection
