<div class="col-md-12 pageheader pt-4">
  <div class="row">
    <div class="col-md-12 col">
      <div class="arrow-steps clearfix">
        <a href="{{route('studentDashboard')}}">
          <div class="step current {{ Request::routeIs('studentDashboard') ? 'current-urltag' : '' }}" style="cursor: pointer;"><span>
              <i class="{{$student->form_step >= 0 ? 'fa fa-check' : 'fa fa-spinner'}}"></i></span> Dashboard
            </span>
          </div>
        </a>
        <a href="{{route('studentform')}}">
          <div class="step current {{ Request::routeIs('studentform') ? 'current-urltag' : '' }}" style="cursor: pointer;">
            <span>
              <i class="{{$student->form_step >= 1 ? 'fa fa-check' : 'fa fa-spinner'}}"></i>
            </span> Basic Details
            </span>
          </div>
        </a>&nbsp;&nbsp;
        <a href="{{ $student->form_step >= 2 ? route('students.addQualificationsCreate') : '#'}}">
          <div class="step current {{ Request::routeIs('students.addQualificationsCreate') ? 'current-urltag' : '' }}" style="cursor: pointer;"><span>
              <i class="{{$student->form_step >= 2 ? 'fa fa-check' : 'fa fa-spinner'}}"></i></span> Qualification Details
            </span>
          </div>
        </a>
        <a href="{{$student->form_step >= 3 ? route('students.additionalDetailCreate') : '#'}}">
          <div class="step current {{ Request::routeIs('students.additionalDetailCreate') ? 'current-urltag' : '' }}" style="cursor: pointer;"><span>
              <i class="{{$student->form_step >= 3 ? 'fa fa-check' : 'fa fa-spinner'}}"></i></span> Additional Details
            </span>
          </div>
        </a>
        <a href="{{$student->form_step >= 3 ? route('students.formReview') : ''}}">
          <div class="step current {{ Request::routeIs('students.formReview') ? 'current-urltag' : '' }}" style="cursor: pointer;"><span>
              <i class="{{$student->form_step >= 4 ? 'fa fa-check' : 'fa fa-spinner'}}"></i></span>Review
            </span>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>
<div class="col-12">
  <hr>
</div>