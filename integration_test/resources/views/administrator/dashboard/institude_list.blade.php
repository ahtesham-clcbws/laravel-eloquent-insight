@extends('administrator.layouts.master')

@section('content')

<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default m-t-15">
      <div class="panel-heading">Institute Enquiry</div>
      <div class="panel-body">
        <div class="card alert">
          <div class="card-header">
            <!-- <div class="row" style="margin-bottom: 15px">
              <div class="col-lg-9"></div>
              <div class="col-lg-2">
                <div class="badge badge-primary">Download as excel</div>
              </div>
              <div class="col-lg-1">
                <div class="badge badge-primary">Print</div>
              </div>
            </div> -->
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Sr.No.</th>
                    <th>Name</th>
                    <th>Inst.Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Interested For</th>
                    <th>Estd. Year</th>
                    <th>City</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  {{-- <td><span class="badge badge-primary">Sale</span></td> --}}
                  @foreach ($institute as $institutes)
                  <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $institutes->name }}</td>
                    <td>{{ $institutes->institute_name }}</td>
                    <td>{{ $institutes->email }}</td>
                    <td>{{ $institutes->phone }}</td>
                    <td>{{ $institutes->interested_for }}</td>
                    <td class="color-primary">{{ $institutes->established_year }}</td>
                    <td>{{ $institutes->city }}</td>
                    <td class="text-end" >
                      <div style="display:flex">

                      <a _target="blank" href="{{route('institute.view',[$institutes])}}" class="btn btn-success">
                        <i class="bi bi-eye-fill"></i>
                      </a>
                      <a _target="blank" href="#" class="deletebutton ms-1 btn btn-danger">
                        <i class="bi bi-trash2"></i>
                      </a>
                      </div>
                      
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- /#page-content-wrapper -->
@endsection('content')