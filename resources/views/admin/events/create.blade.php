@extends('layouts.admin-layout')
@section('title','Create Event')
@section('content')
@include('admin.events.partials._form', ['action' => route('admin.events.store'), 'method' => 'POST'])
@endsection
