@extends('administrator.layouts.master')
@section('content')


<section class="content mt-5">
    <div class="card">
        <div class="card-body">
            <form id="uploadImage" action="{{route('admin.profile')}}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="name" class="control-label">Name</label>
                                        <input type="text" name="name" value="{{$user->name}}" placeholder="Name" title="Please enter valid Name" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender" class="control-label">Gender</label>
                                        <select name="gender" class="form-control">
                                            <option value="">Select Gender</option>
                                            <option {{$user->gender == 'Male' ? 'selected' : ''}} value="Male">Male</option>
                                            <option {{$user->gender == 'Female' ? 'selected' : ''}} value="Female">FeMale</option>
                                            <option {{$user->gender == 'Transgender' ? 'selected' : ''}} value="Transgender">Transgender</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col">
                            <div class="row">
                                <div class="col-md-6 col">
                                    <div class="form-group">
                                        <label for="Email" class="control-label">Email</label>
                                        <input type="email" id="email" name="email" class="form-control" style="border: 1px solid #aaa;" value="{{$user->email}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col">
                                    <div class="form-group">
                                        <label for="Mobile No." class="control-label">Mobile No.</label>
                                        <input type="number" id="mobile" name="mobile" minlength="10" maxlength="10" required="" class="form-control" value="{{$user->mobile}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="photo">Attach Photograph (Max 2MB, JPG, PNG Only):<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="photograph" class="custom-file-input" value="{{$user->photograph}}" id="photo" onchange="validateImage(this,'imagepdf')" {{$user->photograph ? '' : 'required'}}>
                                        <label class="custom-file-label" for="photo">Choose file</label>
                                        @error('photograph')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <!--@if($user->photograph)-->
                                    <!--<div class="input-group-append photograph-image" style="margin-left: 10px;margin-top: -4rem;">-->
                                    <!--    <a target="_blank" class="btn btn-outline-secondary btn-success" href="{{ asset('upload/'.$user->photograph) }}" style="background: none;border: none;">-->
                                    <!--        <img src="{{ asset('upload/'.$user->photograph) }}" alt="photograph" style="width: 7rem;height: 8rem;margin-top: -10re;">-->
                                    <!--    </a>-->
                                    <!--</div>-->
                                    <!--@endif-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-success" type="submit" id="imageSaveButton">
                                Update
                            </button>
                        </div>
                        <div class="col-md-4">

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection