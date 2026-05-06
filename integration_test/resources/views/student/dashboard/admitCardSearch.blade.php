@extends('student.layouts.master')
@section('content')

<div class="container pagecontentbody pt-5">
   <div class="card">
      <div class="card-header">
         <h5>Download Admit Card:</h5>
      </div>
      <form action="{{route('students.admitCard')}}" method="post">
         @csrf
         <div class="card-body">
            <div class="card-text">
            <div class="form-row">
                  <div class="col">
                     <label for="app_code">Application Number</label>
                     <input type="text" name="app_code" class="form-control" placeholder="Enter Application Number" value="{{$studCode?->application_code}}">
                  </div>
                  <div class="col">
                     <label for="class">Select Class</label>
                     <select class="form-control" id="select-class" name="class">
                        <option value="">--Select Class--</option>
                        <option value="{{$qualification->id}}" {{request()->class == $qualification->id ? 'selected' : ''}}>{{$qualification->name}}</option>
                     </select>
                  </div>
               </div>
            </div>
         </div>
         <div class="card-footer">
            <div class="text-center">
               <button type="submit" class="btn btn-primary">Submit</button>
            </div>
         </div>
      </form>
   </div>
</div>
</div>
@endsection('content')