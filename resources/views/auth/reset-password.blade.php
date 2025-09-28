<!-- resources/views/auth/reset-password.blade.php -->
@extends('layouts.auth-layout')

@section('title', 'Réinitialisation du mot de passe - Social Book Network')

@section('left-title', 'Presque terminé !')
@section('left-subtitle', 'Créez votre nouveau mot de passe sécurisé pour retrouver l\'accès à votre compte.')

@section('page-title', 'Nouveau mot de passe')
@section('page-subtitle', 'Créez un mot de passe sécurisé')

@section('styles')
    <style>
        .info-message {
            background: #e3f2fd;
            border-left: 4px solid var(--info-color);
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 4px;
        }

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

        .form-input:read-only {
            background-color: #f8f9fa !important;
            color: var(--text-light);
        }

        .requirement {
            font-size: 11px;
            color: var(--text-light);
            margin-bottom: 2px;
            transition: color 0.3s ease;
        }

        .requirement.met {
            color: var(--success-color) !important;
        }

        .requirement i {
            font-size: 4px;
            margin-right: 5px;
            vertical-align: middle;
            transition: all 0.3s ease;
        }

        .requirement.met i {
            font-size: 10px;
            color: var(--success-color);
        }
    </style>
@endsection

@section('process-steps')
    <div class="step completed">
        <div class="step-icon">
            <i class="fas fa-check"></i>
        </div>
        <span>Email</span>
    </div>
    <div class="step completed">
        <div class="step-icon">
            <i class="fas fa-check"></i>
        </div>
        <span>Lien</span>
    </div>
    <div class="step active">
        <div class="step-icon">
            <i class="fas fa-lock"></i>
        </div>
        <span>Nouveau mot de passe</span>
    </div>
@endsection

@section('content')
    <!-- Message d'information -->
    <div class="info-message">
        <div style="display: flex; align-items: flex-start; gap: 10px;">
            <i class="fas fa-shield-alt"></i>
            <div>
                <p style="margin: 0; color: #1565c0; font-size: 14px; line-height: 1.4;">
                    Créez un nouveau mot de passe sécurisé pour votre compte. Assurez-vous qu'il est différent de vos anciens mots de passe.
                </p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="auth-form" id="resetPasswordForm">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">
                <i class="fas fa-envelope"></i>
                Adresse email
            </label>
            <div class="input-wrapper">
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                       required readonly autocomplete="email" class="form-input @error('email') error @enderror">
                <i class="fas fa-at"></i>
                <i class="fas fa-check" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--success-color);"></i>
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
                Nouveau mot de passe
            </label>
            <div class="input-wrapper">
                <input id="password" type="password" name="password" required
                       autocomplete="new-password" placeholder="Créez un mot de passe sécurisé"
                       class="form-input @error('password') error @enderror">
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

            <!-- Exigences du mot de passe -->
            <div class="password-requirements">
                <div class="requirement" data-requirement="length">
                    <i class="fas fa-circle"></i>
                    Minimum 8 caractères
                </div>
                <div class="requirement" data-requirement="uppercase">
                    <i class="fas fa-circle"></i>
                    Au moins une majuscule
                </div>
                <div class="requirement" data-requirement="number">
                    <i class="fas fa-circle"></i>
                    Au moins un chiffre
                </div>
                <div class="requirement" data-requirement="special">
                    <i class="fas fa-circle"></i>
                    Au moins un caractère spécial
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
                       autocomplete="new-password" placeholder="Confirmez votre nouveau mot de passe"
                       class="form-input">
                <i class="fas fa-check-circle"></i>
                <button type="button" class="password-toggle">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="password-match" id="passwordMatch"></div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="submit-btn">
            <i class="fas fa-redo-alt"></i>
            Réinitialiser le mot de passe
        </button>
    </form>

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

            setupPasswordToggle('.password-toggle', 'password');
            setupPasswordToggle('.password-toggle-confirm', 'password_confirmation');

            // Password strength indicator
            const passwordInput = document.getElementById('password');
            const strengthFill = document.getElementById('strengthFill');
            const strengthLevel = document.getElementById('strengthLevel');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const passwordMatch = document.getElementById('passwordMatch');
            const requirements = document.querySelectorAll('.requirement');

            function updateRequirement(requirement, met) {
                const element = document.querySelector(`[data-requirement="${requirement}"]`);
                if (element) {
                    element.classList.toggle('met', met);
                    element.classList.toggle('unmet', !met);

                    const icon = element.querySelector('i');
                    if (icon) {
                        icon.className = met ? 'fas fa-check' : 'fas fa-circle';
                        icon.style.fontSize = met ? '10px' : '4px';
                        icon.style.color = met ? 'var(--success-color)' : 'var(--text-light)';
                    }
                }
            }

            if (passwordInput && strengthFill && strengthLevel) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;

                    // Vérifier les exigences
                    const hasLength = password.length >= 8;
                    const hasUppercase = /[A-Z]/.test(password);
                    const hasNumber = /[0-9]/.test(password);
                    const hasSpecial = /[^A-Za-z0-9]/.test(password);

                    // Mettre à jour les exigences visuellement
                    updateRequirement('length', hasLength);
                    updateRequirement('uppercase', hasUppercase);
                    updateRequirement('number', hasNumber);
                    updateRequirement('special', hasSpecial);

                    // Calculer la force
                    if (hasLength) strength += 25;
                    if (hasUppercase) strength += 25;
                    if (hasNumber) strength += 25;
                    if (hasSpecial) strength += 25;

                    // Mettre à jour la barre et le texte
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

            // Password confirmation check
            if (confirmPasswordInput && passwordMatch) {
                confirmPasswordInput.addEventListener('input', function() {
                    const password = passwordInput.value;
                    const confirmPassword = this.value;

                    if (confirmPassword === '') {
                        passwordMatch.textContent = '';
                        passwordMatch.className = 'password-match';
                    } else if (password === confirmPassword) {
                        passwordMatch.textContent = '✓ Les mots de passe correspondent';
                        passwordMatch.className = 'password-match valid';
                    } else {
                        passwordMatch.textContent = '✗ Les mots de passe ne correspondent pas';
                        passwordMatch.className = 'password-match invalid';
                    }
                });
            }

            // Form validation before submission
            const form = document.getElementById('resetPasswordForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const password = passwordInput.value;
                    const confirmPassword = confirmPasswordInput.value;
                    const submitBtn = this.querySelector('.submit-btn');

                    // Vérifier la force du mot de passe
                    if (password.length < 8) {
                        e.preventDefault();
                        alert('Le mot de passe doit contenir au moins 8 caractères');
                        return;
                    }

                    // Vérifier la correspondance
                    if (password !== confirmPassword) {
                        e.preventDefault();
                        alert('Les mots de passe ne correspondent pas');
                        return;
                    }

                    // Désactiver le bouton et afficher le loading
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Réinitialisation...';
                    submitBtn.disabled = true;
                });
            }

            // Auto-focus sur le champ mot de passe
            if (passwordInput) {
                passwordInput.focus();
            }

            // Auto-hide status messages
            const statusMessage = document.querySelector('.auth-status');
            if (statusMessage) {
                setTimeout(() => {
                    statusMessage.style.opacity = '0';
                    statusMessage.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        if (statusMessage.parentNode) {
                            statusMessage.parentNode.removeChild(statusMessage);
                        }
                    }, 500);
                }, 5000);
            }
        });
    </script>
@endsection
