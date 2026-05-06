@extends('student.layouts.master') 
@section('content') 
@include('student.layouts.form_arrow_step')

<div class="container pagecontentbody mt-5 pt-5">
   <div class="tab-content">
      <div class="container">
      <div class="pagebody removebg-color">
         <div class="row justify-content-center">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-bordred table-hover bg-white">
                           <thead>
                              <tr>
                                 <th>Name</th>
                                 <th>Mobile</th>
                                 <th>Email</th>
                                 <th>DOB</th>
                                 <th>Payment Status</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td>{{$student->name}}</td>
                                 <td>{{$student->mobile}}</td>
                                 <td> {{$student->email}}</td>
                                 <td>{{$student->dob ?? '-'}}</td>
                                 <td>Pending</td>
                                 <td style="padding:0"> <a class="btn btn-info" style="margin:3px" href="{{route('studentform')}}" title="Edit">&nbsp;{{$student->form_step > 0 ? 'View' : 'Apply'}}&nbsp;</a> &nbsp;&nbsp; </a> </td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div></div>
@endsection('content')