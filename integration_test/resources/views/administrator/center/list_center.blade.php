@extends('administrator.layouts.master')

@section('content')
<style>
   .inside-table>thead>tr>th {
      border-color: #ccc;
      background-color: #fff;
      color: black
   }

   .inside-table thead {
      background-color: #fff !important;
      border-bottom: 1px solid #ccc
   }

   .inside-table> :not(:last-child)> :last-child>* {
      border-bottom-color: #ccc
   }
</style>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default m-t-15">
      <div class="panel-heading m-4 ">
        <h2>Exam Center List</h2>
      </div>
      <div class="panel-body px-3">
        <div class="card alert">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered datatablecl">
                <thead>
                  <tr>
                    <th>Sr.No</th>
                    <th>Center Name</th>
                    <th>Address</th>
                    <th>State</th>
                    <th>City</th>
                    <th>Landmark</th>
                    <th>Pincode</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($centers as $center)
                  <tr>
                    <th scope="row">{{$loop->index+1}}</th>
                    <td>{{$center->center_name}}</td>
                    <td>{{$center->address}}</td>
                    <td>{{$center->state?->name}}</td>
                    <td>{{$center->city?->name}}</td>
                    <td>{{$center->landmark}}</td>
                    <td>{{$center->pincode}}</td>
                    <td style="text-align:center">
                      <a href="{{ route('admin.createCenter', $center->id) }}" class="btn btn-primary" style="text-decoration: none;"></i> Edit</a>
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
</div>
</script>
@endsection