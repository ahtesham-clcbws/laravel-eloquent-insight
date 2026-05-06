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
    <div class="col-lg-12">
        <h4>Sub Category List</h4>
        <hr>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subcategories as $subcategoriess)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{ $subcategoriess->subcategory_name }}</td>
                        <td style="text-align:center">
                            <span class="badge badge-{{ $subcategoriess->status ? 'primary' : 'dark' }}">
                                {{ $subcategoriess->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                @endforeach


                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
