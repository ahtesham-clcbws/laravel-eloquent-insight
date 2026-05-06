@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Sub Category')

@section('breadcrumb')
<ol class="breadcrumb text-right">
    <li><a href="{{ route('course.category') }}">Classes</a></li>
    <li><a href="{{ route('course.subcategory') }}"> Sub Category</a></li>
</ol>
@endsection

@section('content')
<div class="row">
   <div class="col-md-4">
        <div class="form-group"></div>
   </div>

   <div class="col-md-8">

   </div>
    </div>
</div>
@endsection
