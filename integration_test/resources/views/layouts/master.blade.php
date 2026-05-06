<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Admin : Panel</title>

    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <link rel="shortcut icon" href="http://placehold.it/64.png/000/fff">

    <!-- Styles -->
    <link href="{{ asset('assets/css/lib/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/lib/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/lib/menubar/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/lib/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/lib/unix.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

</head>

<body>

    <div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
        <div class="nano">
            <div class="nano-content">
                <ul>
                    <li class="label">Main</li>

                    <li class="active"><a class="sidebar-sub-toggle"><i class="ti-home"></i> Home <span
                                class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="{{ route('home.company') }}">Company</a></li>
                            <li><a href="{{ route('home.slider') }}">Slider</a></li>
                            <li><a href="{{ route('home.scholarshipList') }}">Scholarship</a></li>
                            <li><a href="{{ route('home.benefit') }}">Benefit</a></li>
                            <li><a href="{{ route('home.contactInfo') }}">Contact Info</a></li>
                            <li><a href="{{ route('home.govtwebsite') }}">Govt. Website</a></li>
                            <li><a href="{{ route('home.testimonials') }}">Testimonials</a></li>
                            <li><a href="{{ route('admin.home.faq') }}">FAQ</a></li>
                        </ul>
                    </li>


                    <li><a class="sidebar-sub-toggle"><i class="ti-home"></i> Classes <span
                                class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="{{ route('course.subjects') }}">Subjects</a></li>
                            {{-- <li><a href="{{ route('course.subjectssubcategory') }}">Subjects Sub Category</a></li> --}}
                            <li><a href="{{ route('course.category') }}">Category</a></li>
                            <li><a href="{{ route('course.subcategory') }}">SubCategory</a></li>
                            <li><a href="{{ route('course.classList') }}">Class List</a></li>

                        </ul>
                    </li>

                    <li class="active"><a class="sidebar-sub-toggle"><i class="ti-home"></i> Courses <span
                                class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="{{ route('course.courses') }}">Courses</a></li>
                            <li><a href="{{ route('course.courseslist') }}">Courses List</a></li>
                        </ul>
                    </li>

                    {{-- <li class="label">School</li> --}}
                    <li><a class="sidebar-sub-toggle"><i class="ti-pencil-alt"></i>Students <span
                                class="badge badge-primary">28</span><span
                                class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="{{ route('students.studentslist') }}">Student</a></li>
                            <li><a href="{{ route('students.scholorshipApplyList') }}">Scholorship Student</a></li>
                        </ul>
                    </li>

                    <li><a class="sidebar-sub-toggle"><i class="ti-layout-grid4-alt"></i> Admit Card <span
                                class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="{{ route('result.newresult') }}">List</a></li>
                        </ul>
                    </li>
                    <li><a class="sidebar-sub-toggle"><i class="ti-layout-grid4-alt"></i> Result <span
                                class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="{{ route('result.newresult') }}">New Result</a></li>
                            <li><a href="{{ route('result.previousresult') }}">Previous Result</a></li>
                        </ul>
                    </li>


                    <li><a class="sidebar-sub-toggle"><i class="ti-layout-grid4-alt"></i> Notices <span
                                class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="{{ route('news.blognews') }}">News</a></li>
                            <li><a href="{{ route('news.notification') }}">Notification</a></li>
                        </ul>
                    </li>

                    <li><a class="sidebar-sub-toggle"><i class="ti-layout-grid4-alt"></i> Centers <span
                                class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="{{ route('centers.lists') }}">List</a></li>
                            <li><a href="{{ route('centers.manage') }}">Manage</a></li>
                        </ul>
                    </li>

                    <li><a class="sidebar-sub-toggle"><i class="ti-layout-grid4-alt"></i> Coupon Code <span
                                class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="{{ route('coupon.lists') }}">List</a></li>
                            <li><a href="{{ route('coupon.manage') }}">Genrat</a></li>
                        </ul>
                    </li>



                    <li><a class="sidebar-sub-toggle"><i class="ti-layout-grid4-alt"></i> Enquiry <span
                                class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="{{ route('enquiry.institute') }}">Institutional Enquiry</a></li>
                            <li><a href="{{ route('enquiry.students') }}">Students</a></li>
                            <li><a href="{{ route('enquiry.quickContact') }}">Quick Contact</a></li>
                            <li><a href="{{ route('enquiry.contactPage') }}">Contact Page</a></li>
                        </ul>
                    </li>

                    <li><a class="sidebar-sub-toggle"><i class="ti-layout-grid4-alt"></i> Admin <span
                                class="sidebar-collapse-icon ti-angle-down"></span></a>
                        <ul>
                            <li><a href="{{ route('students.allpayment') }}">Payment</a></li>
                         
                            <li><a href="{{ route('admin.logout') }}">Logout</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <!-- /# sidebar -->

    <div class="header">
        <div class="pull-left">
            <div class="logo"><a href="#"><!-- <img src="assets/images/logo.png" alt="" /> --><span>
                        Admin</span></a></div>
            <div class="hamburger sidebar-toggle">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </div>
        </div>

        <div class="pull-right p-r-15">
            <ul>
                <li class="header-icon dib"><a href="#search"><i class="ti-search"></i></a></li>
                <li class="header-icon dib"><i class="ti-bell"></i>
                    <div class="drop-down">
                        <div class="dropdown-content-heading">
                            <span class="text-left">Recent Notifications</span>
                        </div>
                        <div class="dropdown-content-body">
                            <ul>
                                <li>
                                    <a href="#">
                                        <img class="pull-left m-r-10 avatar-img" src="assets/images/avatar/3.jpg"
                                            alt="" />
                                        <div class="notification-content">
                                            <small class="notification-timestamp pull-right">02:34 PM</small>
                                            <div class="notification-heading">Mr. Vishal</div>
                                            <div class="notification-text">5 members joined today </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="text-center">
                                    <a href="#" class="more-link">See All</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="header-icon dib"><img class="avatar-img" src="assets/images/avatar/1.jpg"
                        alt="" /> <span class="user-avatar">Vishal <i
                            class="ti-angle-down f-s-10"></i></span>
                    <div class="drop-down dropdown-profile">
                        <div class="dropdown-content-body">
                            <ul>
                                <li><a href="#"><i class="ti-user"></i> <span>Profile</span></a></li>
                                <li><a href="#"><i class="ti-settings"></i> <span>Setting</span></a></li>
                                <li><a href="#"><i class="ti-lock"></i> <span>Lock Screen</span></a></li>
                                <li><a href="#"><i class="ti-power-off"></i> <span>Logout</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="content-wrap" style="background: #FFF">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>@yield('pagetype')</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                @yield('breadcrumb')
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <div id="main-content">
                    <div class="row">
                        <div class="col-lg-12">

                            @yield('content')
                            <div class="footer">
                                <p>This dashboard was generated on <span id="date-time"></span> <a href="#"
                                        class="page-refresh">Refresh Dashboard</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="search">
        <button type="button" class="close">×</button>
        <form>
            @csrf
            <input type="search" name="search" value="" placeholder="type keyword(s) here" />
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
    <!-- jquery vendor -->
    {{-- <script src="{{ asset('assets/js/lib/jquery.min.js') }}"></script> --}}
    <script src="{{ asset('https://code.jquery.com/jquery-3.7.1.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery.nanoscroller.min.js') }}"></script>
    <!-- nano scroller -->
    <script src="{{ asset('assets/js/lib/menubar/sidebar.js') }}"></script>
    <script src="{{ asset('assets/js/lib/preloader/pace.min.js') }}"></script>
    <!-- sidebar -->
    <script src="{{ asset('assets/js/lib/bootstrap.min.js') }}"></script>
    <!-- bootstrap -->

    <script src="{{ asset('assets/js/scripts.js') }}"></script>

    <!-- scripit init-->

    
</body>

</html>
