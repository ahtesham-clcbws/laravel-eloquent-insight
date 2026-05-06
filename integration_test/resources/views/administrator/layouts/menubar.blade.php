<?php

use App\Models\Corporate;
use App\Models\CorporateCouponRequest;
use Illuminate\Support\Facades\Auth;

$user = Auth::user();

$newInstituteInquiry = Corporate::where('is_approved', 0)->whereNull('signup_at')->select('id')->count();
$newInstituteSignup = Corporate::where('is_approved', 1)->where('signup_approved', 0)->count();

$newCouponRequests = CorporateCouponRequest::where('status', 'pending')->count();

?>

<nav class="main-header navbar navbar-expand-lg navbar-light">
    <button type="button" class="hamburger animated fadeInLeft is-closed" data-toggle="offcanvas">
        <i class="fa fa-bars"></i>
    </button>
    <div class="header-left-box">
        <a class="required_area" href="{{route('institute.couponRequests')}}">
            <img src="{{asset('admin/icons/DiscountVoucher.png')}}" alt="clock" class="watch_ic">
            <span class="required_text">
                New Coupon Requests
            </span>
            <span class="required_num">{{$newCouponRequests}}</span>
        </a>
        <a class="required_area" href="{{route('institute.list.new')}}">
            <img src="{{asset('admin/icons/SignUp.png')}}" alt="clock" class="watch_ic">
            <span class="required_text">
                New Institute Inquiry
            </span>
            <span class="required_num">{{$newInstituteInquiry}}</span>
        </a>
        <a class="required_area" href="{{route('institute.list.signup')}}">
            <img src="{{asset('admin/icons/ApprovedInstitute.png')}}" alt="clock" class="watch_ic">
            <span class="required_text">
                New Institute Signup
            </span>
            <span class="required_num">{{$newInstituteSignup}}</span>
        </a>
    </div>
    <ul class="navbar-nav dashboard2" id="menu1-top">
        <li class="nav-item">
            <button class="panel-heading last_p">
                @if ($user->photograph != null)
                    <img src="{{ asset('upload/' . $user->photograph) }}">
                @else
                    <img src="{{ asset('upload/admin.png') }}">
                @endif
            </button>
            <div class="dropdown-content panel-collapse profile-noti">
                <div class="profile-box">
                    @if ($user->photograph != null)
                        <img src="{{ asset('upload/' . $user->photograph) }}">
                    @else
                        <img src="{{ asset('upload/admin.png') }}">
                    @endif
                    {{-- <img src="{{ asset('upload/'.$user->photograph) }}"> --}}
                    <h6> {{$user->name}}</h6>
                    <p>{{strtoupper($user->roles)}}</p>
                </div>
                <ul>
                    <li><a href="{{route('admin.profile')}}"><i class="fa fa-user" aria-hidden="true"></i> <span>
                                Profile</span></a></li>
                    <li><a href="{{route('admin.changePassword')}}"><i class="fa fa-pencil-square-o"
                                aria-hidden="true"></i><span> Change Password</span></a></li>

                    <li class="last_rad"><a href="{{route('admin.logout')}}"><i class="fa fa-sign-out"
                                aria-hidden="true"></i><span> Sign Out</span></a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>