<?php

use Illuminate\Support\Facades\Auth;

$student = Auth::guard('student')->user();
// $student->latestStudentCode;
// $appCode = $student->latestStudentCode;
// $studentPayment = $student->studentPayment->last();

?>

<nav class="main-header navbar navbar-expand-lg navbar-light">
    <button type="button" class="hamburger animated fadeInLeft is-closed" data-toggle="offcanvas">
        <i class=" fa fa-bars"></i>
    </button>
    <h5 class="text-white pt-1">Student Panel</h5>
    
    <ul class="navbar-nav dashboard2" id="menu1-top">

        <?php
        $user = Auth::guard('student')->user(); ?>
        <li class="nav-item">
            <button class="panel-heading last_p studentImage">@if($student->photograph)
                <img src="{{ explode('/', $student->photograph)[0] == 'student' ? '/storage/'.$student->photograph : '/upload/student/'.$student->photograph  }}">
                @else
                <img src="{{asset('student/images/th_5.png')}}">
                @endif
            </button>
            <div class="dropdown-content panel-collapse profile-noti">
                <div class="profile-box">
                    @if($student->photograph)
                    <img src="{{ explode('/', $student->photograph)[0] == 'student' ? '/storage/'.$student->photograph : '/upload/student/'.$student->photograph  }}">
                    @else
                    <img src="{{asset('student/images/th_5.png')}}">
                    @endif
                    <h6>{{ucfirst($user->name)}}</h6>
                    <!-- <p>Course: </p> -->
                </div>
                <ul>
                    <li><a href="{{route('students.profilePage')}}"><i class="fa fa-user" aria-hidden="true"></i>
                            <span> Profile</span></a>
                    </li>
                    <li><a href="{{route('students.changePassword')}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span> Change Password</span></a></li>
                    <!--  <li><a href="#"><i class="fa fa-clock-o" aria-hidden="true"></i><span> Offers</span></a></li>
               <li><a href="#"><i class="fa fa-sliders" aria-hidden="true"></i><span> Information</span></a></li>
               <li><a href="#"><i class="fa fa-sliders" aria-hidden="true"></i><span> Activity</span></a></li> -->
                    <li class="last_rad"><a href="{{route('student.logout')}}"><i class="fa fa-sign-out" aria-hidden="true"></i><span> Sign Out</span></a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>