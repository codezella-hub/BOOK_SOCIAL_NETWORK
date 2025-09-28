<!-- resources/views/auth/login.blade.php -->
@extends('layouts.auth-layout')

@section('title', 'Connexion - Social Book Network')

@section('left-title', 'Content de vous revoir !')
@section('left-subtitle', 'Reconnectez-vous à votre compte pour accéder à votre bibliothèque personnelle et à la communauté.')

@section('page-title', 'Connexion')
@section('page-subtitle', 'Accédez à votre compte')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">
                <i class="fas fa-envelope"></i>
                Adresse email
            </label>
            <div class="input-wrapper">
                <input id="email" type="email" name="email"  required autofocus
                       placeholder="votre@email.com" class="form-input @error('email') error @enderror">
                <i class="fas fa-user"></i>
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
                       placeholder="Votre mot de passe" class="form-input @error('password') error @enderror">
                <i class="fas fa-key"></i>
                <button type="button" class="password-toggle">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>{{ $message }}
            </div>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="form-group" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px;">
                <input type="checkbox" name="remember" style="accent-color: var(--primary-color);">
                Se souvenir de moi
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size: 14px; color: var(--primary-color); text-decoration: none;">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="submit-btn">
            <i class="fas fa-sign-in-alt"></i>
            Se connecter
        </button>

        <!-- Register Link -->
        <div class="auth-footer">
            <p>Nouveau sur SocialBook ? <a href="{{ route('register') }}">Créer un compte</a></p>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Activer le toggle du mot de passe
            const toggle = document.querySelector('.password-toggle');
            const input = document.getElementById('password');

            if (toggle && input) {
                toggle.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
                });
            }
        });
    </script>
@endsection
