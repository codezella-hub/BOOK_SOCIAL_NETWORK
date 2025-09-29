@extends('layouts.admin-layout')

@section('title', 'Error')
@section('content')
<div class="p-6 text-center">
    <h1 class="text-3xl font-bold text-red-600 mb-4">500 | Internal Server Error</h1>
    <p class="text-gray-700 mb-6">Sorry, something went wrong on our end. Please try again later.</p>
    <a href="{{ url()->previous() }}" class="btn">Go Back</a>
</div>
@endsection
