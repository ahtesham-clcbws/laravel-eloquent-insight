<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Category')

@section('breadcrumb')
<ol class="breadcrumb text-right">
    <li><a href="{{ route('course.category') }}">Classes</a></li>
    <li><a href="{{ route('course.category') }}">Category</a></li>
</ol>
@endsection

@section('content')
    <h1>Welcome to our website!</h1>
    <!-- Your home page content goes here -->
@endsection
