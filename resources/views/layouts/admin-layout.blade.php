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
    <style>
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
        <!-- Dans votre sidebar, remplacez une des sections par : -->


        <ul class="admin-menu">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">
                <a href="{{ route('admin.donations.index') }}">
                    <i class="fas fa-heart"></i>
                    <span>Donations</span>
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

            <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
                </a>
            </li>
            <!-- Dans admin.blade.php -->
            <li class="{{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                <a href="{{ route('admin.books.index') }}">
                    <i class="fas fa-book"></i>
                    <span>Books</span>
                </a>
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
            <li class="{{ request()->routeIs('admin.quiz.*') ? 'active' : '' }}">
    <a href="{{ route('admin.quiz.index') }}">
                    <i class="fas fa-question-circle"></i>
                    <span>Quiz</span>
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

                        <a href="#" class="user-dropdown-item">
                            <i class="fas fa-cog"></i>
                            <span>Paramètres</span>
                        </a>



                        <a href="{{ route('logout') }}"
                           class="user-dropdown-item logout"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Déconnexion</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
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
