@extends('administrator.layouts.master')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default m-t-15">
            <div class="panel-heading p-2">
                <h5>
                    SignUp Institute:
                </h5>
            </div>
            <div class="panel-body p-3">
                <div class="card alert">
                    <div class="card-body">
                    <a href="{{route('print.signup.institute.list')}}" target="blank" class="btn btn-primary btn-small">Print PDF</a>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="example">
                                <thead>
                                    <tr>
                                        <th style="text-align:left;">Sr.No.</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Inst.Name & City</th>
                                        <th>Email & Mobile</th>
                                        <th>Interested For</th>
                                        <th style="text-align:left;">Estd. Year</th>                                        
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- <td><span class="badge badge-primary">Sale</span></td> --}}
                                    @foreach ($institute as $institutes)
                                    <tr>
                                        <td style="text-align:left;">{{ $loop->iteration }}</td>
                                        <td><img src="{{ asset('/storage/'.$institutes->attachment)}}" style="width:50px;height:50px;border:1px solid #c2c2c2;border-radius:5px;"></td>
                    
                                        <td>
                                        
                                        {{ $institutes->name }}</td>
                                        <td><span class="text-Info">{{ $institutes->institute_name }}</span></span><br><span style="color:blue;">{{ $institutes->district?->name . ', '.$institutes->state?->name}}</span></td>
                                        <td>{{ $institutes->email }}<br>{{ $institutes->phone }}</td>
                                        <td>{{ $institutes->interested_for }}</td>
                                        <td style="text-align:left;">{{ $institutes->established_year }}</td>
                                        <td>
                                           @if(!is_null($institutes->signup_at)) <span class="badge rounded-pill bg-success">SignUp Success</span> @else <span class="badge rounded-pill bg-warning text-dark">Pending</span>  @endif 
                                        </td>
                                        <td class="text-end">
                                            <div style="display:flex">

                                                <a _target="blank" href="{{route('institute.list.signup',[$institutes])}}" class="btn btn-success">
                                                    <i class="bi bi-eye-fill"></i>
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