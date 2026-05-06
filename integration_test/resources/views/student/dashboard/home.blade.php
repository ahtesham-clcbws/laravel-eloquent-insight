@extends('student.layouts.master') 
@section('content') 

<div class="container pagecontentbody">
   <div class="tab-content">
      <div class="container mt-4">
         <div class="row">
            <div class="col-md-12">
               @if($student->scholarship_claim_generation_id)
                  <div class="alert alert-success shadow-sm">
                     <h4 class="alert-heading"><i class="fa fa-trophy mr-2"></i> Congratulations!</h4>
                     <p>You are eligible for the scholarship claim. You can now fill out the scholarship claim form to proceed.</p>
                     @if(!$student->studentClaimForm)
                        <hr>
                        <a href="{{ route('students.claimScholarshipForm') }}" class="btn btn-success">Fill Claim Form Now</a>
                     @endif
                  </div>

                  @if($student->studentClaimForm)
                     <div class="card mt-3 shadow-sm">
                        <div class="card-header bg-primary text-white">
                           <i class="fa fa-file-text-o mr-2"></i> Scholarship Claim Form Status
                        </div>
                        <div class="card-body text-center">
                           <p class="lead">Your scholarship claim form is currently:</p>
                           <h3 class="mb-4">
                              <span class="badge {{ $student->studentClaimForm->status == 'confirmed' ? 'bg-success' : ($student->studentClaimForm->status == 'rejected' ? 'bg-danger' : 'bg-warning') }} p-3">
                                 <i class="fa {{ $student->studentClaimForm->status == 'confirmed' ? 'fa-check-circle' : ($student->studentClaimForm->status == 'rejected' ? 'fa-times-circle' : 'fa-hourglass-half') }} mr-2"></i>
                                 {{ strtoupper(str_replace('-', ' ', $student->studentClaimForm->status)) }}
                              </span>
                           </h3>
                           @if($student->studentClaimForm->status == 'pending-processing')
                              <p class="text-muted">Our administrators are reviewing your application. Please check back soon.</p>
                           @elseif($student->studentClaimForm->status == 'confirmed')
                              <p class="text-success font-weight-bold">Your scholarship has been confirmed! We will contact you soon with further details.</p>
                           @elseif($student->studentClaimForm->status == 'rejected')
                              <p class="text-danger">Your scholarship claim was not approved at this time. Please contact administration for more information.</p>
                           @endif
                        </div>
                     </div>
                  @endif
               @else
                  <div class="card mt-4 shadow-sm">
                     <div class="card-body text-center py-5">
                        <i class="fa fa-info-circle fa-4x text-info mb-3"></i>
                        <h4 class="card-title d-block w-100">Scholarship Eligibility</h4>
                        <p class="card-text mt-3">Once you are marked as eligible for the scholarship based on your performance, you will be able to fill out the claim form here.</p>
                        <p class="text-muted">Please check your results or contact administration if you believe this is an error.</p>
                     </div>
                  </div>
               @endif
            </div>
         </div>
      </div>
   </div>
</div>
@endsection('content')