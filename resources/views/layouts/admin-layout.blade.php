<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Social Book Network</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- CSS Section -->
    @vite(['resources/css/admin.css'])
    
    <!-- Styles globaux pour tous les composants admin -->
    <style>
        /* ===== ESPACEMENT GÉNÉRAL ===== */
        .admin-content {
            padding: 2rem;
        }

        .content-header {
            margin-bottom: 2rem;
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

        /* ===== TABLES - PLUS AÉRÉES ===== */
        .table-compact td,
        .table-compact th {
            padding: 1.25rem 1.5rem;
        }

        .admin-table {
            border-collapse: collapse;
            width: 100%;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .admin-table thead th {
            border-bottom: 2px solid #e5e7eb;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: #f9fafb;
            padding: 1.25rem 1.5rem;
            color: #374151;
        }

        .admin-table tbody tr {
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s ease;
        }

        .admin-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .admin-table tbody tr:last-child {
            border-bottom: none;
        }

        .admin-table tbody td {
            padding: 1.5rem 1.5rem;
            vertical-align: top;
            color: #374151;
        }

        /* ===== FORMULAIRES - PLUS D'ESPACE ===== */
        .form-group {
            margin-bottom: 2rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #374151;
            font-size: 0.95rem;
        }

        .form-label.required::after {
            content: " *";
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
            font-size: 1rem;
            line-height: 1.5;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        /* ===== BOUTONS - MEILLEUR ESPACEMENT ===== */
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

        /* Espacement entre les boutons d'action */
        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        /* ===== GRID DÉTAILS - ESPACEMENT RÉDUIT ===== */
        .detail-grid {
            display: grid;
            gap: 1rem;
        }

        .detail-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .detail-value {
            font-size: 1rem;
            color: #111827;
            line-height: 1.4;
            font-weight: 500;
        }

        /* ===== ÉTAT VIDE - PLUS AÉRÉ ===== */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
        }

        .empty-icon {
            color: #9ca3af;
            font-size: 5rem;
            margin-bottom: 2rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #374151;
            font-weight: 600;
        }

        .empty-state p {
            font-size: 1.125rem;
            margin-bottom: 2.5rem;
            color: #6b7280;
            line-height: 1.6;
        }

        /* ===== ALERTES - PLUS D'ESPACE ===== */
        .alert {
            padding: 1.5rem;
            border-radius: 0.75rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
            border-color: #bbf7d0;
        }

        .alert-dismissible {
            position: relative;
        }

        .alert .close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            color: inherit;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .alert .close:hover {
            opacity: 1;
        }

        /* ===== LAYOUT RESPONSIVE ===== */
        .max-w-2xl {
            max-width: 48rem;
        }

        .max-w-4xl {
            max-width: 56rem;
        }

        /* Espacement pour les formulaires */
        .form-actions {
            padding-top: 2.5rem;
            margin-top: 2.5rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            align-items: center;
        }

        /* ===== CONTENU DES CELLULES TABLE ===== */
        .admin-table td strong {
            font-size: 1.125rem;
            color: #111827;
            font-weight: 600;
        }

        .admin-table .text-sm {
            line-height: 1.6;
            font-size: 0.95rem;
        }

        /* Zone de danger */
        .danger-zone {
            border: 1px solid #fecaca;
            background: #fef2f2;
            border-radius: 0.5rem;
        }

        .danger-zone .card-body {
            padding: 1.25rem;
        }

        .danger-zone p {
            margin-bottom: 1rem;
            line-height: 1.5;
            font-size: 0.9rem;
            color: #7f1d1d;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .danger-zone .btn-danger {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        /* Header styles */
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

        /* Card header */
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

        /* Responsive */
        @media (max-width: 768px) {
            .admin-content {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1.5rem;
            }
            
            .admin-table tbody td,
            .admin-table thead th {
                padding: 1rem;
            }
            
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
    
    @yield('styles')
</head>
<body>
<div class="admin-container">
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="admin-brand">
            <!-- Logo Social Book Network -->
            <span>Admin Panel</span>
        </div>

        <ul class="admin-menu">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="#">
                <a href="#">
                    <i class="fas fa-book"></i>
                    <span>Books</span>
                </a>
            </li>
            <li class="#">
                <a href="#">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.topics.*') ? 'active' : '' }}">
                <a href="{{ route('admin.topics.index') }}">
                    <i class="fas fa-comments"></i>
                    <span>Forum Management</span>
                </a>
            </li>
            <li class="#">
                <a href="#">
                    <i class="fas fa-comments"></i>
                    <span>Reviews</span>
                </a>
            </li>
            <li class="#">
                <a href="#">
                    <i class="fas fa-user-friends"></i>
                    <span>Clubs</span>
                </a>
            </li>
            <li class="#">
                <a href="#">
                    <i class="fas fa-chart-bar"></i>
                    <span>Analytics</span>
                </a>
            </li>
            <li class="#">
                <a href="#">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/') }}">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Back to Site</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="admin-main">
        <div class="admin-header">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="admin-title">@yield('page-title', 'Dashboard')</h1>
            </div>

            <div class="header-right">
                <div class="notification-icon" id="notificationIcon">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">5</span>

                    <!-- Notification Dropdown -->
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h3>Notifications</h3>
                            <button class="btn btn-primary">Mark all as read</button>
                        </div>
                        <div class="notification-list">
                            <div class="notification-item unread">
                                <i class="fas fa-user-plus notification-icon-sm"></i>
                                <div class="notification-content">
                                    <p>New user registration: Marie Dupont</p>
                                    <span class="notification-time">10 minutes ago</span>
                                </div>
                            </div>
                            <div class="notification-item unread">
                                <i class="fas fa-book notification-icon-sm"></i>
                                <div class="notification-content">
                                    <p>New book added: "Le Petit Prince"</p>
                                    <span class="notification-time">2 hours ago</span>
                                </div>
                            </div>
                            <div class="notification-item">
                                <i class="fas fa-star notification-icon-sm"></i>
                                <div class="notification-content">
                                    <p>New review received for "1984"</p>
                                    <span class="notification-time">Yesterday</span>
                                </div>
                            </div>
                            <div class="notification-item">
                                <i class="fas fa-users notification-icon-sm"></i>
                                <div class="notification-content">
                                    <p>New reading club created: "Science Fiction Lovers"</p>
                                    <span class="notification-time">2 days ago</span>
                                </div>
                            </div>
                        </div>
                        <div class="notification-footer">
                            <a href="#">View all notifications</a>
                        </div>
                    </div>
                </div>

                <div class="admin-user" id="adminUser">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=random" alt="Admin User">

                    <!-- User Dropdown Menu -->
                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </div>
                        <div class="user-dropdown-item">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </div>
                        <div class="user-dropdown-item logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        @yield('content')
    </div>
</div>

<!-- JavaScript Section -->
@vite(['resources/js/admin.js'])
@yield('scripts')
</body>
</html>