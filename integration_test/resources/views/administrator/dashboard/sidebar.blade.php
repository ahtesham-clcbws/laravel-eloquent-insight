<!-- Sidebar -->
<nav class=" sidebar navbar-inverse fixed-top elevation-4" id="sidebar-wrapper" role="navigation" style="  font-family: 'Sansita', sans-serif;  font-style: italic !important;overflow-y: hidden;">
  <div class="sidebar-header">
    <div class="sidebar-brand">
      <div class="info">
        <img src="{{asset('admin/images/logo big size.png')}}" alt="">
      </div>
    </div>
    <div class="logo_area">
      <a href="#" class="brand-link">
        <img src="{{asset('admin/images/22.png')}}" alt="#" class="brand-image img-circle elevation-3" style="opacity: .8"></a>
      <div class="brand_link_name">
        <a href="#" class="brand-text font-weight-light director_name">Admin</a>
        <a href="#" class="director_post">Director</a>
      </div>

    </div>
  </div>
  <ul class="nav sidebar-nav">
    <li>
      <div class="dropdown show">
        <a class="btn btn-secondary dropdown-toggle" href="{{route('admin.home')}}">
          <img src="{{asset('admin/icons/Dashboard.png')}}" alt="" class="nav_icon">
          <p>Dashboard</p>
          <i class="fa fa-chevron-right"></i>
        </a>
      </div>
    </li>
    <li>
      <div class="dropdown active open show">
        <a class="btn btn-secondary dropdown-toggle " style="color:#fff !important" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>Home Page Master</p>
          <i class="fa fa-chevron-right"></i>
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" x-placement="top-start" >
          <a class="dropdown-item" href="{{route('admin.home.slider')}}">Slider Banner</a>
          <a class="dropdown-item" href="{{route('admin.home.courseList')}}">Courses List</a>
          <a class="dropdown-item" href="{{route('admin.home.govtwebsite')}}">Govt. website List</a>
          <a class="dropdown-item" href="{{route('admin.home.faq')}}">Faq List</a>
          <a class="dropdown-item" href="{{ route('news.blognews') }}">News</a>
          <a class="dropdown-item" href="{{ route('news.notification') }}">Notification</a>
          <a class="dropdown-item" href="{{ route('admin.home.eprospectus') }}">E-Prospectus</a>
          <a class="dropdown-item" href="{{ route('admin.home.ourJourney') }}">Our Journey</a>
          <a class="dropdown-item" href="{{ route('admin.home.ourContributor') }}">Our Contributor</a>
          <a class="dropdown-item" href="{{ route('admin.home.benefit') }}">Our Benefits</a>
        </div>
      </div>
    </li>
    <li>
      <div class="dropdown show">
        <a class="btn btn-secondary dropdown-toggle" href="{{route('admin.aboutus')}}">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>About Us Page Master</p>
          <i class="fa fa-chevron-right"></i>
        </a>
      </div>
    </li>
    <li>
      <div class="dropdown show">
        <a class="btn btn-secondary dropdown-toggle" href="{{route('admin.scholarship')}}">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>Scholarship Page</p>
          <i class="fa fa-chevron-right"></i>
        </a>
      </div>
    </li>
    <li>
      <div class="dropdown show">
        <a class="btn btn-secondary dropdown-toggle" href="{{route('admin.testimonialList')}}">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>Testimonial List</p>
          <i class="fa fa-chevron-right"></i>
        </a>
      </div>
    </li>
    <li>
      <div class="dropdown show">
        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>Discount Voucher</p>
          <i class="fa fa-chevron-right"></i>
        </a>

        <div class="dropdown-menu" style="color:aquamarine" aria-labelledby="dropdownMenuLink">
          <a class="dropdown-item" href="{{route('coupon.createCoupon')}}">Create Coupon</a>
          <a class="dropdown-item" href="{{route('coupon.lists')}}">Coupon List</a>
          <!-- <a class="dropdown-item" href="#">Applied Coupon</a>
          <a class="dropdown-item" href="#">Issued Coupon</a> -->
        </div>
      </div>
    </li>
    <li>
      <div class="dropdown show">
        <a class="btn btn-secondary dropdown-toggle" href="{{route('institute.list.new')}}">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>New Institute Enquiry</p>
          <i class="fa fa-chevron-right"></i>
        </a>
      </div>
    </li>
    <li>
      <div class="dropdown show">
        <a class="btn btn-secondary dropdown-toggle" href="{{route('institute.list.signup')}}">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>SignUp Institute</p>
          <i class="fa fa-chevron-right"></i>
        </a>
      </div>
    </li>
    <li>
      <div class="dropdown show">
        <a class="btn btn-secondary dropdown-toggle" href="{{route('institute.list')}}">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>Approved Institute</p>
          <i class="fa fa-chevron-right"></i>
        </a>
      </div>
    </li>

    <li>
      <div class="dropdown active open show">
        <a class="btn btn-secondary dropdown-toggle " style="color:#fff !important" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>Student</p>
          <i class="fa fa-chevron-right"></i>
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" x-placement="top-start" >
          <a class="dropdown-item" href="{{route('admin.studentList')}}">Student List</a>
          <a class="dropdown-item" href="{{route('admin.studentExamCenter')}}">Allot Exam Center</a>
        </div>
      </div>
    </li>
    <li>
      <div class="dropdown show">
        <a class="btn btn-secondary dropdown-toggle" href="{{route('dashboard_eductaion_type')}}" role="button">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>Scholarship exam</p>
          <i class="fa fa-chevron-right"></i>
        </a>
      </div>
    </li>
    <li>
      <div class="dropdown active open show">
        <a class="btn btn-secondary dropdown-toggle " style="color:#fff !important" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>Exam Center</p>
          <i class="fa fa-chevron-right"></i>
        </a>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" x-placement="top-start" >
          <a class="dropdown-item" href="{{route('admin.createCenter')}}">Add Center</a>
          <a class="dropdown-item" href="{{route('admin.listCenter')}}">List Center</a>
        </div>
      </div>
    </li>
    <li>
      <div class="dropdown show">
        <a class="btn btn-secondary dropdown-toggle" href="{{route('dashboard_subjects')}}">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>Subjects</p>
          <i class="fa fa-chevron-right"></i>
        </a>

      </div>
    </li>
    <li>
      <div class="dropdown show">
        <a class="btn btn-secondary dropdown-toggle" href="{{route('admin.contactEnquiry')}}">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>New Contact Enquiry</p>
          <i class="fa fa-chevron-right"></i>
        </a>
      </div>
    </li>
    <li>
      <div class="dropdown show">
        <a class="btn btn-secondary dropdown-toggle" href="#">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>Settings</p>
          <i class="fa fa-chevron-right"></i>
        </a>

      </div>
    </li>
    <li>
      <div class="dropdown show">
        <a class="btn btn-secondary dropdown-toggle" href="#">
          <img src="{{asset('admin/images/watch.png')}}" alt="" class="nav_icon">
          <p>Setxzxtings</p>
          <i class="fa fa-chevron-right"></i>
        </a>

      </div>
    </li>
  </ul>
</nav>
<!-- /#sidebar-wrapper -->