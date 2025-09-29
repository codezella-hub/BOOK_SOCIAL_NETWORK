<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Social Book Network - Partagez votre passion pour la lecture')</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user.css'])
    <!-- CSS Section -->
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @yield('styles')
</head>
<body>
<!-- Bande de bienvenue -->
<div class="welcome-bar">
    <div class="container">
        <p>Bienvenue sur Social Book Network - Partagez votre passion pour la lecture avec notre communauté !</p>
    </div>
</div>

<!-- Header -->
<header>
    <div class="container">
        <nav class="navbar">
            <div class="logo">
                <i class="fas fa-book-open logo-icon"></i>
                SocialBook
            </div>
            <ul class="nav-links">
                <li><a href="#"><i class="fas fa-home"></i> Accueil</a></li>
                <li><a href="#"><i class="fas fa-compass"></i> Découvrir</a></li>
                <li><a href="#"><i class="fas fa-users"></i> Communauté</a></li>
                <li class="dropdown">
                    <a href="#"><i class="fas fa-ellipsis-h"></i> Plus <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-content">
                        <a href="#"><i class="fas fa-blog"></i> Blog</a>
                        <a href="/#events">Events</a>
                        <a href="#"><i class="fas fa-question-circle"></i> Aide</a>
                        <a href="#"><i class="fas fa-info-circle"></i> À propos</a>
                    </div>
                </li>
            </ul>
            <div class="nav-icons">
                <div class="icon-btn" id="search-btn">
                    <i class="fas fa-search"></i>
                </div>


                @auth
                    <div class="icon-btn" id="notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <!-- Utilisateur connecté - Afficher l'avatar et le menu déroulant -->
                    <div class="user-dropdown">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-dropdown-content">
                            <a href="{{ route('profile.edit') }}"><i class="fas fa-user-circle"></i> Mon Profil</a>
                            <a href="#"><i class="fas fa-cog"></i> Paramètres</a>

                            <!-- Item Admin Panel conditionnel avec Spatie Permission -->
                            @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Admin Panel</a>
                            @endif

                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Utilisateur non connecté - Afficher le bouton login -->
                    <div class="auth-buttons">
                        <a href="{{ route('login') }}" class="btn btn-outline login-btn">
                            <i class="fas fa-sign-in-alt"></i> Connexion
                        </a>
                        <a href="{{ route('register') }}" class="btn" style="margin-left: 10px;">
                            <i class="fas fa-user-plus"></i> Inscription
                        </a>
                    </div>
                @endauth
            </div>
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </div>
</header>

<!-- Search Overlay -->
<div class="search-overlay" id="search-overlay">
    <div class="search-container">
        <div class="search-header">
            <h3>Rechercher des livres</h3>
            <button class="close-search" id="close-search"><i class="fas fa-times"></i></button>
        </div>
        <form class="search-form">
            <input type="text" placeholder="Titre, auteur, genre...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
        <div class="search-results">
            <div class="search-result-item">
                <h4>L'Étranger</h4>
                <p>Albert Camus • Roman • 1942</p>
            </div>
            <div class="search-result-item">
                <h4>1984</h4>
                <p>George Orwell • Science-fiction • 1949</p>
            </div>
            <div class="search-result-item">
                <h4>Le Petit Prince</h4>
                <p>Antoine de Saint-Exupéry • Conte • 1943</p>
            </div>
        </div>
    </div>
</div>

<!-- Notification Overlay -->
<div class="notification-overlay" id="notification-overlay">
    <div class="notification-container">
        <div class="notification-header">
            <h3>Notifications</h3>
            <button class="close-notification" id="close-notification"><i class="fas fa-times"></i></button>
        </div>
        <div class="notification-list">
            <div class="notification-item">
                <div class="notification-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="notification-content">
                    <p><strong>Marie Dupont</strong> a rejoint votre club de lecture</p>
                    <span class="notification-time">Il y a 2 heures</span>
                </div>
            </div>
            <div class="notification-item">
                <div class="notification-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="notification-content">
                    <p>Votre livre "<strong>L'Étranger</strong>" a reçu un nouvel avis</p>
                    <span class="notification-time">Il y a 5 heures</span>
                </div>
            </div>
            <div class="notification-item">
                <div class="notification-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="notification-content">
                    <p>Nouvel événement: Club de lecture sur la science-fiction</p>
                    <span class="notification-time">Il y a 1 jour</span>
                </div>
            </div>
        </div>
        <div class="notification-footer">
            <a href="#" class="btn btn-light">Voir toutes les notifications</a>
        </div>
    </div>
</div>

<!-- Main Content Section -->
@yield('content')

<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-column">
                <h3>Social Book</h3>
                <p>La plateforme de partage de livres pour les passionnés de lecture.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-column">
                <h3>Liens rapides</h3>
                <ul>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Accueil</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Découvrir</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Communauté</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Blog</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> À propos</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Catégories</h3>
                <ul>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Fiction</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Non-fiction</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Science-fiction</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Fantasy</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Polar</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Contact</h3>
                <ul>
                    <li><i class="fas fa-envelope"></i> contact@socialbook.net</li>
                    <li><i class="fas fa-phone"></i> +33 1 23 45 67 89</li>
                    <li><i class="fas fa-map-marker-alt"></i> 123 Rue des Livres, Paris</li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; {{ date('Y') }} Social Book Network. Tous droits réservés.</p>
        </div>
    </div>
</footer>

<!-- JavaScript Section -->
@vite(['resources/js/user.js'])

@yield('scripts')
</body>
</html>
