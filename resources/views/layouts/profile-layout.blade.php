@extends('layouts.user-layout')

@section('title', 'Mon Profil - Social Book Network')

@section('styles')
    @vite(['resources/css/profile.css'])
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
                        <h3>{{ auth()->user()->name ?? 'Utilisateur' }}</h3>
                        <p>Membre depuis {{ auth()->user()->created_at->format('m/Y') ?? '2024' }}</p>
                    </div>

                    <nav class="sidebar-nav">
                        <ul>
                            <li class="nav-item @yield('active-profile')">
                                <a href="#">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Profil</span>
                                </a>
                            </li>
                            <li class="nav-item @yield('active-books')">
                                <a href="#">
                                    <i class="fas fa-book"></i>
                                    <span>Mes Livres</span>
                                </a>
                            </li>
                            <li class="nav-item @yield('active-reviews')">
                                <a href="#">
                                    <i class="fas fa-star"></i>
                                    <span>Mes Avis</span>
                                </a>
                            </li>
                            <li class="nav-item @yield('active-favorites')">
                                <a href="#">
                                    <i class="fas fa-heart"></i>
                                    <span>Favoris</span>
                                </a>
                            </li>
                            <li class="nav-item @yield('active-settings')">
                                <a href="#">
                                    <i class="fas fa-cog"></i>
                                    <span>Paramètres</span>
                                </a>
                            </li>
                            <li class="nav-item @yield('active-clubs')">
                                <a href="#">
                                    <i class="fas fa-users"></i>
                                    <span>Clubs de Lecture</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Déconnexion</span>
                                </a>
                                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </nav>
                </aside>

                <!-- Main Content -->
                <main class="profile-content">
                    <div class="content-header">
                        <h1>@yield('page-title', 'Mon Profil')</h1>
                        <div class="breadcrumb">
                            <a href="#">Accueil</a>
                            <i class="fas fa-chevron-right"></i>
                            <span>@yield('page-title', 'Profil')</span>
                        </div>
                    </div>

                    <div class="content-body">
                        @yield('profile-content')
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/profile.js'])
@endsection
