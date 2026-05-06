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
    <div class="col-lg-6">
        <div class="panel panel-default m-t-15">
            <div class="panel-heading">Add Sub Category/Section</div>
            <div class="panel-body">
                <div class="card alert">
                    <div class="card-body">
                        <form action="{{ route('course.savecategory') }}" method="POST">
                            @csrf
                        <div class="form-group">
                            <p class="text-muted m-b-15 f-s-12">Add New Sub Category/Section.</p>
                            <input type="text" name="category" class="form-control input-focus" placeholder="Add Category">
                        </div>
                        <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                    </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-lg-6">
        <h4>Category List</h4>
        <hr>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{ $category->name }}</td>
                        <td>
                            <span class="badge badge-{{ $category->status ? 'primary' : 'dark' }}">
                                {{ $category->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('course.subcategory') }}">Add Section</a> |
                            <span class="fa fa-eye"></span> |
                            <a href="{{ route('courses.deleteCategory', ['id' => $category->id]) }}">
                                <span class="fa fa-trash"></span>
                            </a>
                            </td>
                    </tr>
                @endforeach


                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        if (confirm("Are you sure you want to delete this item?")) {
            // User clicked "OK", proceed with deletion
            // Add your deletion logic here
            console.log("Item deleted");
        } else {
            // User clicked "Cancel", do nothing
            console.log("Deletion canceled");
        }
    }
</script>
@endsection
