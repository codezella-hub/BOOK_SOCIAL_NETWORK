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

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS Section -->
    @vite(['resources/css/admin.css'])
    <style>
        /* Animation pour les nouvelles notifications */
        .notification-badge {
            transition: all 0.3s ease;
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .notification-icon {
            position: relative;
            cursor: pointer;
        }
        
        .notification-item {
            transition: background-color 0.3s ease;
        }
        
        /* Pulse animation pour le badge */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        .notification-badge.new {
            animation: pulse 0.6s ease-in-out;
            background: #ff3838;
        }
        
        /* Loading state */
        .notification-icon.loading i {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Style pour le dropdown des notifications */
        .notification-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            width: 350px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .notification-dropdown.show {
            display: block;
        }

        .notification-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
            background: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .mark-all-read-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .mark-all-read-btn:hover {
            background: #0056b3;
        }

        .mark-all-read-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }

        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            text-decoration: none;
            color: #333;
            transition: background-color 0.2s;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background-color: #f0f8ff;
            border-left: 3px solid #007bff;
        }

        .notification-icon-sm {
            margin-right: 10px;
            color: #007bff;
            font-size: 14px;
            margin-top: 2px;
        }

        .notification-content {
            flex: 1;
        }

        .notification-content p {
            margin: 0 0 5px 0;
            font-size: 14px;
            line-height: 1.4;
        }

        .notification-time {
            font-size: 12px;
            color: #6c757d;
        }

        .notification-footer {
            padding: 10px 15px;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .notification-footer a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
    
    @yield('styles')
</head>
<body>
<div class="admin-container">
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="admin-brand">
            <span>Admin Panel</span>
        </div>

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
            <li class="{{ request()->routeIs('admin.topics.*') ? 'active' : '' }}">
                <a href="{{ route('admin.topics.index') }}">
                    <i class="fas fa-comments"></i>
                    <span>Forum Management</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.index') }}">
                    <i class="fas fa-flag"></i>
                    <span>Reports Management</span>
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
                    {{-- Badge initial --}}
                    @if($unreadNotificationsCount > 0)
                        <span class="notification-badge" id="initialNotificationBadge">{{ $unreadNotificationsCount }}</span>
                    @endif

                    <!-- Dropdown des notifications dynamique -->
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h3>Notifications</h3>
                            <button class="mark-all-read-btn" id="markAllReadBtn" 
                                    {{ $unreadNotificationsCount == 0 ? 'disabled' : '' }}>
                                Mark all as read
                            </button>
                        </div>
                        <div class="notification-list" id="notificationList">
                            @forelse($notifications as $notification)
                                <a href="{{ route('admin.notifications.read', ['notification' => $notification->id]) }}" 
                                   class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}"
                                   onclick="markAsRead('{{ $notification->id }}', event)">
                                    <i class="fas fa-flag notification-icon-sm"></i>
                                    <div class="notification-content">
                                        <p>{{ $notification->data['message'] }}</p>
                                        <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="notification-item">
                                    <div class="notification-content">
                                        <p>No notifications yet.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <div class="notification-footer">
                            <a href="{{ route('admin.reports.index') }}">View all reports</a>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script pour les notifications en temps réel -->
<script>
class RealTimeNotifications {
    constructor() {
        this.notificationIcon = document.getElementById('notificationIcon');
        this.notificationDropdown = document.getElementById('notificationDropdown');
        this.notificationList = document.getElementById('notificationList');
        this.markAllReadBtn = document.getElementById('markAllReadBtn');
        this.notificationBadge = document.querySelector('.notification-badge') || document.getElementById('initialNotificationBadge');
        this.pollingInterval = null;
        this.isPolling = false;
        this.isDropdownOpen = false;
        
        this.init();
    }

