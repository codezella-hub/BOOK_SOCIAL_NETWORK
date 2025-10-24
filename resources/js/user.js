document.addEventListener('DOMContentLoaded', function() {
    console.log('User JS loaded successfully');

    // Animation au défilement
    const fadeElements = document.querySelectorAll('.fade-in');

    const fadeInOnScroll = () => {
        fadeElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;

            if (elementTop < window.innerHeight - elementVisible) {
                element.classList.add('visible');
            }
        });
    };

    window.addEventListener('scroll', fadeInOnScroll);
    window.addEventListener('load', fadeInOnScroll);

    // Menu mobile toggle
    const menuToggle = document.querySelector('.menu-toggle');
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            const navLinks = document.querySelector('.nav-links');
            const navIcons = document.querySelector('.nav-icons');

            if (navLinks && navIcons) {
                navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
                navIcons.style.display = navIcons.style.display === 'flex' ? 'none' : 'flex';

                if (window.innerWidth <= 768) {
                    if (navLinks.style.display === 'flex') {
                        navLinks.style.flexDirection = 'column';
                        navIcons.style.flexDirection = 'column';
                        navLinks.style.position = 'absolute';
                        navLinks.style.top = '80px';
                        navLinks.style.left = '0';
                        navLinks.style.right = '0';
                        navLinks.style.background = 'white';
                        navLinks.style.padding = '20px';
                        navLinks.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
                        navIcons.style.position = 'absolute';
                        navIcons.style.top = '320px';
                        navIcons.style.left = '0';
                        navIcons.style.right = '0';
                        navIcons.style.background = 'white';
                        navIcons.style.padding = '20px';
                        navIcons.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
                    }
                }
            }
        });
    }

    // Existing init for books
initCarousel('#books-track', '.carousel-dot');

