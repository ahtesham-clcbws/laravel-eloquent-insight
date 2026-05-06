<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Category')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Classes</a></li>
        <li><a href="{{ route('course.category') }}">Coupon</a></li>
    </ol>
@endsection
<style>
    .boxShadow {
        margin: 10px auto;
        background-color: #fff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
</style>
@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-default m-t-15">
                <div class="panel-heading">Add Center</div>
                <div class="panel-body">
                    <div class="card alert">
                        <div class="card-body">
                            <form action="{{ route('centers.addnew') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <p class="text-muted m-b-15 f-s-12">Add Center Name</p>
                                    <input type="text" name="center_name" class="form-control input-focus"
                                        placeholder="Add Center Name">
                                </div>

                                <div class="form-group">
                                    <p class="text-muted m-b-15 f-s-12">Add Address</p>
                                    <input type="text" name="center_address" class="form-control input-focus"
                                        placeholder="Add Center Address">
                                </div>

                                <div class="form-group">
                                    <p class="text-muted m-b-15 f-s-12">Lat</p>
                                    <input type="text" name="add_lat" class="form-control input-focus"
                                        placeholder="Add Lat">
                                </div>

                                <div class="form-group">
                                    <p class="text-muted m-b-15 f-s-12">Long</p>
                                    <input type="text" name="add_long" class="form-control input-focus"
                                        placeholder="Add Long">
                                </div>

                                <div class="form-group">
                                    <p class="text-muted m-b-15 f-s-12">Contact</p>
                                    <input type="text" name="add_contact" class="form-control input-focus"
                                        placeholder="Add Contact">
                                </div>

                                <div class="form-group">
                                    <p class="text-muted m-b-15 f-s-12">Dist</p>
                                    <select name="add_district" class="form-control">
                                        <option value="">Select District</option>
                                        <option value="Begusarai">Begusarai</option>
                                        <option value="Patna">Patna</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <p class="text-muted m-b-15 f-s-12">State</p>
                                    <select name="add_state" class="form-control">
                                        <option value="">Select State</option>
                                        <option value="Bihar">Bihar</option>
                                        <option value="UP">UP</option>
                                    </select>
                                </div>
                                <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-6">
            <table class="table boxshadow">
                <thead>
                    <tr>
                        <th>Coupon Code</th>
                        <th>Prefix</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($centers as $center)
                        <tr>
                            <td style="font-size: 13px">{{ $center->center_name }}</td>
                            <td style="font-size: 13px">{{ $center->add_district }} | {{ $center->add_state }}</td>
                            <td style="font-size: 13px">{{ $center->status ? 'Active' : 'Inactive' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- {{ $coupons->links() }} --}}

        </div>
    </div>





@endsection
