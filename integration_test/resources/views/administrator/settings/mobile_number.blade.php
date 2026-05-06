@extends('administrator.layouts.master')
@section('content')


<?php

use Illuminate\Support\Facades\Auth;

$admin = Auth::user();
?>
@if($admin->roles != 'admin')
{{ abort(403,'Permission Denied');}}
@endif
@section('content')
<div class="row py-2 pl-3 pr-3">
    <div class="container ">
        <div class="row">
            <div class="col-lg-6 col-md-6 col">
                <div class="panel panel-default m-t-15">
                    <div class="card-header">
                        <h5>Add Mobile Number:</h5>
                    </div>
                    <div class="panel-body">
                        <div class="card alert">
                            <div class="card-body">
                                <form action="{{ route('admin.mobile_number') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col">
                                            <div class="form-group">
                                                <p for="mobile">Mobile</p>
                                                <input class="form-control" placeholder="Enter Mobile" type="text" id="mobile" name="mobile" value="" required>
                                            </div>
                                        </div>
                                        <div class="col-md-5 text-center">
                                        <p for="mobile">&nbsp;</p>
                                        <input type="submit" class="btn btn-primary btn-flat m-b-10 m-l-5 w-50 " value="Submit">
                                    </div>
                                    </div>
                                   
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6 col-md-6 col">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr.No.</th>
                                    <th>Mobile</th>
                                    <th>Is Required OTP</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mobileNumbers  as $mobile)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $mobile->mobile }}</td>
                                    <td>
                                        <div class="form-control2">
                                            <label class="switch" for="featured-{{ $mobile->id }}">
                                                <input type="checkbox" id="featured-{{ $mobile->id }}" data-id="{{ $mobile->id }}" class="toggle-status" {{ $mobile->isOtpRequired ? 'checked' : '' }}>
                                                <div class="slider round">
                                                    <span class="off">Deactive</span>
                                                    <span class="on">Active</span>
                                                </div>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="color-primary" style="text-align: center">
                                        <a href="{{route('status_mobile_number.delete',[$mobile->id])}}"><span class="fa fa-edit"></span> </a> | <a href="{{route('status_mobile_number.delete',[$mobile->id])}}"><span class="fa fa-trash"></span></a>
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
<script>
    $(document).ready(function() {
        $('.toggle-status').on('change', function() {
            var courseId = $(this).data('id');
            var isStatus = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('mobile.number.statusToggle') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: courseId,
                    status: isStatus
                },
                success: function(response) {
                    if(response.status){
                        success(response.message);
                    }else{
                        error(response.message);
                    }
                },
                error: function(xhr) {
                    error(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection