<!-- resources/views/layouts/auth-layout.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentification - Social Book Network')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        /* Variables CSS */
        :root {
            --primary-color: #000000;
            --secondary-color: #333333;
            --accent-color: #ffffff;
            --light-color: #f5f5f5;
            --dark-color: #1a1a1a;
            --text-color: #333333;
            --text-light: #666666;
            --gray-light: #e0e0e0;
            --gray-medium: #cccccc;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --error-color: #e74c3c;
            --info-color: #3498db;
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 10px 25px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s ease;
            --border-radius: 8px;
        }

        /* Reset et base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', 'Segoe UI', sans-serif;
            background: var(--accent-color);
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
        }

        /* Container principal split-screen */
        .auth-split-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Partie gauche (noire) */
        .auth-left {
            flex: 1;
            background: var(--primary-color);
            color: var(--accent-color);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .auth-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="books" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23books)"/></svg>');
            opacity: 0.1;
        }

        .auth-left-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .auth-logo-icon {
            font-size: 48px;
            color: var(--accent-color);
        }

        .auth-logo-text {
            font-size: 32px;
            font-weight: 700;
            color: var(--accent-color);
        }

        .auth-left-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--accent-color);
        }

        .auth-left-subtitle {
            font-size: 16px;
            opacity: 0.8;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .auth-features {
            text-align: left;
            margin-top: 40px;
        }

        .auth-feature {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .auth-feature:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .feature-text h4 {
            margin-bottom: 5px;
            font-size: 16px;
        }

        .feature-text p {
            font-size: 14px;
            opacity: 0.8;
        }

        /* Partie droite (blanche) */
        .auth-right {
            flex: 1;
            background: var(--accent-color);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            overflow-y: auto;
        }

        .auth-form-container {
            width: 100%;
            max-width: 400px;
            animation: slideInRight 0.6s ease;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .auth-subtitle {
            font-size: 14px;
            color: var(--text-light);
        }

        /* Styles des formulaires */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label i {
            color: var(--primary-color);
            width: 16px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 2px solid var(--gray-light);
            border-radius: var(--border-radius);
            font-size: 14px;
            transition: var(--transition);
            background: var(--accent-color);
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

        .form-input.error {
            border-color: var(--error-color);
            animation: shake 0.3s ease-in-out;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .password-toggle {
            position: absolute;
            right: 35px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .error-message {
            color: var(--error-color);
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: var(--primary-color);
            color: var(--accent-color);
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: inherit;
        }

        .submit-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .submit-btn:disabled {
            background: var(--gray-light);
            color: var(--text-light);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .auth-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-light);
        }

        .auth-footer p {
            margin: 0;
            color: var(--text-light);
            font-size: 14px;
        }

        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        /* Bouton Retour à l'accueil */
        .home-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.1);
            color: var(--accent-color);
            border: none;
            border-radius: var(--border-radius);
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-family: inherit;
            backdrop-filter: blur(10px);
        }

        .home-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .home-btn.right {
            left: auto;
            right: 20px;
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-color);
        }

        .home-btn.right:hover {
            background: rgba(0, 0, 0, 0.1);
        }

        /* Messages d'état */
        .auth-status {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
            text-align: center;
            font-weight: 500;
        }

        .auth-status.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .auth-status.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .auth-status.info {
            background: #e3f2fd;
            color: #1565c0;
            border: 1px solid #bbdefb;
        }

        /* Animations */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-split-container {
                flex-direction: column;
            }

            .auth-left {
                padding: 30px 20px;
                min-height: auto;
            }

            .auth-right {
                padding: 30px 20px;
            }

            .auth-logo {
                margin-bottom: 20px;
            }

            .auth-logo-icon {
                font-size: 36px;
            }

            .auth-logo-text {
                font-size: 24px;
            }

            .auth-left-title {
                font-size: 22px;
            }

            .auth-features {
                display: none;
            }

            .home-btn {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 20px;
                align-self: flex-start;
            }

            .home-btn.right {
                position: relative;
                top: auto;
                right: auto;
                margin-bottom: 20px;
                align-self: flex-start;
            }
        }

        /* Styles spécifiques pour les indicateurs de processus */
        .process-steps {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .step {
            text-align: center;
            flex: 1;
        }

        .step-icon {
            width: 30px;
            height: 30px;
            background: var(--gray-light);
            color: var(--text-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 5px;
            font-size: 12px;
            transition: var(--transition);
        }

        .step.active .step-icon {
            background: var(--primary-color);
            color: var(--accent-color);
        }

        .step.completed .step-icon {
            background: var(--success-color);
            color: var(--accent-color);
        }

        .step span {
            font-size: 11px;
            color: var(--text-light);
        }

        .step.active span {
            color: var(--primary-color);
            font-weight: 600;
        }
    </style>

    <!-- Styles supplémentaires de la page -->
    @yield('styles')
</head>
<body>
<div class="auth-split-container">
    <!-- Partie gauche (noire) -->
    <div class="auth-left">
        <!-- Bouton Retour à l'accueil (côté gauche) -->
        <a href="{{ url('/') }}" class="home-btn">
            <i class="fas fa-arrow-left"></i>
            Retour à l'accueil
        </a>

        <div class="auth-left-content">
            <!-- Logo et titre -->
            <div class="auth-logo">
                <i class="fas fa-book-open auth-logo-icon"></i>
                <span class="auth-logo-text">SocialBook</span>
            </div>

            <!-- Titre et sous-titre de la page -->
            <h2 class="auth-left-title">@yield('left-title', 'Rejoignez notre communauté')</h2>
            <p class="auth-left-subtitle">@yield('left-subtitle', 'Partagez votre passion pour la lecture avec des milliers de lecteurs passionnés')</p>

            <!-- Section features -->
            <div class="auth-features">
                <div class="auth-feature">
                    <div class="feature-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Bibliothèque personnelle</h4>
                        <p>Gérez votre collection de livres</p>
                    </div>
                </div>
                <div class="auth-feature">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Communauté active</h4>
                        <p>Échangez avec d'autres lecteurs</p>
                    </div>
                </div>
                <div class="auth-feature">
                    <div class="feature-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Recommandations</h4>
                        <p>Découvrez de nouvelles lectures</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Partie droite (blanche) -->
    <div class="auth-right">
        <!-- Bouton Retour à l'accueil (côté droit - version alternative) -->
        <a href="{{ url('/') }}" class="home-btn right">
            <i class="fas fa-home"></i>
            Accueil
        </a>

        <div class="auth-form-container">
            <!-- En-tête du formulaire -->
            <div class="auth-header">
                <h1 class="auth-title">@yield('page-title', 'Connexion')</h1>
                <p class="auth-subtitle">@yield('page-subtitle', 'Accédez à votre compte')</p>
            </div>

            <!-- Indicateur d'étapes (optionnel) -->
            @hasSection('process-steps')
                <div class="process-steps">
                    @yield('process-steps')
                </div>
            @endif

            <!-- Messages de session -->
            @if(session('status'))
                <div class="auth-status success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="auth-status error">
                    <i class="fas fa-exclamation-circle"></i>
                    @foreach($errors->all() as $error)
                        {{ $error }}@if(!$loop->last)<br>@endif
                    @endforeach
                </div>
            @endif

            <!-- Contenu spécifique à la page -->
            @yield('content')
        </div>
    </div>
</div>

<!-- Scripts communs -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const setupPasswordToggle = (toggleClass, inputId) => {
            const toggle = document.querySelector(toggleClass);
            const input = document.getElementById(inputId);

            if (toggle && input) {
                toggle.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
                });
            }
        };

        // Auto-hide status messages
        const statusMessages = document.querySelectorAll('.auth-status');
        statusMessages.forEach(message => {
            setTimeout(() => {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    if (message.parentNode) {
                        message.parentNode.removeChild(message);
                    }
                }, 500);
            }, 5000);
        });

        // Loading state for forms
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('.submit-btn');
                if (submitBtn && !submitBtn.disabled) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
                    submitBtn.disabled = true;

                    // Restore after 30 seconds timeout
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 30000);
                }
            });
        });

        // Auto-focus on first input
        const firstInput = document.querySelector('input[autofocus]');
        if (firstInput) {
            firstInput.focus();
        }
    });

    // Fonction pour l'indicateur de force du mot de passe
    function setupPasswordStrength(inputId, strengthBarId, strengthTextId) {
        const passwordInput = document.getElementById(inputId);
        const strengthFill = document.getElementById(strengthBarId);
        const strengthLevel = document.getElementById(strengthTextId);

        if (passwordInput && strengthFill && strengthLevel) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                const hasLength = password.length >= 8;
                const hasUppercase = /[A-Z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSpecial = /[^A-Za-z0-9]/.test(password);

                if (hasLength) strength += 25;
                if (hasUppercase) strength += 25;
                if (hasNumber) strength += 25;
                if (hasSpecial) strength += 25;

                strengthFill.className = 'strength-fill';
                strengthLevel.className = 'strength-level';

                if (strength <= 25) {
                    strengthFill.style.width = '25%';
                    strengthFill.style.background = 'var(--error-color)';
                    strengthLevel.textContent = 'Faible';
                    strengthLevel.style.color = 'var(--error-color)';
                } else if (strength <= 50) {
                    strengthFill.style.width = '50%';
                    strengthFill.style.background = 'var(--warning-color)';
                    strengthLevel.textContent = 'Moyen';
                    strengthLevel.style.color = 'var(--warning-color)';
                } else if (strength <= 75) {
                    strengthFill.style.width = '75%';
                    strengthFill.style.background = 'var(--success-color)';
                    strengthLevel.textContent = 'Fort';
                    strengthLevel.style.color = 'var(--success-color)';
                } else {
                    strengthFill.style.width = '100%';
                    strengthFill.style.background = '#2ecc71';
                    strengthLevel.textContent = 'Très fort';
                    strengthLevel.style.color = '#2ecc71';
                }
            });
        }
    }
</script>

<!-- Scripts supplémentaires de la page -->
@yield('scripts')
</body>
</html>
