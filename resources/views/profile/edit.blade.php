@extends('layouts.user-layout')

@section('title', 'Mon Profil - Social Book Network')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
    <div class="profile-container">
        <div class="container">
            <div class="profile-layout">
                <!-- Sidebar -->
                <aside class="profile-sidebar">
                    <div class="sidebar-header">
                        <div class="user-avatar-large">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3>{{ Auth::user()->name }}</h3>
                        <p>Membre depuis {{ Auth::user()->created_at->format('m/Y') }}</p>
                    </div>

                    <nav class="sidebar-nav">
                        <ul>
                            <li class="nav-item active">
                                <a href="#profile-info">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Informations du profil</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#password">
                                    <i class="fas fa-lock"></i>
                                    <span>Mot de passe</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#delete-account">
                                    <i class="fas fa-trash-alt"></i>
                                    <span>Supprimer le compte</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </aside>

                <!-- Main Content -->
                <main class="profile-content">
                    <div class="content-header">
                        <h1>Mon Profil</h1>
                        <div class="breadcrumb">
                            <a href="#">Accueil</a>
                            <i class="fas fa-chevron-right"></i>
                            <span>Profil</span>
                        </div>
                    </div>

                    <div class="content-body">
                        <!-- Section Informations du profil -->
                        <div class="profile-section" id="profile-info">
                            <div class="section-header">
                                <h2>
                                    <i class="fas fa-user-circle"></i>
                                    Informations du profil
                                </h2>
                                <p>Mettez à jour les informations de votre profil et votre adresse email.</p>
                            </div>

                            <div class="profile-card">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>

                        <!-- Section Mot de passe -->
                        <div class="profile-section" id="password">
                            <div class="section-header">
                                <h2>
                                    <i class="fas fa-lock"></i>
                                    Mise à jour du mot de passe
                                </h2>
                                <p>Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester sécurisé.</p>
                            </div>

                            <div class="profile-card">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>

                        <!-- Section Suppression du compte -->
                        <div class="profile-section" id="delete-account">
                            <div class="section-header">
                                <h2>
                                    <i class="fas fa-trash-alt"></i>
                                    Suppression du compte
                                </h2>
                                <p>Supprimez définitivement votre compte.</p>
                            </div>

                            <div class="profile-card">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/profile.js') }}"></script>
@endsection
