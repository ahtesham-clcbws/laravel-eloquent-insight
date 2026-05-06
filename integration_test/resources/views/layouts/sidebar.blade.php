 <?php

use Illuminate\Support\Facades\Auth;

$user = Auth::guard('student')->user();

$studCode = $user->studentCode->first();
?>

<nav class=" sidebar navbar-inverse fixed-top elevation-4" id="sidebar-wrapper" role="navigation" style="overflow-y: hidden;  font-style: italic !important;">
   <div class="sidebar-header">
      <div class="sidebar-brand">
         <div class="info">
            <a href="{{('studentDashboard')}}">
               <h5 style="margin-top: 20px;"> Dashboard</h5>
            </a>
            <!-- <img src="{{asset('student/images/logo big')}} size.png" alt=""> -->
         </div>
      </div>
      <div class="logo_area mb-2">
         <a href="{{('studentDashboard')}}" class="brand-link"> 
         @if($student->photograph)
         <img src="{{ explode('/', $student->photograph)[0] == 'student' ? '/storage/'.$student->photograph : '/upload/student/'.$student->photograph  }}" alt="Prifle Dp" class="brand-image img-circle elevation-3" style="opacity: .8">
            @else
             <img src="{{asset('student/images/th_5.png')}}" class="brand-image img-circle elevation-3" style="opacity: .8">
            @endif 
         </a>
         <div class="brand_link_name mb-2">
            <a href="{{('studentDashboard')}}" class="brand-text font-weight-light director_name">{{$user->name}}</a>
            <br>
            <?php 
              $studCode = $student->studentCode->first();
              ?>
            @if($studCode)
            <p style="color:#18c968;font-size: 15px;">Application No: {{$studCode->application_code}}</p>
         
            @endif
            <!-- <a href="#" class="director_post">Director</a> -->
         </div>
      </div>
   </div>
   <ul class="nav sidebar-nav">
      <li>
         <div class="dropdown show">
            <a class="btn btn-secondary dropdown-toggle" href="{{route('studentDashboard')}}" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <img src="{{asset('student/images/watch.png')}}" alt="" class="nav_icon">
               <p>Dashboard</p>
               <i class="fa fa-chevron-right"></i>
            </a>
            <!-- <div class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <a class="dropdown-item" href="#">Action</a> <a class="dropdown-item" href="#">Another action</a> <a class="dropdown-item" href="#">Something else here</a> </div> -->
         </div>
         @if($user->is_final_submitted)
         <div class="dropdown show">
            <a class="btn btn-secondary" href="{{route('students.formReview')}}">
               <img src="{{asset('student/images/8.png')}}" alt="" class="nav_icon">
               <p style="color:#18c968">Application Form</p>
            </a>
         </div>
         @endif
         @if($studCode?->is_paid || $studCode?->used_coupon)
         <div class="dropdown show">
            <a class="btn btn-secondary" href="{{route('students.admitCardSearch')}}">
               <img src="{{asset('student/images/12.png')}}" alt="" class="nav_icon">
               <p style="color:#18c968">Admid Card</p>
            </a>
         </div>
         @endif
         @if($studCode?->is_paid || $studCode?->used_coupon)
         <div class="dropdown show">
            <a class="btn btn-secondary" href="{{route('students.resultdownload')}}">
               <img src="{{asset('student/images/12.png')}}" alt="" class="nav_icon">
               <p style="color:#18c968">Result Download</p>
            </a>
         </div>
         <div class="dropdown show">
            <a class="btn btn-secondary" href="{{route('student.payment')}}">
               <img src="{{asset('student/images/12.png')}}" alt="" class="nav_icon">
               <p style="color:#18c968">Payment Details</p>
            </a>
         </div>
         @endif
      </li>

   </ul>
</nav>
<!-- /#sidebar-wrapper -->