// Add this line for events carousel:
initCarousel('#events-track', '#events-dots .carousel-dot');


    // Ajustement du menu en cas de redimensionnement
    window.addEventListener('resize', function() {
        const navLinks = document.querySelector('.nav-links');
        const navIcons = document.querySelector('.nav-icons');

        if (navLinks && navIcons) {
            if (window.innerWidth > 768) {
                navLinks.style.display = 'flex';
                navIcons.style.display = 'flex';
                navLinks.style.flexDirection = 'row';
                navIcons.style.flexDirection = 'row';
                navLinks.style.position = 'static';
                navLinks.style.background = 'transparent';
                navLinks.style.padding = '0';
                navLinks.style.boxShadow = 'none';
                navIcons.style.position = 'static';
                navIcons.style.background = 'transparent';
                navIcons.style.padding = '0';
                navIcons.style.boxShadow = 'none';
            } else {
                navLinks.style.display = 'none';
                navIcons.style.display = 'none';
            }
        }
    });

    // Gestion de la recherche
    const searchBtn = document.getElementById('search-btn');
    const searchOverlay = document.getElementById('search-overlay');
    const closeSearch = document.getElementById('close-search');

    if (searchBtn && searchOverlay && closeSearch) {
        searchBtn.addEventListener('click', function() {
            searchOverlay.style.display = 'block';
        });

        closeSearch.addEventListener('click', function() {
            searchOverlay.style.display = 'none';
        });

        searchOverlay.addEventListener('click', function(e) {
            if (e.target === searchOverlay) {
                searchOverlay.style.display = 'none';
            }
        });
    }

    // Gestion des notifications
    const notificationBtn = document.getElementById('notification-btn');
    const notificationOverlay = document.getElementById('notification-overlay');
    const closeNotification = document.getElementById('close-notification');

    if (notificationBtn && notificationOverlay && closeNotification) {
        notificationBtn.addEventListener('click', function() {
            notificationOverlay.style.display = 'block';
        });

        closeNotification.addEventListener('click', function() {
            notificationOverlay.style.display = 'none';
        });

        notificationOverlay.addEventListener('click', function(e) {
            if (e.target === notificationOverlay) {
                notificationOverlay.style.display = 'none';
            }
        });
    }

    // Carrousel des catégories
    const categoriesTrack = document.getElementById('categories-track');
    const categoryCards = document.querySelectorAll('.category-card');
    const categoryDots = document.querySelectorAll('.categories-carousel .carousel-dot');

    let currentCategorySlide = 0;
    const categorySlidesToShow = 4;

    function updateCategoriesCarousel() {
        if (categoriesTrack && categoryCards.length > 0) {
            const cardWidth = categoryCards[0].offsetWidth + 20; // width + gap
            const translateX = -currentCategorySlide * cardWidth * categorySlidesToShow;
            categoriesTrack.style.transform = `translateX(${translateX}px)`;

            // Mettre à jour les dots actifs
            categoryDots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentCategorySlide);
            });
        }
    }

    categoryDots.forEach(dot => {
        dot.addEventListener('click', function() {
            currentCategorySlide = parseInt(this.getAttribute('data-slide'));
            updateCategoriesCarousel();
        });
    });

    // Carrousel des livres
    const booksTrack = document.getElementById('books-track');
    const bookCards = document.querySelectorAll('.book-card');
    const bookDots = document.querySelectorAll('.books-carousel .carousel-dot');

    let currentBookSlide = 0;
    const bookSlidesToShow = 4;

    function updateBooksCarousel() {
        if (booksTrack && bookCards.length > 0) {
            const cardWidth = bookCards[0].offsetWidth + 30; // width + gap
            const translateX = -currentBookSlide * cardWidth * bookSlidesToShow;
            booksTrack.style.transform = `translateX(${translateX}px)`;

            // Mettre à jour les dots actifs
            bookDots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentBookSlide);
            });
        }
    }

    bookDots.forEach(dot => {
        dot.addEventListener('click', function() {
            currentBookSlide = parseInt(this.getAttribute('data-slide'));
            updateBooksCarousel();
        });
    });

    // Initialiser les carrousels
    window.addEventListener('load', function() {
        updateCategoriesCarousel();
        updateBooksCarousel();
    });

    // Ajuster les carrousels au redimensionnement
    window.addEventListener('resize', function() {
        updateCategoriesCarousel();
        updateBooksCarousel();
    });

    // Gestion des favoris
    const favoriteButtons = document.querySelectorAll('.book-favorite, .action-btn .fa-heart');
    favoriteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const icon = this.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                icon.style.color = '#ff4757';
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                icon.style.color = '';
            }
        });
    });

    // Gestion du menu déroulant utilisateur - CORRECTION
    const userDropdown = document.querySelector('.user-dropdown');
    const userAvatar = document.querySelector('.user-avatar');

    if (userDropdown && userAvatar) {
        let closeTimeout;

        // Ouvrir/fermer le menu au clic sur l'avatar
        userAvatar.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');

            // Annuler tout timeout en cours
            clearTimeout(closeTimeout);
        });

        // Garder le menu ouvert lors du survol
        userDropdown.addEventListener('mouseenter', function() {
            clearTimeout(closeTimeout);
            userDropdown.classList.add('active');
        });

        userDropdown.addEventListener('mouseleave', function(e) {
            // Délai avant de fermer pour permettre à l'utilisateur de passer à l'élément suivant
            closeTimeout = setTimeout(() => {
                if (!userDropdown.matches(':hover')) {
                    userDropdown.classList.remove('active');
                }
            }, 300);
        });

        // Fermer le menu en cliquant ailleurs
        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target)) {
                userDropdown.classList.remove('active');
            }
        });

        // Empêcher la fermeture lors du clic sur les liens du menu
        const dropdownLinks = document.querySelectorAll('.user-dropdown-content a');
        dropdownLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.stopPropagation();
                // Optionnel: fermer le menu après le clic
                // userDropdown.classList.remove('active');
            });
        });
    }
});
