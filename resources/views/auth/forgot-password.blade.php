<!-- resources/views/auth/forgot-password.blade.php -->
@extends('layouts.auth-layout')

@section('title', 'Mot de passe oublié - Social Book Network')

@section('left-title', 'Réinitialisation du mot de passe')
@section('left-subtitle', 'Nous vous aiderons à retrouver l\'accès à votre compte en quelques étapes simples.')

@section('page-title', 'Mot de passe oublié')
@section('page-subtitle', 'Recevez un lien de réinitialisation par email')

@section('styles')
    <style>
        .info-message {
            background: #e3f2fd;
            border-left: 4px solid var(--info-color);
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 4px;
        }

        .info-message i {
            color: var(--info-color);
            margin-right: 10px;
        }

        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .success-animation {
            animation: successPulse 0.6s ease;
        }
    </style>
@endsection

@section('process-steps')
    <div class="step active">
        <div class="step-icon">
            <i class="fas fa-envelope"></i>
        </div>
        <span>Email</span>
    </div>
    <div class="step">
        <div class="step-icon">
            <i class="fas fa-link"></i>
        </div>
        <span>Lien</span>
    </div>
    <div class="step">
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
            <i class="fas fa-info-circle"></i>
            <div>
                <p style="margin: 0; color: #1565c0; font-size: 14px; line-height: 1.4;">
                    {{ __('Vous avez oublié votre mot de passe ? Aucun problème. Indiquez-nous votre adresse email et nous vous enverrons un lien de réinitialisation qui vous permettra d\'en choisir un nouveau.') }}
                </p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">
                <i class="fas fa-envelope"></i>
                Adresse email
            </label>
            <div class="input-wrapper">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       autofocus autocomplete="email" placeholder="votre@email.com"
                       class="form-input @error('email') error @enderror">
                <i class="fas fa-at"></i>
            </div>
            @error('email')
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>{{ $message }}
            </div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="submit-btn">
            <i class="fas fa-paper-plane"></i>
            Envoyer le lien de réinitialisation
        </button>

        <!-- Back to Login -->
        <div class="auth-footer">
            <p>
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-left"></i>
                    Retour à la connexion
                </a>
            </p>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide status messages after 5 seconds
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

            // Add loading state to submit button
            const form = document.querySelector('.auth-form');
            if (form) {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('.submit-btn');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
                        submitBtn.disabled = true;

                        // Réactiver le bouton après 10 secondes au cas où
                        setTimeout(() => {
                            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Envoyer le lien de réinitialisation';
                            submitBtn.disabled = false;
                        }, 10000);
                    }
                });
            }

            // Success animation if status is present
            if (statusMessage && statusMessage.classList.contains('success')) {
                statusMessage.classList.add('success-animation');
            }

            // Auto-focus sur le champ email
            const emailInput = document.getElementById('email');
            if (emailInput) {
                emailInput.focus();
            }

            // Validation en temps réel de l'email
            if (emailInput) {
                emailInput.addEventListener('blur', function() {
                    const email = this.value;
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if (email && !emailRegex.test(email)) {
                        this.classList.add('error');
                    } else {
                        this.classList.remove('error');
                    }
                });
            }
        });
    </script>
@endsection
