{{-- resources/views/home.blade.php --}}
@extends('layouts.user-layout')

@section('title', 'Accueil - Social Book Network')

@section('content')

@include('partials.hero-user')
@include('partials.categorie-user')
@include('partials.book-user')
@include('partials.features-user')
@include('partials.community-user')
@include('partials.testimonials-user')
@include('partials.newsletter-user')
@endsection


