<!-- resources/views/auth/register.blade.php -->
@extends('layouts.auth-layout')

@section('title', 'Inscription - Social Book Network')

@section('left-title', 'Rejoignez notre communauté !')
@section('left-subtitle', 'Créez votre compte pour découvrir des milliers de livres, rejoindre des clubs de lecture et partager vos avis.')

@section('page-title', 'Créer un compte')
@section('page-subtitle', 'Inscription rapide en 2 minutes')

@section('styles')
    <style>
        .password-strength {
            margin-top: 10px;
        }

        .strength-bar {
            height: 6px;
            background: var(--gray-light);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .strength-text {
            font-size: 12px;
            color: var(--text-light);
            display: flex;
            justify-content: space-between;
        }

        .strength-fill.weak { background: var(--error-color); width: 25%; }
        .strength-fill.medium { background: var(--warning-color); width: 50%; }
        .strength-fill.strong { background: var(--success-color); width: 75%; }
        .strength-fill.very-strong { background: #2ecc71; width: 100%; }

        .password-match.valid { color: var(--success-color) !important; font-weight: 600; }
        .password-match.invalid { color: var(--error-color) !important; font-weight: 600; }

        .terms-label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            cursor: pointer;
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 20px;
        }

        .terms-label a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .terms-label a:hover {
            text-decoration: underline;
        }
    </style>
@endsection

@section('content')
    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="form-label">
                <i class="fas fa-user"></i>
                Nom complet
            </label>
            <div class="input-wrapper">
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       placeholder="Votre nom complet" class="form-input @error('name') error @enderror">
                <i class="fas fa-user-circle"></i>
            </div>
            @error('name')
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>{{ $message }}
            </div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">
                <i class="fas fa-envelope"></i>
                Adresse email
            </label>
            <div class="input-wrapper">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       placeholder="votre@email.com" class="form-input @error('email') error @enderror">
                <i class="fas fa-at"></i>
            </div>
            @error('email')
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>{{ $message }}
            </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">
                <i class="fas fa-lock"></i>
                Mot de passe
            </label>
            <div class="input-wrapper">
                <input id="password" type="password" name="password" required
                       placeholder="Créez un mot de passe sécurisé" class="form-input @error('password') error @enderror">
                <i class="fas fa-key"></i>
                <button type="button" class="password-toggle">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <!-- Indicateur de force du mot de passe -->
            <div class="password-strength">
                <div class="strength-bar">
                    <div class="strength-fill" id="strengthFill"></div>
                </div>
                <div class="strength-text">
                    <span>Force du mot de passe:</span>
                    <span class="strength-level" id="strengthLevel">-</span>
                </div>
            </div>

            @error('password')
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>{{ $message }}
            </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">
                <i class="fas fa-lock"></i>
                Confirmer le mot de passe
            </label>
            <div class="input-wrapper">
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       placeholder="Confirmez votre mot de passe" class="form-input">
                <i class="fas fa-check-circle"></i>
                <button type="button" class="password-toggle">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="password-match" id="passwordMatch"></div>
        </div>

        <!-- Terms and Conditions -->
        <div class="form-group">
            <label class="terms-label">
                <input type="checkbox" name="terms" required style="accent-color: var(--primary-color); margin-top: 2px;">
                <span>
                J'accepte les
                <a href="#">conditions d'utilisation</a>
                et la
                <a href="#">politique de confidentialité</a>
            </span>
            </label>
            @error('terms')
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>{{ $message }}
            </div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="submit-btn">
            <i class="fas fa-user-plus"></i>
            Créer mon compte
        </button>

        <!-- Login Link -->
        <div class="auth-footer">
            <p>Déjà membre ? <a href="{{ route('login') }}">Se connecter</a></p>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configurer la force du mot de passe
            const passwordInput = document.getElementById('password');
            const strengthFill = document.getElementById('strengthFill');
            const strengthLevel = document.getElementById('strengthLevel');
            const confirmInput = document.getElementById('password_confirmation');
            const matchElement = document.getElementById('passwordMatch');

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
                        strengthFill.classList.add('weak');
                        strengthLevel.textContent = 'Faible';
                        strengthLevel.style.color = 'var(--error-color)';
                    } else if (strength <= 50) {
                        strengthFill.classList.add('medium');
                        strengthLevel.textContent = 'Moyen';
                        strengthLevel.style.color = 'var(--warning-color)';
                    } else if (strength <= 75) {
                        strengthFill.classList.add('strong');
                        strengthLevel.textContent = 'Fort';
                        strengthLevel.style.color = 'var(--success-color)';
                    } else {
                        strengthFill.classList.add('very-strong');
                        strengthLevel.textContent = 'Très fort';
                        strengthLevel.style.color = '#2ecc71';
                    }
                });
            }

            // Vérification de correspondance des mots de passe
            if (confirmInput && matchElement) {
                confirmInput.addEventListener('input', function() {
                    if (this.value === '') {
                        matchElement.textContent = '';
                        matchElement.className = 'password-match';
                    } else if (this.value === passwordInput.value) {
                        matchElement.textContent = '✓ Les mots de passe correspondent';
                        matchElement.className = 'password-match valid';
                    } else {
                        matchElement.textContent = '✗ Les mots de passe ne correspondent pas';
                        matchElement.className = 'password-match invalid';
                    }
                });
            }

            // Toggle password visibility
            document.querySelectorAll('.password-toggle').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const input = this.parentNode.querySelector('input');
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
                });
            });

            // Validation du formulaire
            const form = document.querySelector('.auth-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const termsCheckbox = this.querySelector('input[name="terms"]');
                    if (!termsCheckbox.checked) {
                        e.preventDefault();
                        alert('Veuillez accepter les conditions d\'utilisation');
                        return;
                    }

                    const submitBtn = this.querySelector('.submit-btn');
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création du compte...';
                    submitBtn.disabled = true;
                });
            }
        });
    </script>
@endsection
