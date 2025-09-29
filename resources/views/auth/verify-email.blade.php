<!-- resources/views/auth/verify-email.blade.php -->
@extends('layouts.auth-layout')

@section('title', 'Vérification de l\'email - Social Book Network')

@section('content')
    <section class="auth-section" style="padding: 100px 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center;">
        <div class="container">
            <div class="auth-container" style="max-width: 500px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); overflow: hidden;">

                <!-- En-tête -->
                <div class="auth-header" style="background: var(--primary-color); padding: 30px; text-align: center;">
                    <div class="logo" style="display: flex; align-items: center; justify-content: center; gap: 10px; color: white; margin-bottom: 15px;">
                        <i class="fas fa-book-open" style="font-size: 32px;"></i>
                        <span style="font-size: 24px; font-weight: 700;">SocialBook</span>
                    </div>
                    <h1 style="color: white; margin: 0; font-size: 24px; font-weight: 600;">Vérification requise</h1>
                    <p style="color: rgba(255,255,255,0.8); margin: 5px 0 0; font-size: 14px;">Activez votre compte pour commencer</p>
                </div>

                <div class="auth-body" style="padding: 30px;">
                    <!-- Illustration -->
                    <div class="verification-illustration" style="text-align: center; margin-bottom: 25px;">
                        <div style="width: 80px; height: 80px; background: #e3f2fd; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                            <i class="fas fa-envelope-open-text" style="font-size: 32px; color: #2196f3;"></i>
                        </div>
                        <h3 style="color: var(--primary-color); margin-bottom: 5px;">Vérifiez votre email</h3>
                    </div>

                    <!-- Message principal -->
                    <div class="info-message" style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; margin-bottom: 25px; border-radius: 4px;">
                        <div style="display: flex; align-items: flex-start; gap: 10px;">
                            <i class="fas fa-info-circle" style="color: #2196f3; font-size: 16px; margin-top: 2px;"></i>
                            <div>
                                <p style="margin: 0; color: #1565c0; font-size: 14px; line-height: 1.5;">
                                    {{ __('Merci pour votre inscription ! Avant de commencer, pourriez-vous vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer ? Si vous n\'avez pas reçu l\'email, nous vous en enverrons un autre avec plaisir.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Message de succès -->
                    @if (session('status') == 'verification-link-sent')
                        <div class="success-message" style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 25px; border-radius: 4px; animation: slideIn 0.3s ease;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-check-circle" style="color: #28a745; font-size: 16px;"></i>
                                <div>
                                    <p style="margin: 0; color: #155724; font-size: 14px; font-weight: 500;">
                                        {{ __('Un nouveau lien de vérification a été envoyé à l\'adresse email que vous avez fournie lors de l\'inscription.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="verification-actions" style="display: flex; flex-direction: column; gap: 20px;">
                        <!-- Renvoyer l'email de vérification -->
                        <form method="POST" action="{{ route('verification.send') }}" class="resend-form">
                            @csrf
                            <button type="submit" class="resend-btn" style="width: 100%; padding: 12px; background: var(--primary-color); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 10px;">
                                <i class="fas fa-paper-plane"></i>
                                {{ __('Renvoyer l\'email de vérification') }}
                            </button>
                        </form>

                        <!-- Déconnexion -->
                        <form method="POST" action="{{ route('logout') }}" class="logout-form" style="text-align: center;">
                            @csrf
                            <button type="submit" class="logout-btn" style="background: none; border: none; color: var(--text-light); text-decoration: underline; cursor: pointer; font-size: 14px; transition: color 0.3s ease; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="fas fa-sign-out-alt"></i>
                                {{ __('Se déconnecter') }}
                            </button>
                        </form>
                    </div>

                    <!-- Assistance -->
                    <div class="help-section" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--gray-light);">
                        <div class="help-item" style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 15px;">
                            <i class="fas fa-search" style="color: var(--primary-color); font-size: 14px; margin-top: 2px;"></i>
                            <div>
                                <h4 style="margin: 0 0 5px 0; font-size: 14px; color: var(--text-color);">Vérifiez vos spams</h4>
                                <p style="margin: 0; font-size: 12px; color: var(--text-light);">L'email peut avoir été classé dans vos courriers indésirables.</p>
                            </div>
                        </div>

                        <div class="help-item" style="display: flex; align-items: flex-start; gap: 10px;">
                            <i class="fas fa-clock" style="color: var(--primary-color); font-size: 14px; margin-top: 2px;"></i>
                            <div>
                                <h4 style="margin: 0 0 5px 0; font-size: 14px; color: var(--text-color);">Patientez quelques minutes</h4>
                                <p style="margin: 0; font-size: 12px; color: var(--text-light);">L'email peut prendre quelques minutes à arriver.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact support -->
                    <div class="support-contact" style="text-align: center; margin-top: 20px;">
                        <p style="font-size: 12px; color: var(--text-light); margin: 0;">
                            Probleme persistant ?
                            <a href="mailto:support@socialbook.net" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">
                                Contacter le support
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .resend-btn:hover {
            background: var(--secondary-color) !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .resend-btn:disabled {
            background: var(--gray-light) !important;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .logout-btn:hover {
            color: var(--primary-color) !important;
        }

        .success-message {
            background: #d4edda;
            border-left: 4px solid #28a745;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .verification-illustration {
            animation: pulse 2s infinite;
        }

        /* Animation pour le bouton de renvoi */
        @keyframes sending {
            0% { transform: translateY(0); }
            50% { transform: translateY(-2px); }
            100% { transform: translateY(0); }
        }

        .sending {
            animation: sending 0.6s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du bouton de renvoi d'email
            const resendBtn = document.querySelector('.resend-btn');
            const resendForm = document.querySelector('.resend-form');

            if (resendForm) {
                resendForm.addEventListener('submit', function(e) {
                    if (resendBtn) {
                        // Désactiver le bouton et afficher l'animation
                        resendBtn.disabled = true;
                        resendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
                        resendBtn.classList.add('sending');

                        // Réactiver après 30 secondes (cooldown)
                        setTimeout(() => {
                            resendBtn.disabled = false;
                            resendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Renvoyer l\'email de vérification';
                            resendBtn.classList.remove('sending');
                        }, 30000);
                    }
                });
            }

            // Compte à rebours pour le prochain renvoi
            let countdown = 30;
            const startCountdown = () => {
                const countdownInterval = setInterval(() => {
                    countdown--;
                    if (resendBtn) {
                        resendBtn.innerHTML = `<i class="fas fa-clock"></i> Renvoyer (${countdown}s)`;
                    }

                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                        if (resendBtn) {
                            resendBtn.disabled = false;
                            resendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Renvoyer l\'email de vérification';
                        }
                        countdown = 30;
                    }
                }, 1000);
            };

            // Démarrer le compte à rebours si un email vient d'être envoyé
            @if (session('status') == 'verification-link-sent')
            if (resendBtn) {
                resendBtn.disabled = true;
                startCountdown();
            }
            @endif

            // Auto-hide du message de succès après 5 secondes
            const successMessage = document.querySelector('.success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    successMessage.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        if (successMessage.parentNode) {
                            successMessage.parentNode.removeChild(successMessage);
                        }
                    }, 500);
                }, 5000);
            }

            // Vérification automatique de l'email (optionnel)
            const checkVerification = () => {
                fetch('{{ route('verification.check') }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.verified) {
                            window.location.href = '{{ route('dashboard') }}';
                        }
                    })
                    .catch(error => console.error('Error:', error));
            };

            // Vérifier toutes les 10 secondes (optionnel)
            // setInterval(checkVerification, 10000);
        });
    </script>
@endsection
