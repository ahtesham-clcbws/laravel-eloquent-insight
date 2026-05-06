<!-- Sidebar --> <?php

                    use Illuminate\Support\Facades\Auth;

                    $corporate = Auth::guard('corporate')->user();

                    ?>
<style>
    a {
        pointer-events: cursor;
    }
</style>
<nav class=" sidebar navbar-inverse fixed-top elevation-4" id="sidebar-wrapper" role="navigation" style="overflow-y: hidden;  font-style: italic !important;">
    <div class="sidebar-header">
        <div class="sidebar-brand" style="height: auto !important;">
            <div>
                <a href="{{('studentDashboard')}}">
                    <img src="/website/assets/images/brand/logo.png" style="width: 100%;" />
                </a>
                <h5 class="mb-0 py-1 fw-bold text-white">Institute Panel</h5>
            </div>
        </div>
        <div class="sidebar-brand d-none">
            <div class="info d-flex">
                <img src="{{asset('website/assets/images/fav-icon.png')}}"
                    style="width:40px; height: 40px; margin: 0 10px 0 5px !important">
                <span class="text-left" style="font-size: 14px;">
                    <span class="font-weight-bold">Institute panel</span><br />
                    <span class="text-white font-weight-bold">{{$corporate->institute_name}}</span>
                </span>
            </div>
        </div>

        <div class="logo_area mb-2">
            <a href="{{('corporateDashboard')}}" class="brand-link">
                @if($corporate->attachment)
                <img src="{{  asset('/storage/'.$corporate->attachment) }}" alt="Prifle Dp" class="brand-image img-circle elevation-3" style="opacity: .8">
                @else
                <img src="{{asset('corporate/images/th_5.png')}}" class="brand-image img-circle elevation-3" style="opacity: .8">
                @endif
            </a>
            <div class="brand_link_name mb-2">
                <a href="{{('corporateDashboard')}}" class="brand-text font-weight-light director_name">{{$corporate->name}}</a>
                <br>
            </div>
        </div>
    </div>
    <ul class="nav sidebar-nav" style="display: block;">
        <li>
            <div class="dropdown show">
                <a class="btn btn-secondary dropdown-toggle" href="{{route('corporateDashboard')}}">

                    <p>Dashboard</p>
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </li>
        <li>
            <div class="dropdown show">
                <a class="btn btn-secondary dropdown-toggle" href="{{route('corporateStudent')}}">

                    <p>Student List</p>
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </li>
        <li>
            <div class="dropdown show">
                <a class="btn btn-secondary dropdown-toggle" href="{{route('corporate.couponlist')}}">

                    <p>Coupon List</p>
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </li>
        <li>
            <div class="dropdown show">
                <a class="btn btn-secondary dropdown-toggle" href="{{route('corporate.couponRequest')}}">

                    <p>Request Coupons</p>
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </li>
        <li>
            <div class="dropdown show">
                <a class="btn btn-secondary dropdown-toggle" href="{{route('corporate.sayAboutUs')}}">

                    <p>Say About Us</p>
                    <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- /#sidebar-wrapper -->