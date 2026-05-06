<style>
    #sidebar-wrapper {
        background: #ffffff !important;
        border-right: 1px solid #e3e6f0;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 120, 0.15);
        transition: width 0.3s ease;
    }

    .sidebar-brand {
        padding: 1.5rem 1rem;
        border-bottom: 1px solid #f8f9fc;
        background: #fff;
    }

    .side-nav-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        color: #333333 !important;
        text-decoration: none !important;
        font-weight: 600;
        font-size: 0.9rem;
        border-left: 3px solid transparent;
        transition: all 0.2s ease-in-out;
    }

    .side-nav-item:hover {
        background-color: #f8f9fc;
        color: #4e73df !important;
        border-left-color: #4e73df;
    }

    .side-nav-item.active {
        background-color: #f8f9fc;
        color: #4e73df !important;
        border-left-color: #4e73df;
    }

    .side-nav-item i.nav_icon_bi {
        font-size: 1.1rem;
        margin-right: 0.75rem;
        width: 20px;
        text-align: center;
    }

    .side-nav-item img.nav_icon {
        width: 18px;
        height: 18px;
        margin-right: 0.75rem;
        opacity: 0.8;
        filter: grayscale(100%);
        transition: all 0.2s;
    }

    .side-nav-item.active img.nav_icon, .side-nav-item:hover img.nav_icon {
        opacity: 1;
        filter: none;
    }

    .side-nav-item .chevron-icon {
        margin-left: auto;
        font-size: 0.7rem;
        transition: transform 0.2s;
        color: #858796;
    }

    .side-nav-item[aria-expanded="true"] .chevron-icon {
        transform: rotate(90deg);
        color: #4e73df;
    }

    .sub-menu {
        background-color: #fcfcfc;
        list-style: none;
        padding: 0.5rem 0;
        margin: 0;
        border-left: 1px solid #eaecf4;
        margin-left: 1.5rem;
    }

    .sub-menu-item {
        display: block;
        padding: 0.5rem 1rem 0.5rem 1.5rem;
        color: #5a5c69 !important;
        text-decoration: none !important;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .sub-menu-item:hover, .sub-menu-item.active {
        color: #4e73df !important;
        background-color: #f8f9fc;
    }

    .sidebar-nav-container {
        height: calc(100vh - 100px);
        overflow-y: auto;
    }

    .sidebar-nav-container {
        height: calc(100vh - 100px);
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #4e73df #f8f9fc;
    }

    /* Custom scrollbar for Chrome, Safari and Opera */
    .sidebar-nav-container::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-nav-container::-webkit-scrollbar-thumb {
        background-color: #4e73df;
        border-radius: 10px;
    }

    .sidebar-nav-container::-webkit-scrollbar-track {
        background-color: #f8f9fc;
    }
</style>
<!-- Sidebar -->
<nav class="sidebar fixed-top elevation-4" id="sidebar-wrapper" role="navigation">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center">
            <img src="{{asset('website/assets/images/fav-icon.png')}}" style="width:35px; height: 35px;" alt="Logo">
            <div class="ms-3">
                <div class="fw-bold text-dark" style="font-size: 0.9rem; line-height: 1.2;">Admin Panel</div>
                <div class="text-muted small">{{auth()->user()->name}}</div>
            </div>
        </div>
    </div>

    <div class="sidebar-nav-container">
        <div class="nav flex-column mt-2">
            <!-- Dashboard -->
            <a class="side-nav-item {{ Route::is('admin.home') ? 'active' : '' }}" href="{{route('admin.home')}}">
                <img src="{{asset('admin/icons/Dashboard.png')}}" class="nav_icon" alt="">
                <span>Dashboard</span>
            </a>

            <!-- Upload Image -->
            <a class="side-nav-item {{ Route::is('file.upload') ? 'active' : '' }}" href="{{route('file.upload')}}">
                <img src="{{asset('admin/icons/UploadImage.png')}}" class="nav_icon" alt="">
                <span>Upload Image</span>
            </a>

            <!-- Home Page Master -->
            @php
                $homePages = ['admin.home.slider', 'admin.home.courseList', 'admin.home.govtwebsite', 'admin.home.faq', 'news.blognews', 'news.notification', 'admin.home.eprospectus', 'admin.home.ourJourney', 'admin.home.ourContributor', 'admin.home.benefit'];
                $isHomeActive = Route::is($homePages);
            @endphp
            <a class="side-nav-item {{ $isHomeActive ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#homePageMaster" href="javascript:void(0)" role="button" aria-expanded="{{ $isHomeActive ? 'true' : 'false' }}" aria-controls="homePageMaster">
                <img src="{{asset('admin/icons/HomePageMaster.png')}}" class="nav_icon" alt="">
                <span>Home Page Master</span>
                <i class="bi bi-chevron-right chevron-icon"></i>
            </a>
            <div class="collapse {{ $isHomeActive ? 'show' : '' }}" id="homePageMaster">
                <div class="sub-menu">
                    <a class="sub-menu-item {{ Route::is('admin.home.slider') ? 'active' : '' }}" href="{{route('admin.home.slider')}}">Slider Banner</a>
                    <a class="sub-menu-item {{ Route::is('admin.home.courseList') ? 'active' : '' }}" href="{{route('admin.home.courseList')}}">Courses List</a>
                    <a class="sub-menu-item {{ Route::is('admin.home.govtwebsite') ? 'active' : '' }}" href="{{route('admin.home.govtwebsite')}}">Govt. Website</a>
                    <a class="sub-menu-item {{ Route::is('admin.home.faq') ? 'active' : '' }}" href="{{route('admin.home.faq')}}">FAQ List</a>
                    <a class="sub-menu-item {{ Route::is('news.blognews') ? 'active' : '' }}" href="{{ route('news.blognews') }}">News</a>
                    <a class="sub-menu-item {{ Route::is('news.notification') ? 'active' : '' }}" href="{{ route('news.notification') }}">Notifications</a>
                    <a class="sub-menu-item {{ Route::is('admin.home.eprospectus') ? 'active' : '' }}" href="{{ route('admin.home.eprospectus') }}">E-Prospectus</a>
                    <a class="sub-menu-item {{ Route::is('admin.home.ourJourney') ? 'active' : '' }}" href="{{ route('admin.home.ourJourney') }}">Our Journey</a>
                    <a class="sub-menu-item {{ Route::is('admin.home.ourContributor') ? 'active' : '' }}" href="{{ route('admin.home.ourContributor') }}">Our Contributors</a>
                    <a class="sub-menu-item {{ Route::is('admin.home.benefit') ? 'active' : '' }}" href="{{ route('admin.home.benefit') }}">Our Benefits</a>
                </div>
            </div>

            <!-- Single Pages -->
            <a class="side-nav-item {{ Route::is('admin.aboutus') ? 'active' : '' }}" href="{{route('admin.aboutus')}}">
                <img src="{{asset('admin/icons/AboutUs.png')}}" class="nav_icon" alt="">
                <span>About Us Page</span>
            </a>
            <a class="side-nav-item {{ Route::is('admin.scholarship') ? 'active' : '' }}" href="{{route('admin.scholarship')}}">
                <img src="{{asset('admin/icons/ScholarshipPage.png')}}" class="nav_icon" alt="">
                <span>Scholarship Page</span>
            </a>
            <a class="side-nav-item {{ Route::is('admin.testimonialList') ? 'active' : '' }}" href="{{route('admin.testimonialList')}}">
                <img src="{{asset('admin/icons/Testimony1.png')}}" class="nav_icon" alt="">
                <span>Testimonial List</span>
            </a>

            <!-- Discount Voucher -->
            @php $isVoucherActive = Route::is(['coupon.createCoupon', 'coupon.lists']); @endphp
            <a class="side-nav-item {{ $isVoucherActive ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#discountVoucher" href="javascript:void(0)" role="button" aria-expanded="{{ $isVoucherActive ? 'true' : 'false' }}" aria-controls="discountVoucher">
                <img src="{{asset('admin/icons/DiscountVoucher.png')}}" class="nav_icon" alt="">
                <span>Discount Voucher</span>
                <i class="bi bi-chevron-right chevron-icon"></i>
            </a>
            <div class="collapse {{ $isVoucherActive ? 'show' : '' }}" id="discountVoucher">
                <div class="sub-menu">
                    <a class="sub-menu-item {{ Route::is('coupon.createCoupon') ? 'active' : '' }}" href="{{route('coupon.createCoupon')}}">Create Coupon</a>
                    <a class="sub-menu-item {{ Route::is('coupon.lists') ? 'active' : '' }}" href="{{route('coupon.lists')}}">Coupon List</a>
                </div>
            </div>

            <!-- Institute Section -->
            <a class="side-nav-item {{ Route::is('institute.list.new') ? 'active' : '' }}" href="{{route('institute.list.new')}}">
                <img src="{{asset('admin/icons/InstituteEnquiry.png')}}" class="nav_icon" alt="">
                <span>New Institute Enquiry</span>
            </a>
            <a class="side-nav-item {{ Route::is('institute.list.signup') ? 'active' : '' }}" href="{{route('institute.list.signup')}}">
                <img src="{{asset('admin/icons/SignUp.png')}}" class="nav_icon" alt="">
                <span>SignUp Institute</span>
            </a>
            <a class="side-nav-item {{ Route::is('institute.list') ? 'active' : '' }}" href="{{route('institute.list')}}">
                <img src="{{asset('admin/icons/ApprovedInstitute.png')}}" class="nav_icon" alt="">
                <span>Approved Institute</span>
            </a>
            <a class="side-nav-item {{ Route::is('institute.couponRequests') ? 'active' : '' }}" href="{{route('institute.couponRequests')}}">
                <img src="{{asset('admin/icons/DiscountVoucher.png')}}" style="filter: invert(1); opacity: 0.5;" class="nav_icon" alt="">
                <span>New Coupon Requests</span>
            </a>

            <!-- Student Section -->
            @php
                $studentPages = ['admin.studentListRegistered', 'admin.studentList', 'admin.studentClaimList', 'admin.student-roll-list', 'admin.studentExamCenter', 'admin.student.result'];
                $isStudentActive = Route::is($studentPages);
            @endphp
            <a class="side-nav-item {{ $isStudentActive ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#studentMenu" href="javascript:void(0)" role="button" aria-expanded="{{ $isStudentActive ? 'true' : 'false' }}" aria-controls="studentMenu">
                <img src="{{asset('admin/icons/Students.png')}}" class="nav_icon" alt="">
                <span>Student</span>
                <i class="bi bi-chevron-right chevron-icon"></i>
            </a>
            <div class="collapse {{ $isStudentActive ? 'show' : '' }}" id="studentMenu">
                <div class="sub-menu">
                    <a class="sub-menu-item {{ Route::is('admin.studentListRegistered') ? 'active' : '' }}" href="{{route('admin.studentListRegistered')}}">New Student</a>
                    <a class="sub-menu-item {{ Route::is('admin.studentList') ? 'active' : '' }}" href="{{route('admin.studentList')}}">Student List</a>
                    <a class="sub-menu-item {{ Route::is('admin.student-roll-list') ? 'active' : '' }}" href="{{route('admin.student-roll-list')}}">Student Roll No</a>
                    <a class="sub-menu-item {{ Route::is('admin.studentExamCenter') ? 'active' : '' }}" href="{{route('admin.studentExamCenter')}}">Allot Exam Center</a>
                    <a class="sub-menu-item {{ Route::is('admin.student.result') ? 'active' : '' }}" href="{{route('admin.student.result')}}">Student Result</a>
                    <a class="sub-menu-item {{ Route::is('admin.studentClaimList') ? 'active' : '' }}" href="{{route('admin.studentClaimList')}}">Claim Forms</a>
                </div>
            </div>

            <!-- Settings -->
            @php
                $settingPages = ['payment-settings.store', 'admin.mobile_number', 'admin.state-settings', 'admin.important-links', 'admin.district-settings', 'admin.scholarship-forms', 'admin.policy-pages', 'settings.registration', 'settings.popup', 'settings.resetPortal'];
                $isSettingsActive = Route::is($settingPages);
            @endphp
            <a class="side-nav-item {{ $isSettingsActive ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#settingsMenu" href="javascript:void(0)" role="button" aria-expanded="{{ $isSettingsActive ? 'true' : 'false' }}" aria-controls="settingsMenu">
                <img src="{{asset('admin/icons/Setting.png')}}" class="nav_icon" alt="">
                <span>Settings</span>
                <i class="bi bi-chevron-right chevron-icon"></i>
            </a>
            <div class="collapse {{ $isSettingsActive ? 'show' : '' }}" id="settingsMenu">
                <div class="sub-menu">
                    <a class="sub-menu-item {{ Route::is('payment-settings.store') ? 'active' : '' }}" href="{{route('payment-settings.store')}}">Payment Settings</a>
                    <a class="sub-menu-item {{ Route::is('admin.mobile_number') ? 'active' : '' }}" href="{{route('admin.mobile_number')}}">Mobile Number</a>
                    <a class="sub-menu-item {{ Route::is('admin.state-settings') ? 'active' : '' }}" href="{{route('admin.state-settings')}}">States</a>
                    <a class="sub-menu-item {{ Route::is('admin.important-links') ? 'active' : '' }}" href="{{route('admin.important-links')}}">Important Links</a>
                    <a class="sub-menu-item {{ Route::is('admin.district-settings') ? 'active' : '' }}" href="{{route('admin.district-settings')}}">Districts</a>
                    <a class="sub-menu-item {{ Route::is('admin.scholarship-forms') ? 'active' : '' }}" href="{{route('admin.scholarship-forms')}}">Scholarship Forms</a>
                    <a class="sub-menu-item {{ Route::is('admin.policy-pages') ? 'active' : '' }}" href="{{route('admin.policy-pages')}}">Policy Pages</a>
                    <a class="sub-menu-item {{ Route::is('settings.registration') ? 'active' : '' }}" href="{{route('settings.registration')}}">Registration Settings</a>
                    <a class="sub-menu-item {{ Route::is('settings.popup') ? 'active' : '' }}" href="{{route('settings.popup')}}">Popup Settings</a>
                    <a class="sub-menu-item {{ Route::is('settings.resetPortal') ? 'active' : '' }}" href="{{route('settings.resetPortal')}}">Reset Portal</a>
                </div>
            </div>

            <!-- Exam Center -->
            @php $isExamActive = Route::is(['admin.createCenter', 'admin.listCenter']); @endphp
            <a class="side-nav-item {{ $isExamActive ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#examCenterMenu" href="javascript:void(0)" role="button" aria-expanded="{{ $isExamActive ? 'true' : 'false' }}" aria-controls="examCenterMenu">
                <img src="{{asset('admin/icons/ExamCentre.png')}}" class="nav_icon" alt="">
                <span>Exam Center</span>
                <i class="bi bi-chevron-right chevron-icon"></i>
            </a>
            <div class="collapse {{ $isExamActive ? 'show' : '' }}" id="examCenterMenu">
                <div class="sub-menu">
                    <a class="sub-menu-item {{ Route::is('admin.createCenter') ? 'active' : '' }}" href="{{route('admin.createCenter')}}">Add Center</a>
                    <a class="sub-menu-item {{ Route::is('admin.listCenter') ? 'active' : '' }}" href="{{route('admin.listCenter')}}">List Center</a>
                </div>
            </div>

            <!-- More Single Pages -->
            <a class="side-nav-item {{ Route::is('dashboard_eductaion_type') ? 'active' : '' }}" href="{{route('dashboard_eductaion_type')}}">
                <img src="{{asset('admin/icons/ScholarshipExam.png')}}" class="nav_icon" alt="">
                <span>Scholarship Exam</span>
            </a>
            <a class="side-nav-item {{ Route::is('dashboard_subjects') ? 'active' : '' }}" href="{{route('dashboard_subjects')}}">
                <img src="{{asset('admin/icons/Subject.png')}}" class="nav_icon" alt="">
                <span>Subjects</span>
            </a>
            <a class="side-nav-item {{ Route::is('admin.contactEnquiry') ? 'active' : '' }}" href="{{route('admin.contactEnquiry')}}">
                <img src="{{asset('admin/icons/ContactEnquiry.png')}}" class="nav_icon" alt="">
                <span>Contact Enquiries</span>
            </a>
            <a class="side-nav-item {{ Route::is('admin.terms_condition') ? 'active' : '' }}" href="{{route('admin.terms_condition')}}">
                <img src="{{asset('admin/icons/PDFContentUpload.png')}}" class="nav_icon" alt="">
                <span>PDF Content Upload</span>
            </a>
        </div>
    </div>
</nav>
<!-- /#sidebar-wrapper -->