    init() {
        // Initialiser le badge
        this.initializeBadge();
        
        // Démarrer le polling
        this.startPolling();
        
        // Mettre à jour toutes les 5 secondes
        this.pollingInterval = setInterval(() => {
            this.updateNotifications();
        }, 5000);

        // Gérer le clic sur l'icône de notification
        this.notificationIcon.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleDropdown();
        });

        // Gérer le bouton "Mark all as read"
        this.markAllReadBtn.addEventListener('click', () => {
            this.markAllAsRead();
        });

        // Fermer le dropdown quand on clique ailleurs
        document.addEventListener('click', () => {
            this.closeDropdown();
        });

        // Empêcher la fermeture quand on clique dans le dropdown
        this.notificationDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Écouter la visibilité de la page pour économiser les ressources
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopPolling();
            } else {
                this.startPolling();
                this.updateNotifications();
            }
        });
    }

    initializeBadge() {
        // S'assurer que le badge existe et a le bon style
        if (this.notificationBadge) {
            this.notificationBadge.className = 'notification-badge';
            this.notificationBadge.style.display = this.notificationBadge.textContent > 0 ? 'flex' : 'none';
        }
    }

    startPolling() {
        if (!this.isPolling) {
            this.isPolling = true;
        }
    }

    stopPolling() {
        this.isPolling = false;
    }

    toggleDropdown() {
        if (this.notificationDropdown.classList.contains('show')) {
            this.closeDropdown();
        } else {
            this.openDropdown();
        }
    }

    openDropdown() {
        this.notificationDropdown.classList.add('show');
        this.isDropdownOpen = true;
        // Mettre à jour immédiatement quand on ouvre le dropdown
        this.updateNotifications();
    }

    closeDropdown() {
        this.notificationDropdown.classList.remove('show');
        this.isDropdownOpen = false;
    }

    async updateNotifications() {
        if (!this.isPolling) return;

        try {
            // Ajouter une classe de loading seulement si le dropdown est fermé
            if (!this.isDropdownOpen) {
                this.notificationIcon.classList.add('loading');
            }

            const response = await fetch('{{ route("admin.notifications.api") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();
            this.updateUI(data);

        } catch (error) {
            console.error('Error fetching notifications:', error);
        } finally {
            this.notificationIcon.classList.remove('loading');
        }
    }

    updateUI(data) {
        // Mettre à jour le badge
        this.updateBadge(data.unread_count);
        
        // Mettre à jour la liste des notifications
        this.updateNotificationList(data.notifications);
        
        // Mettre à jour l'état du bouton "Mark all as read"
        this.updateMarkAllReadButton(data.unread_count);
    }

    updateBadge(unreadCount) {
        const currentCount = this.notificationBadge ? parseInt(this.notificationBadge.textContent) : 0;
        
        if (unreadCount > 0) {
            if (this.notificationBadge) {
                // Animation si le nombre a changé
                if (currentCount !== unreadCount) {
                    this.notificationBadge.classList.add('new');
                    setTimeout(() => {
                        this.notificationBadge.classList.remove('new');
                    }, 600);
                }
                this.notificationBadge.textContent = unreadCount;
                this.notificationBadge.style.display = 'flex';
            } else {
                // Créer le badge s'il n'existe pas
                this.notificationBadge = document.createElement('span');
                this.notificationBadge.className = 'notification-badge new';
                this.notificationBadge.textContent = unreadCount;
                this.notificationIcon.appendChild(this.notificationBadge);
                
                setTimeout(() => {
                    this.notificationBadge.classList.remove('new');
                }, 600);
            }
        } else if (this.notificationBadge) {
            this.notificationBadge.style.display = 'none';
        }
    }

    updateNotificationList(notifications) {
        if (!this.notificationList) return;

        if (notifications.length === 0) {
            this.notificationList.innerHTML = `
                <div class="notification-item">
                    <div class="notification-content">
                        <p>No notifications yet.</p>
                    </div>
                </div>
            `;
            return;
        }

        this.notificationList.innerHTML = notifications.map(notification => `
            <a href="${notification.url}" 
               class="notification-item ${notification.is_unread ? 'unread' : ''}"
               onclick="markAsRead('${notification.id}', event)">
                <i class="fas fa-flag notification-icon-sm"></i>
                <div class="notification-content">
                    <p>${this.escapeHtml(notification.message)}</p>
                    <span class="notification-time">${notification.created_at}</span>
                </div>
            </a>
        `).join('');
    }

    updateMarkAllReadButton(unreadCount) {
        if (this.markAllReadBtn) {
            if (unreadCount > 0) {
                this.markAllReadBtn.disabled = false;
                this.markAllReadBtn.style.opacity = '1';
            } else {
                this.markAllReadBtn.disabled = true;
                this.markAllReadBtn.style.opacity = '0.6';
            }
        }
    }

    async markAllAsRead() {
        if (this.markAllReadBtn.disabled) return;

        try {
            // Désactiver le bouton pendant le traitement
            this.markAllReadBtn.disabled = true;
            this.markAllReadBtn.textContent = 'Marking...';

            const response = await fetch('{{ route("admin.notifications.markAllRead") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const result = await response.json();
            
            if (result.success) {
                // Mettre à jour l'interface immédiatement
                this.updateBadge(0);
                this.updateMarkAllReadButton(0);
                
                // Retirer la classe "unread" de toutes les notifications
                const unreadItems = this.notificationList.querySelectorAll('.notification-item.unread');
                unreadItems.forEach(item => {
                    item.classList.remove('unread');
                });

                // Afficher un message de succès
                this.showSuccessMessage('All notifications marked as read');
            }

        } catch (error) {
            console.error('Error marking all as read:', error);
            this.showErrorMessage('Error marking notifications as read');
        } finally {
            // Réactiver le bouton
            this.markAllReadBtn.disabled = false;
            this.markAllReadBtn.textContent = 'Mark all as read';
        }
    }

    showSuccessMessage(message) {
        this.showTemporaryMessage(message, 'success');
    }

    showErrorMessage(message) {
        this.showTemporaryMessage(message, 'error');
    }

    showTemporaryMessage(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.textContent = message;
        messageDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            background: ${type === 'success' ? '#28a745' : '#dc3545'};
            color: white;
            border-radius: 4px;
            z-index: 10000;
            font-size: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        `;
        
        document.body.appendChild(messageDiv);
        
        setTimeout(() => {
            messageDiv.remove();
        }, 3000);
    }

    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    destroy() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }
        this.isPolling = false;
    }
}

// Fonction pour marquer une notification comme lue
function markAsRead(notificationId, event) {
    if (event.metaKey || event.ctrlKey) return true;
    
    event.preventDefault();
    
    // Marquer comme lue visuellement immédiatement
    const notificationItem = event.currentTarget;
    notificationItem.classList.remove('unread');
    
    // Mettre à jour le compteur
    const badge = document.querySelector('.notification-badge');
    if (badge) {
        const currentCount = parseInt(badge.textContent);
        if (currentCount > 1) {
            badge.textContent = currentCount - 1;
        } else {
            badge.style.display = 'none';
        }
    }
    
    // Rediriger après un court délai
    setTimeout(() => {
        window.location.href = `/admin/notifications/${notificationId}/read`;
    }, 300);
}

// Initialiser quand le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('notificationIcon')) {
        window.realTimeNotifications = new RealTimeNotifications();
    }
});

// Nettoyer quand la page se ferme
window.addEventListener('beforeunload', function() {
    if (window.realTimeNotifications) {
        window.realTimeNotifications.destroy();
    }
});
</script>

@yield('scripts')
</body>
</html>