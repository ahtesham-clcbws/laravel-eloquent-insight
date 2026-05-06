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
                     <label for="roll_number">Roll Number</label>
                     <input type="text" name="roll_number" class="form-control" placeholder="Enter Roll number" value="12345">
                  </div>
                  <div class="col">
                     <label for="class">--Select Class--</label>

                     <select class="form-control" id="select-class" name="class">
                        <option value="">--Select Class--</option>
                        <option value="2" {{request()->class == '2' ? 'selected' : ''}}>Class-2</option>
                        <option value="3" {{request()->class == '3' ? 'selected' : ''}}>Class-3</option>
                        <option value="4" {{request()->class == '4' ? 'selected' : ''}}>Class-4</option>
                        <option value="5" {{request()->class == '5' ? 'selected' : ''}}>Class-5</option>
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