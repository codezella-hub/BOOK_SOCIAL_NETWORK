document.addEventListener('DOMContentLoaded', function() {
    console.log('Profile JS loaded successfully');

    // Gestion du modal de suppression
    function initDeleteModal() {
        const deleteButton = document.querySelector('button[data-modal-toggle="delete-user-modal"]');
        const deleteModal = document.getElementById('delete-user-modal');
        const closeButton = deleteModal ? deleteModal.querySelector('.close') : null;
        const cancelButton = deleteModal ? deleteModal.querySelector('.btn-secondary') : null;

        if (deleteButton && deleteModal) {
            // Ouvrir le modal
            deleteButton.addEventListener('click', function(e) {
                e.preventDefault();
                deleteModal.style.display = 'block';
                document.body.style.overflow = 'hidden'; // Empêcher le scroll
            });

            // Fermer le modal
            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    deleteModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                });
            }

            if (cancelButton) {
                cancelButton.addEventListener('click', function() {
                    deleteModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                });
            }

            // Fermer en cliquant en dehors du modal
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    deleteModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });

            // Fermer avec la touche Échap
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && deleteModal.style.display === 'block') {
                    deleteModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        }
    }

    // Navigation smooth scroll
    const navLinks = document.querySelectorAll('.sidebar-nav a[href^="#"]');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                navLinks.forEach(navLink => {
                    navLink.parentElement.classList.remove('active');
                });

                this.parentElement.classList.add('active');

                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Gestion des formulaires
    const forms = document.querySelectorAll('.profile-card form');

    forms.forEach(form => {
        // Éviter les conflits avec le formulaire de suppression
        if (!form.closest('#delete-account')) {
            const inputs = form.querySelectorAll('input[required], textarea[required]');

            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });

                input.addEventListener('input', function() {
                    clearFieldError(this);
                });
            });

            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn && submitBtn.type === 'submit') {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
                }
            });
        }
    });

    function validateField(field) {
        const errorElement = field.parentElement.querySelector('.text-red-600');

        if (!field.checkValidity()) {
            field.style.borderColor = '#dc2626';
            if (errorElement) {
                errorElement.style.display = 'block';
            }
        } else {
            field.style.borderColor = '#16a34a';
            if (errorElement) {
                errorElement.style.display = 'none';
            }
        }
    }

    function clearFieldError(field) {
        const errorElement = field.parentElement.querySelector('.text-red-600');
        field.style.borderColor = '#e0e0e0';
        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }

    // Toggle pour les mots de passe
    const passwordToggles = document.querySelectorAll('.absolute[type="button"]');

    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const passwordInput = this.previousElementSibling;
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Menu mobile
    function setupMobileMenu() {
        const sidebar = document.querySelector('.profile-sidebar');
        const contentHeader = document.querySelector('.content-header');

        if (window.innerWidth <= 992) {
            let mobileToggle = document.querySelector('.sidebar-mobile-toggle');

            if (!mobileToggle) {
                mobileToggle = document.createElement('button');
                mobileToggle.className = 'sidebar-mobile-toggle';
                mobileToggle.innerHTML = '<i class="fas fa-bars"></i> Menu du profil';
                mobileToggle.style.cssText = `
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    justify-content: center;
                    width: 100%;
                    padding: 15px;
                    background: #000;
                    color: white;
                    border: none;
                    border-radius: 8px;
                    margin-bottom: 20px;
                    cursor: pointer;
                    font-size: 16px;
                `;

                if (contentHeader) {
                    contentHeader.parentNode.insertBefore(mobileToggle, contentHeader);
                }
            }

            sidebar.style.display = 'none';
            mobileToggle.style.display = 'flex';

            mobileToggle.addEventListener('click', function() {
                if (sidebar.style.display === 'none') {
                    sidebar.style.display = 'block';
                    mobileToggle.innerHTML = '<i class="fas fa-times"></i> Fermer le menu';
                } else {
                    sidebar.style.display = 'none';
                    mobileToggle.innerHTML = '<i class="fas fa-bars"></i> Menu du profil';
                }
            });
        } else {
            sidebar.style.display = 'block';
            const mobileToggle = document.querySelector('.sidebar-mobile-toggle');
            if (mobileToggle) {
                mobileToggle.style.display = 'none';
            }
        }
    }

    // Initialisation
    initDeleteModal();
    setupMobileMenu();
    window.addEventListener('resize', setupMobileMenu);
});
