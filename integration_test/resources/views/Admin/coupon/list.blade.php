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
                <div class="panel-heading">Generate CouponCode</div>
                <div class="panel-body">
                    <div class="card alert">
                        <div class="card-body">
                            <form action="{{ route('coupon.saveCoupon') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <p class="text-muted m-b-15 f-s-12">Prefix</p>
                                    <input type="text" name="prefix" class="form-control input-focus" placeholder="Add Prefix">
                                </div>

                                <div class="form-group">
                                    <p class="text-muted m-b-15 f-s-12">Discount Type</p>
                                    <select name="discount_type" class="form-control">
                                        <option value="">Select Value Type</option>
                                        <option value="amount">Rupee</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <p class="text-muted m-b-15 f-s-12">Discount Value</p>
                                    <input type="number" name="discount_value" class="form-control input-focus"
                                        placeholder="Only Number i.e 5">
                                </div>

                                <div class="form-group">
                                    <p class="text-muted m-b-15 f-s-12">Number of Coupons</p>
                                    <input type="number" name="number_of_coupons" class="form-control input-focus"
                                        placeholder="Only Number i.e 5">
                                </div>

                                <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="boxShadow">
                <form action="{{ route('coupon.filter') }}" method="GET">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="prefix">Prefix:</label>
                                <input type="text" class="form-control" id="prefix" name="prefix"
                                    value="{{ request('prefix') }}">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="status">Status:</label>
                                <select id="status" class="form-control" name="status">
                                    <option value="">All</option>
                                    <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <button type="submit">Filter</button>
                </form>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Coupon Code</th>
                        <th>Prefix</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coupons as $coupon)
                        <tr>
                            <td style="font-size: 13px">{{ $coupon->couponcode }}</td>
                            <td style="font-size: 13px">{{ $coupon->prefix }}</td>
                            <td style="font-size: 13px">{{ $coupon->status ? 'Active' : 'Inactive' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $coupons->links() }}

        </div>
    </div>





@endsection
