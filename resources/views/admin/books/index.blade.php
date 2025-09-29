@extends('layouts.admin-layout')

@section('title', 'Manage Books - Admin Panel')
@section('page-title', 'Manage Books')

@section('content')
    <div class="admin-section">
        <div class="section-header">
            <div class="header-content">
                <h2 class="section-title">All Books</h2>
                <p class="section-subtitle">Manage books in your library</p>
            </div>
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Book
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-cards" style="margin-bottom: 30px;">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Total Books</h3>
                    <p>{{ \App\Models\Book::count() }}</p>
                </div>
                <div class="stat-icon books-icon">
                    <i class="fas fa-book"></i>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Shareable Books</h3>
                    <p>{{ \App\Models\Book::where('shareable', true)->count() }}</p>
                </div>
                <div class="stat-icon shareable-icon">
                    <i class="fas fa-share-alt"></i>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Archived Books</h3>
                    <p>{{ \App\Models\Book::where('archived', true)->count() }}</p>
                </div>
                <div class="stat-icon archived-icon">
                    <i class="fas fa-archive"></i>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success" style="background: #d1f2eb; color: #0f5132; border: 1px solid #badbcc; border-radius: 8px; padding: 16px; margin-bottom: 25px; display: flex; align-items: center; gap: 12px;">
                <div style="background: #0f5132; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check"></i>
                </div>
                <div style="flex: 1;">
                    <strong style="display: block; margin-bottom: 4px;">Success!</strong>
                    {{ session('success') }}
                </div>
                <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: #0f5132; cursor: pointer; padding: 4px;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px; padding: 16px; margin-bottom: 25px; display: flex; align-items: center; gap: 12px;">
                <div style="background: #721c24; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div style="flex: 1;">
                    <strong style="display: block; margin-bottom: 4px;">Error!</strong>
                    {{ session('error') }}
                </div>
                <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: #721c24; cursor: pointer; padding: 4px;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Filtres et Recherche -->
        <div class="filters-section" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 25px;">
            <form method="GET" action="{{ route('admin.books.index') }}" class="filters-form">
                <div class="filters-grid" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 12px; align-items: end;">

                    <!-- Barre de recherche -->
                    <div class="filter-group">
                        <label style="display: block; margin-bottom: 6px; font-weight: 500; color: #374151; font-size: 14px;">Search</label>
                        <div class="search-box" style="position: relative;">
                            <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6b7280;"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Title, author or ISBN..."
                                   style="width: 100%; padding: 10px 12px 10px 36px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; transition: all 0.2s;">
                            @if(request('search'))
                                <a href="{{ route('admin.books.index', request()->except('search')) }}"
                                   style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #6b7280; text-decoration: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Filtre Catégorie -->
                    <div class="filter-group">
                        <label style="display: block; margin-bottom: 6px; font-weight: 500; color: #374151; font-size: 14px;">Category</label>
                        <select name="category" onchange="this.form.submit()" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background: white;">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre Statut -->
                    <div class="filter-group">
                        <label style="display: block; margin-bottom: 6px; font-weight: 500; color: #374151; font-size: 14px;">Status</label>
                        <select name="archived" onchange="this.form.submit()" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background: white;">
                            <option value="">All Status</option>
                            <option value="0" {{ request('archived') === '0' ? 'selected' : '' }}>Active</option>
                            <option value="1" {{ request('archived') === '1' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    <!-- Filtre Partage -->
                    <div class="filter-group">
                        <label style="display: block; margin-bottom: 6px; font-weight: 500; color: #374151; font-size: 14px;">Sharing</label>
                        <select name="shareable" onchange="this.form.submit()" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background: white;">
                            <option value="">All Sharing</option>
                            <option value="1" {{ request('shareable') === '1' ? 'selected' : '' }}>Shareable</option>
                            <option value="0" {{ request('shareable') === '0' ? 'selected' : '' }}>Private</option>
                        </select>
                    </div>

                    <!-- Boutons -->
                    <div class="filter-group" style="display: flex; gap: 8px; align-items: end;">
                        <button type="submit" style="background: var(--main-color); color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('admin.books.index') }}" style="background: #6b7280; color: white; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- Résultats de recherche -->
            @if(request()->anyFilled(['search', 'category', 'archived', 'shareable']))
                <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                    <div style="color: #374151; font-size: 14px; margin-bottom: 8px;">
                        <strong>{{ $books->total() }}</strong> book(s) found
                        @if(request('search'))
                            for "<strong>{{ request('search') }}</strong>"
                        @endif
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @if(request('category'))
                            @php $category = \App\Models\Category::find(request('category')) @endphp
                            <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; display: flex; align-items: center; gap: 4px;">
                            Category: {{ $category->name }}
                            <a href="{{ route('admin.books.index', request()->except('category')) }}" style="color: #1e40af; text-decoration: none;">×</a>
                        </span>
                        @endif
                        @if(request('archived') !== '')
                            <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 12px; display: flex; align-items: center; gap: 4px;">
                            Status: {{ request('archived') ? 'Archived' : 'Active' }}
                            <a href="{{ route('admin.books.index', request()->except('archived')) }}" style="color: #92400e; text-decoration: none;">×</a>
                        </span>
                        @endif
                        @if(request('shareable') !== '')
                            <span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 12px; font-size: 12px; display: flex; align-items: center; gap: 4px;">
                            Sharing: {{ request('shareable') ? 'Shareable' : 'Private' }}
                            <a href="{{ route('admin.books.index', request()->except('shareable')) }}" style="color: #166534; text-decoration: none;">×</a>
                        </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Books Table -->
        <div class="table-container" style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div class="table-responsive">
                <table class="admin-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                    <tr style="background: #f9fafb;">
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; font-size: 14px;">Cover</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; font-size: 14px;">Book Details</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; font-size: 14px;">Category</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; font-size: 14px;">Owner</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; font-size: 14px;">Status</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; font-size: 14px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($books as $book)
                        <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;">
                            <td style="padding: 16px;">
                                <div style="width: 50px; height: 65px; border-radius: 4px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                    @if($book->book_cover)
                                        <img src="{{ Storage::disk('public')->url($book->book_cover) }}"
                                             alt="{{ $book->title }}"
                                             style="width: 100%; height: 100%; object-fit: cover;"
                                             onerror="this.src='https://via.placeholder.com/50x65/667eea/ffffff?text=Cover'">
                                    @else
                                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white;">
                                            <i class="fas fa-book" style="font-size: 16px;"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 16px;">
                                <div>
                                    <div style="font-weight: 600; color: #111827; margin-bottom: 4px; font-size: 15px;">{{ $book->title }}</div>
                                    <div style="color: #6b7280; font-size: 13px; margin-bottom: 4px; display: flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-user-edit" style="font-size: 12px;"></i>
                                        {{ $book->author_name }}
                                    </div>
                                    <div style="font-family: 'Courier New', monospace; background: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 11px; color: #374151; display: inline-block;">
                                        {{ $book->isbn }}
                                    </div>

                                </div>
                            </td>
                            <td style="padding: 16px;">
                            <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500;">
                                {{ $book->category->name }}
                            </span>
                            </td>
                            <td style="padding: 16px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($book->user->name) }}&background=random&color=fff"
                                         alt="{{ $book->user->name }}"
                                         style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                    <div>
                                        <div style="font-weight: 500; color: #374151; font-size: 14px;">{{ $book->user->name }}</div>
                                        <div style="color: #6b7280; font-size: 12px;">{{ $book->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 16px;">
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    <form action="{{ route('admin.books.toggle-archive', $book) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                style="background: {{ $book->archived ? '#fef3c7' : '#dcfce7' }};
                                                   color: {{ $book->archived ? '#92400e' : '#166534' }};
                                                   border: none;
                                                   padding: 4px 8px;
                                                   border-radius: 12px;
                                                   font-size: 11px;
                                                   font-weight: 500;
                                                   cursor: pointer;
                                                   display: flex;
                                                   align-items: center;
                                                   gap: 4px;
                                                   transition: all 0.2s;">
                                            <i class="fas {{ $book->archived ? 'fa-archive' : 'fa-box-open' }}" style="font-size: 10px;"></i>
                                            {{ $book->archived ? 'Archived' : 'Active' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.books.toggle-shareable', $book) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                style="background: {{ $book->shareable ? '#dcfce7' : '#fef3c7' }};
                                                   color: {{ $book->shareable ? '#166534' : '#92400e' }};
                                                   border: none;
                                                   padding: 4px 8px;
                                                   border-radius: 12px;
                                                   font-size: 11px;
                                                   font-weight: 500;
                                                   cursor: pointer;
                                                   display: flex;
                                                   align-items: center;
                                                   gap: 4px;
                                                   transition: all 0.2s;">
                                            <i class="fas {{ $book->shareable ? 'fa-share-alt' : 'fa-lock' }}" style="font-size: 10px;"></i>
                                            {{ $book->shareable ? 'Shareable' : 'Private' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td style="padding: 16px;">
                                <div style="display: flex; gap: 6px;">
                                    <a href="{{ route('admin.books.show', $book) }}"
                                       style="background: #dbeafe; color: #1e40af; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s;">
                                        <i class="fas fa-eye" style="font-size: 12px;"></i>
                                    </a>
                                    <a href="{{ route('admin.books.edit', $book) }}"
                                       style="background: #fef3c7; color: #92400e; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.2s;">
                                        <i class="fas fa-edit" style="font-size: 12px;"></i>
                                    </a>
                                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete this book?')"
                                                style="background: #fef2f2; color: #dc2626; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; transition: all 0.2s;">
                                            <i class="fas fa-trash" style="font-size: 12px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 48px 16px; text-align: center;">
                                <div style="color: #9ca3af; text-align: center;">
                                    <i class="fas fa-book-open" style="font-size: 48px; margin-bottom: 16px; color: #d1d5db;"></i>
                                    <h3 style="color: #6b7280; margin-bottom: 8px; font-size: 18px;">No Books Found</h3>
                                    <p style="color: #9ca3af; margin-bottom: 20px;">No books match your search criteria</p>
                                    <a href="{{ route('admin.books.index') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-refresh"></i> Clear Filters
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
            <div class="pagination-container" style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center; padding: 16px 0;">
                <div class="pagination-info" style="color: #6b7280; font-size: 14px;">
                    Showing <strong>{{ $books->firstItem() ?? 0 }}</strong> to
                    <strong>{{ $books->lastItem() ?? 0 }}</strong> of
                    <strong>{{ $books->total() }}</strong> books
                    @if(request()->anyFilled(['search', 'category', 'archived', 'shareable']))
                        <span style="color: #9ca3af; font-style: italic;">(filtered)</span>
                    @endif
                </div>

                <div class="pagination-wrapper" style="display: flex; align-items: center; gap: 8px;">
                    <!-- Previous Page -->
                    @if($books->onFirstPage())
                        <span style="padding: 8px 12px; color: #d1d5db; cursor: not-allowed; display: flex; align-items: center; gap: 6px; font-size: 14px;">
                    <i class="fas fa-chevron-left"></i> Previous
                </span>
                    @else
                        <a href="{{ $books->previousPageUrl() }}{{ request()->getQueryString() ? '&' . request()->getQueryString() : '' }}"
                           style="padding: 8px 12px; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 6px; font-size: 14px; transition: all 0.2s;">
                            <i class="fas fa-chevron-left"></i> Previous
                        </a>
                    @endif

                    <!-- Page Numbers -->
                    <div class="pagination-numbers" style="display: flex; gap: 4px;">
                        @php
                            $current = $books->currentPage();
                            $last = $books->lastPage();
                            $start = max(1, $current - 1);
                            $end = min($last, $current + 1);
                        @endphp

                        @if($start > 1)
                            <a href="{{ $books->url(1) }}{{ request()->getQueryString() ? '&' . request()->getQueryString() : '' }}"
                               style="padding: 8px 12px; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; text-decoration: none; font-size: 14px; transition: all 0.2s;">1</a>
                            @if($start > 2)
                                <span style="padding: 8px 4px; color: #9ca3af;">...</span>
                            @endif
                        @endif

                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $current)
                                <span style="padding: 8px 12px; background: var(--main-color); color: white; border-radius: 6px; font-size: 14px;">{{ $page }}</span>
                            @else
                                <a href="{{ $books->url($page) }}{{ request()->getQueryString() ? '&' . request()->getQueryString() : '' }}"
                                   style="padding: 8px 12px; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; text-decoration: none; font-size: 14px; transition: all 0.2s;">{{ $page }}</a>
                            @endif
                        @endfor

                        @if($end < $last)
                            @if($end < $last - 1)
                                <span style="padding: 8px 4px; color: #9ca3af;">...</span>
                            @endif
                            <a href="{{ $books->url($last) }}{{ request()->getQueryString() ? '&' . request()->getQueryString() : '' }}"
                               style="padding: 8px 12px; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; text-decoration: none; font-size: 14px; transition: all 0.2s;">{{ $last }}</a>
                        @endif
                    </div>

                    <!-- Next Page -->
                    @if($books->hasMorePages())
                        <a href="{{ $books->nextPageUrl() }}{{ request()->getQueryString() ? '&' . request()->getQueryString() : '' }}"
                           style="padding: 8px 12px; background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 6px; font-size: 14px; transition: all 0.2s;">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span style="padding: 8px 12px; color: #d1d5db; cursor: not-allowed; display: flex; align-items: center; gap: 6px; font-size: 14px;">
                    Next <i class="fas fa-chevron-right"></i>
                </span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <style>
        /* Hover effects */
        tr:hover {
            background: #f9fafb !important;
        }

        a:hover, button:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* Stats Cards */
        .books-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .shareable-icon {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            color: white;
        }

        .archived-icon {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .filters-grid {
                grid-template-columns: 1fr 1fr !important;
                gap: 12px !important;
            }
        }

        @media (max-width: 768px) {
            .filters-grid {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .pagination-container {
                flex-direction: column;
                gap: 16px;
                align-items: stretch;
            }

            .pagination-wrapper {
                justify-content: center;
            }
        }

        @media (max-width: 640px) {
            .pagination-numbers {
                display: none !important;
            }
        }
    </style>
@endsection
