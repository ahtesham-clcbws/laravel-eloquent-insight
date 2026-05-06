@extends('administrator.layouts.master')
@section('content')


@section('content')
<div class="row py-2 pl-3 pr-3">
    <div class="container ">
        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default m-t-15">
                    <div class="card-header">
                        <h3> Add Our Benefit</h3>
                    </div>
                    <div class="panel-body">
                        <div class="card alert">
                            <div class="card-body">
                                <form action="{{ route('admin.home.benefit') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <p class="text-muted f-s-12">Benefit*</p>
                                        <input required class="form-control" name="benefit"></input>
                                    </div>
                                    <div class="col-md-12">
                                    <p class="text-muted f-s-12">Icon*</p>
                                        <div class="icon-group">
                                            <input type="radio" class="btn-check" name="icon" id="icon-op1" value="fa fa-suitcase" checked autocomplete="off">
                                            <label class="btn btn-icon" for="icon-op1"><i class="fa fa-suitcase"></i></label>

                                            <input type="radio" class="btn-check" name="icon" id="icon-op2" value="fa fa-handshake-o" autocomplete="off">
                                            <label class="btn btn-icon" for="icon-op2"><i class="fa fa-handshake-o"></i></label>

                                            <input type="radio" class="btn-check" name="icon" id="icon-op3" value="fa fa-bandcamp" autocomplete="off">
                                            <label class="btn btn-icon" for="icon-op3"><i class="fa fa-bandcamp"></i></label>

                                            <input type="radio" class="btn-check" name="icon" id="icon-op4" value="fa fa-area-chart" autocomplete="off">
                                            <label class="btn btn-icon" for="icon-op4"><i class="fa fa-area-chart"></i></label>

                                            <input type="radio" class="btn-check" name="icon" id="icon-op5" value="fa fa-area-chart" autocomplete="off">
                                            <label class="btn btn-icon" for="icon-op5"><i class="fa fa-area-chart"></i></label>

                                         
                                        </div>
                                    </div>
                                    <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="card-header">
                    <h3>Our Benefit List</h3>
                </div>
                <div class="card">
                    <div class="card-body" style="max-height: 384px;overflow-y: auto;">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Title</th>
                                        <th>Is Featured</th>
                                        <th style="text-align: center;">Icon</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ourBenefits as $pros)
                                    <tr>
                                        <th scope="row">{{$loop->iteration}}</th>
                                        <td>{{ $pros->benefits }}</td>
                                        <td>

                                            <div class="form-control2">
                                                <label class="switch" for="status-{{ $pros->id }}">
                                                    <input type="checkbox" id="status-{{ $pros->id }}" data-id="{{ $pros->id }}" class="toggle-featured" {{ $pros->is_featured ? 'checked' : '' }}>
                                                    <div class="slider round">
                                                        <span class="off">Inactive</span>
                                                        <span class="on">Active</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                        <label class="btn btn-icon" for="icon-op18"><span class="{{$pros->icon}} ?? handshake"></span></label>
                                        </td>

                                        <td style="text-align: center">
                                            <a href="{{ route('admin.home.deletebenefits', ['id' => $pros->id]) }}">
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

            </div>
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


<script>
    $('.toggle-featured').on('change', function() {
        var id = $(this).data('id');
        var isFeatured = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: "{{ route('toggle.featured') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                type: 'ourBenefit',
                is_featured: isFeatured
            },
            success: function(response) {
                if (response.status) {
                    success(response.message);
                } else {
                    error(response.message);
                }
            },
            error: function(xhr) {
                error(xhr.responseText);
            }
        });
    });
</script>
@endsection