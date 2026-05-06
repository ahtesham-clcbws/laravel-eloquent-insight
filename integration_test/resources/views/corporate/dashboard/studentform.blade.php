@extends('student.layouts.master')

@section('content')

<!-- Form step start -->
@include('student.layouts.form_arrow_step')
<!-- Form step end -->

<!-- InstanceBeginEditable name="Content Area" -->
@if($student->is_final_submitted) <style>
   input,
   select {
      pointer-events: none;
   }
</style>@endif
<div class="container pagecontentbody">
   <div class="tab-content">
      <div class="pagebody row">
         <div class="container col-md-9" style="border: 1px solid #c6cbd0;padding: 8px 25px;">
            <form method="post" action="{{route('students.addstudent')}}" enctype="multipart/form-data">
               @csrf

               <div class="row mt-2 ">
                  <div class="col-md-6 mb-3">
                     <label class="form-label">Student Name</label>
                     <input pattern="[A-Za-z ]+" class="form-control" value="{{$student->name}}" name="name" />
                     @error('name')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6 col mb-3">
                     <label class="form-label">Name of Father<span class="text-danger">*</span></label>
                     <input pattern="[A-Za-z ]+" class="form-control" placeholder="Enter name of Father" value="{{$student->father_name}}" name="father_name" required />
                     @error('father_name')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6 col mb-3">
                     <label class="form-label">Mobile<span class="text-danger">*</span></label>
                     <input pattern="[0-9]+" class="form-control" placeholder="Enter Mobile" value="{{$student->mobile}}" name="" disabled />
                  </div>
                  <div class="col-md-6 col mb-3">
                     <label class="form-label">Email ID<span class="text-danger">*</span></label>
                     <input pattern="[0-9]+" class="form-control" placeholder="Enter Email ID" value="{{$student->email}}" name="" disabled />
                  </div>

                  <div class="col-md-6 col mb-3">
                     <label class="form-label">Date of Birth<span class="text-danger">*</span></label>
                     <input type="date" class="form-control " placeholder="dd-mm-yyyy" value="{{$student->dob}}" name="dob" id="dob" required />
                     @error('dob')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6 col mb-3">
                     <label class="form-label">Gender<span class="text-danger">*</span></label>
                     <select name="gender" class="form-control form-select" id="gender" required>
                        <option value="">--Select Gender--</option>
                        <option value="Male" {{'Male' == $student->gender ? 'selected' : ''}}>Male</option>
                        <option value="Female" {{'Female' == $student->gender ? 'selected' : ''}}>Female</option>
                        <option value="Transgender" {{'Transgender' == $student->gender ? 'selected' : ''}}>Transgender</option>
                     </select>
                     @error('gender')
                     <div class="text-danger">{{ $message }}</div>
                     @enderror
                  </div>
                 

                  <div class="col-md-12 col mb-3">
                     <label class="form-label">Address<span class="text-danger">*</span></label>
                     <input class="form-control" name="address" placeholder="Enter address" value="{{$student->address}}" required />
                     @error('address')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
           
                  <div class="col-md-6 col mb-3">
                     <label class="form-label">State<span class="text-danger">*</span></label>
                     <select name="state_id" class="form-control form-select" id="state" onchange="selectDisctrict(this.value)" required>
                        <option value="">--Select state--</option>
                        @foreach($states as $state)
                     
                   
                        <option value="{{$state->id}}" {{$student->state_id == $state->id ? 'selected' : ''}}>{{$state->name}}</option>
                        @endforeach
                     </select>
                     @error('state_id')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6 col mb-3">
                     <label class="form-label">District/City<span class="text-danger">*</span></label>
                     <select name="district_id" class="form-control form-select" id="district" required>
                        <option value="">--Select District/City--</option>
                     </select>
                     @error('district_id')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-6 col mb-3">
                     <label class="form-label">Landmark</label>
                     <input type="text" placeholder="Enter landmark" class="form-control" name="landmark" value="{{$student->landmark}}" />
                     @error('landmark')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-3 col col mb-3">
                     <label class="form-label">Pincode<span class="text-danger">*</span><small>[Max Digits:6]</small></label>
                     <input type="text" pattern="[0-9]{6}" placeholder="Enter pincode" class="form-control" name="pincode" value="{{$student->pincode}}" required />
                     @error('pincode')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
                  <div class="col-md-3 col mb-3">
                     <div class="" style=" ">
                        <span style="display: block;font-family: Josefin Sans;font-size: 16px;font-weight: 600;padding: 7px 7px 10px 4px;">Person Disability</span>
                        &nbsp;&nbsp; <input type="radio" name="disability" value="Yes" {{$student->disability == 'Yes' ? 'checked' : ''}}> Yes
                        &nbsp; <input type="radio" name="disability" value="No" {{$student->disability == 'No' ? 'checked' : ''}}> No &nbsp;
                     </div> @error('disability')
                     <div class="text-danger">{{$message}}</div>
                     @enderror
                  </div>
               </div>

               <div class="row justify-content-center">
                  <div class="col-12">
                     <hr />
                  </div>
                  <div class="col-md-3 d-grid">
                     <button type="submit" class="btn btn-theme submitform">Save and Next</button>
                  </div>

               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<script>

   function selectDisctrict(state) {
      console.log(state)
      console.log('vvv')
      if (state) {
         fetchDistricts(state);
      } else {
         $('#district').html('<option value="">--Select district--</option>');
      }
   }

   $('#state').val({{ $student->state_id ?? 'null'}}).trigger('change');

   // Function to fetch subjects based on subcategory
   function fetchDistricts(state) {
      $.get('/districts/' + state, function(data) {
         $('#district').empty().append('<option value="">--Select District/City--</option>');
         $.each(data, function(index, district) {
            var selected = ({{$student->district_id ?? 'null'}} == district.id) ? 'selected' : '';
            $('#district').append('<option value="' + district.id + '" ' + selected + '>' + district.name + '</option>');
         });
      });
   }

   // Calculate the date 10 years ago from today
   var today = new Date();
   var tenYearsAgo = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate());

   // Format the date as required by the date input
   var formattedDate = tenYearsAgo.toISOString().split('T')[0];

   // Set the max attribute of the input element
   document.getElementById("dob").setAttribute("max", formattedDate);
</script>
<!-- InstanceEndEditable -->
@endsection('content')