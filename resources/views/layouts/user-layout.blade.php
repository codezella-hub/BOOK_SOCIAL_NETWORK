<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Social Book Network - Partagez votre passion pour la lecture')</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Section -->
    @vite(['resources/css/user.css'])
    <style>
.forum-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.forum-header {
    text-align: center;
    margin-bottom: 2rem;
}

.forum-header h1 {
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.forum-header p {
    color: #718096;
    margin-bottom: 1.5rem;
}

.posts-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.post-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.post-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 40px;
    height: 40px;
    background: #3b82f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.user-details h4 {
    margin: 0;
    color: #2d3748;
    font-size: 1rem;
}

.post-time {
    color: #718096;
    font-size: 0.875rem;
}

.topic-badge {
    background: #e2e8f0;
    color: #4a5568;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.post-content {
    margin-bottom: 1rem;
}

.post-content p {
    color: #4a5568;
    line-height: 1.6;
    margin: 0;
}

.post-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #4a5568;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #718096;
    margin-bottom: 1.5rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.375rem;
    text-decoration: none;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-info {
    background: #06b6d4;
    color: white;
}

.btn-info:hover {
    background: #0891b2;
}

.btn-warning {
    background: #f59e0b;
    color: white;
}

.btn-warning:hover {
    background: #d97706;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

.alert {
    padding: 1rem;
    border-radius: 0.375rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}
.post-form-container {
    max-width: 600px;
    margin: 0 auto;
    background: white;
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
}

.form-label.required::after {
    content: " *";
    color: #ef4444;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 1rem;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.char-count {
    text-align: right;
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

.error-message {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}
.post-detail-container {
    max-width: 800px;
    margin: 0 auto;
}

.main-post {
    background: white;
    border-radius: 0.75rem;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.post-header {
    margin-bottom: 1.5rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-avatar {
    width: 50px;
    height: 50px;
    background: #3b82f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.user-avatar-sm {
    width: 35px;
    height: 35px;
    background: #6b7280;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
}

.user-details h3 {
    margin: 0;
    color: #2d3748;
    font-size: 1.25rem;
}

.user-details h4 {
    margin: 0;
    color: #2d3748;
    font-size: 1rem;
}

.post-time, .comment-time {
    color: #718096;
    font-size: 0.875rem;
}

.topic-badge {
    background: #e2e8f0;
    color: #4a5568;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.post-content {
    margin-bottom: 1.5rem;
}

.post-content p {
    color: #4a5568;
    line-height: 1.7;
    font-size: 1.1rem;
    margin: 0;
}

.post-stats {
    display: flex;
    gap: 2rem;
    border-top: 1px solid #e2e8f0;
    padding-top: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #718096;
    font-size: 0.875rem;
}

.comments-section {
    background: white;
    border-radius: 0.75rem;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.comments-section h3 {
    color: #2d3748;
    margin-bottom: 1.5rem;
    font-size: 1.25rem;
}

.comments-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.comment-item {
    padding: 1.5rem;
    border: 1px solid #f1f5f9;
    border-radius: 0.5rem;
    background: #f8fafc;
}

.comment-header {
    margin-bottom: 0.75rem;
}

.comment-content p {
    color: #4a5568;
    line-height: 1.6;
    margin: 0;
}

.empty-comments {
    text-align: center;
    padding: 3rem 2rem;
    color: #718096;
}

.empty-comments i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.comment-form {
    border-top: 1px solid #e2e8f0;
    padding-top: 1.5rem;
}

.comment-form h4 {
    color: #2d3748;
    margin-bottom: 1rem;
}

.post-actions-section {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.btn-outline {
    background: transparent;
    border: 1px solid #d1d5db;
    color: #4b5563;
    padding: 0.5rem 1rem;
}

.btn-outline:hover {
    background: #f3f4f6;
}

.danger-zone {
    border-top: 1px solid #fecaca;
    padding-top: 1.5rem;
}

.danger-zone h4 {
    color: #dc2626;
    margin-bottom: 0.5rem;
}

.danger-zone p {
    color: #7f1d1d;
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

.alert {
    padding: 1rem;
    border-radius: 0.375rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}
.comment-form-container {
    max-width: 600px;
    margin: 0 auto;
    background: white;
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
}

.form-label.required::after {
    content: " *";
    color: #ef4444;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 1rem;
    resize: vertical;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.char-count {
    text-align: right;
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

.error-message {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}
.comment-actions {
    display: flex;
    gap: 0.25rem;
}

.btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    min-height: auto;
}
.like-form {
    margin: 0;
}

.like-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid #d1d5db;
    background: transparent;
    color: #4b5563;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.like-btn:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.like-btn .like-icon {
    color: #6b7280;
    transition: all 0.3s ease;
}

.like-btn[data-liked="true"] .like-icon {
    color: #ef4444;
}

.like-btn:hover .like-icon {
    transform: scale(1.1);
}

.action-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.btn-outline {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid #d1d5db;
    background: transparent;
    color: #4b5563;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-outline:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

</style>
    @stack('styles')
    
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
                <li><a href="{{ route('user.home') }}"><i class="fas fa-home"></i> Accueil</a></li>
                <li><a href="#"><i class="fas fa-compass"></i> Découvrir</a></li>
                <li><a href="{{ route('user.posts.index') }}"><i class="fas fa-comments"></i> Forum</a></li>
                <li><a href="#"><i class="fas fa-users"></i> Communauté</a></li>
                <li class="dropdown">
                    <a href="#"><i class="fas fa-ellipsis-h"></i> Plus <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-content">
                        <a href="#"><i class="fas fa-blog"></i> Blog</a>
                        <a href="#"><i class="fas fa-calendar-alt"></i> Événements</a>
                        <a href="#"><i class="fas fa-question-circle"></i> Aide</a>
                        <a href="#"><i class="fas fa-info-circle"></i> À propos</a>
                    </div>
                </li>
            </ul>
            <div class="nav-icons">
                <div class="icon-btn" id="search-btn">
                    <i class="fas fa-search"></i>
                </div>
                <div class="icon-btn" id="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>

                <div class="user-dropdown">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-dropdown-content">
                        <a href="#"><i class="fas fa-user-circle"></i> Mon Profil</a>
                        <a href="#"><i class="fas fa-cog"></i> Paramètres</a>
                        <a href="#"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                    </div>
                </div>
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
<main>
    @yield('content')
</main>

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
                    <li><a href="{{ route('user.posts.index') }}"><i class="fas fa-chevron-right"></i> Forum</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Communauté</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Blog</a></li>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Compteur pour les posts (create/edit)
    const contentP = document.getElementById('content_P');
    const charCount = document.getElementById('charCount');
    
    if (contentP && charCount) {
        contentP.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;
        });
    }

    // Compteur pour les commentaires (create/edit)
    const contentC = document.getElementById('content_C');
    const charCountComment = document.getElementById('charCount');
    
    if (contentC && charCountComment) {
        contentC.addEventListener('input', function() {
            const length = this.value.length;
            charCountComment.textContent = length;
        });
    }

    // ========== SYSTÈME DE LIKES ==========
    const likeForms = document.querySelectorAll('.like-form');
    
    likeForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = form.querySelector('.like-btn');
            const likeIcon = form.querySelector('.like-icon');
            const likeText = form.querySelector('.like-text');
            const likesCount = form.querySelector('.likes-count');
            const currentLiked = button.dataset.liked === 'true';
            
            // Désactiver le bouton pendant la requête
            button.disabled = true;
            
            // Envoyer la requête AJAX
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour le data-attribute
                    button.dataset.liked = data.liked.toString();
                    
                    // Mettre à jour l'icône
                    if (data.liked) {
                        likeIcon.style.color = '#ef4444';
                        likeIcon.classList.remove('far');
                        likeIcon.classList.add('fas');
                    } else {
                        likeIcon.style.color = '#6b7280';
                        likeIcon.classList.remove('fas');
                        likeIcon.classList.add('far');
                    }
                    
                    // Mettre à jour le texte
                    likeText.textContent = data.liked ? 'Unlike' : 'Like';
                    
                    // Mettre à jour le compteur
                    likesCount.textContent = `(${data.likes_count})`;
                    
                    // Mettre à jour aussi le compteur dans les stats
                    const statsLikes = document.querySelector('.post-stats .stat-item:nth-child(1) span');
                    if (statsLikes) {
                        statsLikes.textContent = `${data.likes_count} likes`;
                    }
                    
                    // Afficher un message toast
                    showToast(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Une erreur est survenue', 'error');
            })
            .finally(() => {
                // Réactiver le bouton
                button.disabled = false;
            });
        });
    });
    
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        const bgColor = type === 'success' ? '#10b981' : '#ef4444';
        
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${bgColor};
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            z-index: 1000;
            animation: slideIn 0.3s ease;
            font-family: inherit;
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        `;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 2000);
    }
    
    // Ajouter les animations CSS
    if (!document.querySelector('#like-styles')) {
        const style = document.createElement('style');
        style.id = 'like-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            
            .like-btn {
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                border: 1px solid #d1d5db;
                background: transparent;
                color: #4b5563;
                padding: 0.5rem 1rem;
                border-radius: 0.375rem;
                cursor: pointer;
                text-decoration: none;
            }
            
            .like-btn:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }
            
            .like-btn:hover:not(:disabled) {
                background: #f3f4f6;
                border-color: #9ca3af;
            }
            
            .like-btn:hover:not(:disabled) .like-icon {
                transform: scale(1.1);
            }
            
            .like-icon {
                transition: all 0.3s ease;
            }
            
            .action-buttons {
                display: flex;
                gap: 1rem;
                margin-bottom: 1.5rem;
                flex-wrap: wrap;
            }
            
            .btn-outline {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                border: 1px solid #d1d5db;
                background: transparent;
                color: #4b5563;
                padding: 0.5rem 1rem;
                border-radius: 0.375rem;
                cursor: pointer;
                text-decoration: none;
                transition: all 0.3s ease;
                font-family: inherit;
                font-size: 14px;
            }
            
            .btn-outline:hover {
                background: #f3f4f6;
                border-color: #9ca3af;
            }
        `;
        document.head.appendChild(style);
    }
});
</script>

@stack('scripts')
@yield('scripts')
</body>
</html>
