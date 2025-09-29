@extends('layouts.admin-layout')
@section('title','Edit Event')
@section('content')
@include('admin.events.partials._form', ['action' => route('admin.events.update', $event), 'method' => 'POST', 'event' => $event])
@endsection
