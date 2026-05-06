@extends('administrator.layouts.master')
@section('content')


<style>
    textarea {
        width: 100%;
    }
</style>
<div class="row py-2 pl-3 pr-3">
    <div class="container ">

        <!-- Section1 Start  -->
        <div class="row section_one">
            <div class="col-lg-6">
                <div class="panel panel-default m-t-15">
                    <div class="card-header">
                        <h4>Add About Us Banner:</h4>
                    </div>
                    <div class="panel-body">
                        <div class="card alert">
                            <div class="card-body">
                                <form id="form_about_banner" action="{{ route('admin.aboutus') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <p class="text-muted f-s-12">Title<span class="text-danger">*</span></p>
                                        <input id="banner_title" name="title" class="form-control">
                                        <input type="hidden" name="form_type" value="about_banner" class="form-control">
                                        <input id="banner_id" type="hidden" name="banner_id" value="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <p class="text-muted f-s-12">Add Banner Image<span class="text-danger">(Size:3000x700)*</span></p>
                                        <input type="file" id="fileInput" class="form-control input-focus" onchange="validateImage(this)" name="banner">
                                        <img id="imagePreview" src="#" alt="Image Preview" style="display: none;width:200px">
                                    </div>
                                    <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="card-header">
                    <h4>About Us Banner :</h4>
                </div>

                <div class="card table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sr.N.</th>
                                <th>Title</th>
                                <th>Banner</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($data['banners'])
                            @foreach ($data['banners'] as $banner)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $banner->title }}</td>

                                <td>
                                    <div style="text-align: center;">
                                        @if ($banner->banner)
                                        <a href="{{ asset('home/aboutus/'.$banner->banner) }}" target="_blank"> <img src="{{ asset('home/aboutus/'.$banner->banner) }}" alt="Banner Image" style="max-width: 50px; max-height: 40px;">
                                        </a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="form-control2">
                                        <label class="switch" for="status-{{ $banner->id }}">
                                            <input type="checkbox" id="status-{{ $banner->id }}" data-id="{{ $banner->id }}" onchange="toggleStatus(this, 'about_banner')" {{ $banner->status ? 'checked' : '' }}>
                                            <div class="slider round">
                                                <span class="off">Inactive</span>
                                                <span class="on">Active</span>
                                            </div>
                                        </label>
                                    </div>
                                </td>

                                <td style="text-align: center">
                                    <a href="javascript:void(0)" onclick="editBanner({{ json_encode($banner) }})" class="mr-2">
                                        <span class="fa fa-edit text-primary"></span>
                                    </a>
                                    <a href="{{ route('admin.home.aboutUsDelete', ['form_type' => 'about_banner','id' => $banner->id]) }}" onclick="return confirm('Are you sure?')">
                                        <span class="fa fa-trash text-danger"></span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Section1 End -->

        <!-- Founder thought Section Start  -->
        <div class="row section_founder">
            <div class="col-lg-6">
                <div class="panel panel-default m-t-15">
                    <div class="card-header">
                        <h4>Founder Thought Section:</h4>
                    </div>
                    <div class="panel-body">
                        <div class="card alert">
                            <div class="card-body">
                                <form id="form_about_founder" action="{{ route('admin.aboutus') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Title<span class="text-danger">*</span></p>
                                                <input id="founder_title" name="title" class="form-control" required placeholder="Enter title here">
                                                <input type="hidden" name="form_type" value="about_founder" class="form-control">
                                                <input id="founder_id" type="hidden" name="founder_id" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Add Icon Image<span class="text-danger">(60x60)*</span></p>
                                                <input type="file" id="founder_icon" class="form-control input-focus" onchange="validateImage(this)" name="icon">
                                                @error('icon')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <img id="founder_icon_preview" src="#" alt="Image Preview" style="display: none;width:100px">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Add Picture<span class="text-danger">(256x256)*</span></p>
                                                <input type="file" id="founder_picture" class="form-control input-focus" onchange="validateImage(this)" name="picture">
                                                @error('picture')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <img id="founder_picture_preview" src="#" alt="Image Preview" style="display: none;width:100px">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Name<span class="text-danger">*</span></p>
                                                <input id="name" name="name" class="form-control" required placeholder="Enter name here">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">message<span class="text-danger">*</span></p>
                                                <textarea id="founder_message" class="editor form-control w-100 ckeditor" style="width: 100%;" name="message" placeholder="Enter message here"></textarea>
                                                @error('message')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col text-center">
                                        <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card-header">
                    <h4>Found Message List:</h4>
                </div>

                <div class="card table-responsive">
                <div class="card-body" style="max-height: 435px; overflow-y: auto; padding:0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sr.N.</th>
                                <th>Title</th>
                                <th>Icon</th>
                                <th>Picture</th>
                                <th>Name</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($data['founders'])
                            @foreach ($data['founders'] as $founder)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $founder->title }}</td>

                                <td>
                                    <div style="text-align: center;">
                                        @if ($founder->icon)
                                        <a href="{{ asset('home/aboutus/'.$founder->icon) }}" target="_blank"> <img src="{{ asset('home/aboutus/'.$founder->icon) }}" alt="icon Image" style=" max-height: 40px;">
                                        </a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div style="text-align: center;">
                                        @if ($founder->picture)
                                        <a href="{{ asset('home/aboutus/'.$founder->picture) }}" target="_blank"> <img src="{{ asset('home/aboutus/'.$founder->picture) }}" alt="picture Image" style=" max-height: 50px;">
                                        </a>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $founder->name }}</td>
                                <td>
                                    <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 70px;">
                                        {!! $founder->message !!}
                                    </div>
                                </td>
                                <td>
                                    <div class="form-control2">
                                        <label class="switch" for="statusf-{{ $founder->id }}">
                                            <input type="checkbox" id="statusf-{{ $founder->id }}" data-id="{{ $founder->id }}" onchange="toggleStatus(this, 'about_founder')" {{ $founder->status ? 'checked' :
                                    '' }}>
                                            <div class="slider round">
                                                <span class="off">Inactive</span>
                                                <span class="on">Active</span>
                                            </div>
                                        </label>
                                    </div>
                                </td>

                                <td style="text-align: center">
                                    <a href="javascript:void(0)" onclick="editFounder({{ json_encode($founder) }})" class="mr-2">
                                        <span class="fa fa-edit text-primary"></span>
                                    </a>
                                    <a href="{{ route('admin.home.aboutUsDelete', ['form_type' => 'about_founder','id' => $founder->id]) }}" onclick="return confirm('Are you sure?')">
                                        <span class="fa fa-trash text-danger"></span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
        <!-- Founder thought Section End -->

        <!-- Section2 Start  -->
        <div class="row section_two">
            <div class="col-lg-12 col-md-12 col">
                <div class="panel panel-default m-t-15">
                    <div class="card-header">
                        <h4>Add Section Two (Vision/Mission/Values):</h4>
                    </div>
                    <div class="">
                        <div class="">
                            <form id="form_about_section2" action="{{ route('admin.aboutus') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card alert">
                                    <div class="row card-body">
                                        <div class="col-md-3 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Title<span class="text-danger">*</span></p>
                                                <input id="section2_title" name="title" class="form-control" required placeholder="Enter title here">
                                                @error('title')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <input type="hidden" name="form_type" value="about_section2" class="form-control">
                                                <input id="section_two_id" type="hidden" name="section_two_id" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Add Icon Image<span class="text-danger">(60x60)*</span></p>
                                                <input type="file" id="section2_banner" class="form-control input-focus" onchange="validateImage(this)" name="banner">
                                                @error('banner')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <img id="section2_banner_preview" src="#" alt="Image Preview" style="display: none;width:100px">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Description<span class="text-danger">(min character:20)*</span></p>
                                                <textarea id="section2_description" required class="editor form-control ckeditor" name="description" placeholder="Enter description here"></textarea>
                                                @error('description')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card alert">
                                    <div class="row card-body">
                                        <div class="col-md-3 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Heading A <span class="text-danger">*</span></p>
                                                <input required id="service_a_id" name="service_a" class="form-control" placeholder="Enter Heading A here">
                                                @error('service_a')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Add Image<span class="text-danger">(60x60)*</span></p>
                                                <input type="file" id="servicea_image" class="form-control input-focus" onchange="validateImage(this)" name="service_a_image">
                                                @error('service_a_image')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <img id="servicea_image_preview" src="#" alt="Image Preview" style="display: none;width:50px">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Heading A Description<span class="text-danger">(min character:20)*</span></p>
                                                <textarea id="section2_service_a_description" required class="editor form-control ckeditor" name="service_a_description" placeholder="Enter Service A description here"></textarea>
                                                @error('service_a_description')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Heading B<span class="text-danger">*</span></p>
                                                <input required id="service_b_id" name="service_b" class="form-control" placeholder="Enter Heading B here">
                                                @error('service_b')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Add Image<span class="text-danger">(60x60)*</span></p>
                                                <input type="file" id="serviceb_image" class="form-control input-focus" onchange="validateImage(this)" name="service_b_image">
                                                @error('service_b_image')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <img id="serviceb_image_preview" src="#" alt="Image Preview" style="display: none;width:50px">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Heading B Description<span class="text-danger">(min character:20)*</span></p>
                                                <textarea id="section2_service_b_description" required class="editor form-control ckeditor" name="service_b_description" placeholder="Enter Heading B description here"></textarea>
                                                @error('service_b_description')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Heading C<span class="text-danger">*</span></p>
                                                <input required id="service_c_id" name="service_c" class="form-control" placeholder="Enter Heading C here">
                                                @error('service_c')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Add Image<span class="text-danger">(60x60)*</span></p>
                                                <input type="file" id="servicec_image" class="form-control input-focus" onchange="validateImage(this)" name="service_c_image">
                                                @error('service_c_image')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <img id="servicec_image_preview" src="#" alt="Image Preview" style="display: none;width:50px">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Heading C Description<span class="text-danger">(min character:20)*</span></p>
                                                <textarea id="section2_service_c_description" required class="editor form-control ckeditor" name="service_c_description" placeholder="Enter Heading C description here"></textarea>
                                                @error('service_c_description')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Heading D<span class="text-danger">*</span></p>
                                                <input required id="service_d_id" name="service_d" class="form-control" placeholder="Enter Heading D here">
                                                @error('service_d')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Add Image<span class="text-danger">(60x60)*</span></p>
                                                <input type="file" id="serviced_image" class="form-control input-focus" onchange="validateImage(this)" name="service_d_image">
                                                @error('service_d_image')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <img id="serviced_image_preview" src="#" alt="Image Preview" style="display: none;width:50px">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Heading D Description<span class="text-danger">(min character:20)*</span></p>
                                                <textarea id="section2_service_d_description" required class="editor form-control ckeditor" name="service_d_description" placeholder="Enter Heading D description here"></textarea>
                                                @error('service_d_description')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <input type="submit" class="btn btn-primary btn-flat m-b-10 m-l-5" value="Submit Section Two">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card-header">
                    <h4>Section Two List (Vision/Mission/Values):</h4>
                </div>
                <div class="card table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sr.N.</th>
                                <th>Title</th>
                                <th>Image Icon</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($data['sectionTwo'])
                            @foreach ($data['sectionTwo'] as $sectionTwo)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $sectionTwo->title }}</td>

                                <td>
                                    <div style="text-align: center;">
                                        @if ($sectionTwo->banner)
                                        <a href="{{ asset('home/aboutus/'.$sectionTwo->banner) }}" target="_blank"> <img src="{{ asset('home/aboutus/'.$sectionTwo->banner) }}" alt="sectionTwo Image" style="max-width: 50px; max-height: 40px;">
                                        </a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="form-control2">
                                        <label class="switch" for="status-sectionTwo{{ $sectionTwo->id }}">
                                            <input type="checkbox" id="status-sectionTwo{{ $sectionTwo->id }}" data-id="{{ $sectionTwo->id }}" onchange="toggleStatus(this, 'about_sectionTwo')" {{ $sectionTwo->status ? 'checked' : '' }}>
                                            <div class="slider round">
                                                <span class="off">Inactive</span>
                                                <span class="on">Active</span>
                                            </div>
                                        </label>
                                    </div>
                                </td>

                                <td style="text-align: center">
                                    <a href="javascript:void(0)" onclick="editSectionTwo({{ json_encode($sectionTwo) }})" class="mr-2">
                                        <span class="fa fa-edit text-primary"></span>
                                    </a>
                                    <a href="{{ route('admin.home.aboutUsDelete', ['form_type' => 'about_sectionTwo','id' => $sectionTwo->id]) }}" onclick="return confirm('Are you sure?')">
                                        <span class="fa fa-trash text-danger"></span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Section2 End -->
        </div>
        <!-- Section two end -->
        <!-- Section1 Three Start  -->
        <div class="row section_three">
            <div class="col-lg-6">
                <div class="panel panel-default m-t-15">
                    <div class="card-header">
                        <h4>Add Section Three (Value for our clients):</h4>
                    </div>
                    <div class="panel-body">
                        <div class="card alert">
                            <div class="card-body">
                                <form id="form_about_section3" action="{{ route('admin.aboutus') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Section Title<span class="text-secondary">(Optional)</span></p>
                                                <input id="section3_title_main" name="section_title" class="form-control">
                                                @error('section_title')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <input type="hidden" name="form_type" value="about_section3" class="form-control">
                                                <input id="section_three_id" type="hidden" name="section_three_id" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Section Remarks<span class="text-secondary">(Optional)</span>
                                                </p>
                                                <input id="section3_remarks" name="section_remarks" class="form-control">
                                                @error('section_remarks')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Add Image<span class="text-danger">(Size:60x60)*</span></p>
                                                <input type="file" id="section3_image" class="form-control input-focus" onchange="validateImage(this)" name="image">
                                                @error('image')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <img id="section3_image_preview" src="#" alt="Image Preview" style="display: none;width:200px">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Tilte<span class="text-danger">*</span></p>
                                                <input required id="section3_title" name="title" class="form-control">
                                                @error('title')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Description<span class="text-danger">(min
                                                        character:10)*</span></p>
                                                <textarea required class="editor form-control ckeditor" id="section3_description" name="description" placeholder="Enter description here"></textarea>
                                                @error('description')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <input type="submit" class="btn btn-primary btn-flat m-b-10 m-l-5" value="Submit">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="card-header">
                    <h4>Section Three List(Value for our clients):</h4>
                </div>

                <div class="card table-responsive">
                    <div class="card-body" style="max-height: 409px; overflow-y: auto; padding:0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sr.N.</th>
                                    <th>Section Title</th>
                                    <th>Section Remarks</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($data['bannerSectionThrees'])
                                @foreach ($data['bannerSectionThrees'] as $bannerSectionThree)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $bannerSectionThree->section_title }}</td>
                                    <td>{{ $bannerSectionThree->section_remarks }}</td>
                                    <td>{{ $bannerSectionThree->title }}</td>
                                    <td>
                                        <div style="text-align: center;">
                                            @if ($bannerSectionThree->image)
                                            <a href="{{ asset('home/aboutus/'.$bannerSectionThree->image) }}" target="_blank"> <img src="{{ asset('home/aboutus/'.$bannerSectionThree->image) }}" alt="Banner Image" style="max-width: 50px; max-height: 40px;">
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-control2">
                                            <label class="switch" for="status-sectionThree{{ $bannerSectionThree->id }}">
                                                <input type="checkbox" id="status-sectionThree{{ $bannerSectionThree->id }}" data-id="{{ $bannerSectionThree->id }}" onchange="toggleStatus(this, 'about_sectionThree')" {{ $bannerSectionThree->status ? 'checked' :
                                    '' }}>
                                                <div class="slider round">
                                                    <span class="off">Inactive</span>
                                                    <span class="on">Active</span>
                                                </div>
                                            </label>
                                        </div>
                                    </td>

                                    <td style="text-align: center">
                                        <a href="javascript:void(0)" onclick="editGenericSection({{ json_encode($bannerSectionThree) }}, 'about_section3')" class="mr-2">
                                            <span class="fa fa-edit text-primary"></span>
                                        </a>
                                        <a href="{{ route('admin.home.aboutUsDelete', ['form_type' => 'about_sectionThree','id' => $bannerSectionThree->id]) }}" onclick="return confirm('Are you sure?')">
                                            <span class="fa fa-trash text-danger"></span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Section Three End -->
        <!-- Section1 Four Start  -->
        <div class="row section_Four">
            <div class="col-lg-6">
                <div class="panel panel-default m-t-15">
                    <div class="card-header">
                        <h4>Add Section Four(our values/Core values):</h4>
                    </div>
                    <div class="panel-body">
                        <div class="card alert">
                            <div class="card-body">
                                <form id="form_about_section4" action="{{ route('admin.aboutus') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Section Title<span class="text-secondary">(Optional)</span></p>
                                                <input id="section4_title_main" name="section_title" class="form-control">
                                                @error('section_title')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <input type="hidden" name="form_type" value="about_section4" class="form-control">
                                                <input id="about_section4_id" type="hidden" name="about_section4_id" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Section Remarks<span class="text-secondary">(Optional)</span>
                                                </p>
                                                <input id="section4_remarks" name="section_remarks" class="form-control">
                                                @error('section_remarks')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Add Image<span class="text-danger">(Size:1500x1000)*</span></p>
                                                <input type="file" id="section4_image" class="form-control input-focus" onchange="validateImage(this)" name="image">
                                                @error('image')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <img id="section4_image_preview" src="#" alt="Image Preview" style="display: none;width:200px">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Tilte<span class="text-danger">*</span></p>
                                                <input id="section4_title" name="title" class="form-control" required>
                                                @error('title')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Description<span class="text-danger">(min
                                                        character:10)*</span></p>
                                                <textarea required class="editor form-control ckeditor" id="section4_description" name="description" placeholder="Enter description here"></textarea>
                                                @error('description')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <input type="submit" class="btn btn-primary btn-flat m-b-10 m-l-5" value="Submit">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="card-header">
                    <h4>Section Four List(our values/Core values):</h4>
                </div>

                <div class="card table-responsive">
                    <div class="card-body" style="max-height: 409px; overflow-y: auto; padding:0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sr.N.</th>
                                    <th>Section Title</th>
                                    <th>Section Remarks</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($data['bannerSectionFours'])
                                @foreach ($data['bannerSectionFours'] as $bannerSectionFour)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $bannerSectionFour->section_title }}</td>
                                    <td>{{ $bannerSectionFour->section_remarks }}</td>
                                    <td>{{ $bannerSectionFour->title }}</td>
                                    <td>
                                        <div style="text-align: center;">
                                            @if ($bannerSectionFour->image)
                                            <a href="{{ asset('home/aboutus/'.$bannerSectionFour->image) }}" target="_blank"> <img src="{{ asset('home/aboutus/'.$bannerSectionFour->image) }}" alt="Banner Image" style="max-width: 50px; max-height: 40px;">
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-control2">
                                            <label class="switch" for="status-sectionFour{{ $bannerSectionFour->id }}">
                                                <input type="checkbox" id="status-sectionFour{{ $bannerSectionFour->id }}" data-id="{{ $bannerSectionFour->id }}" onchange="toggleStatus(this, 'about_sectionFour')" {{ $bannerSectionFour->status ? 'checked' :
                                    '' }}>
                                                <div class="slider round">
                                                    <span class="off">Inactive</span>
                                                    <span class="on">Active</span>
                                                </div>
                                            </label>
                                        </div>
                                    </td>

                                    <td style="text-align: center">
                                        <a href="javascript:void(0)" onclick="editGenericSection({{ json_encode($bannerSectionFour) }}, 'about_section4')" class="mr-2">
                                            <span class="fa fa-edit text-primary"></span>
                                        </a>
                                        <a href="{{ route('admin.home.aboutUsDelete', ['form_type' => 'about_sectionFour','id' => $bannerSectionFour->id]) }}" onclick="return confirm('Are you sure?')">
                                            <span class="fa fa-trash text-danger"></span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Section Four End -->
        <!-- Section1 Five Start  -->
        <div class="row section_Five">
            <div class="col-lg-6">
                <div class="panel panel-default m-t-15">
                    <div class="card-header">
                        <h4>Add Section Five (what Clients say):</h4>
                    </div>
                    <div class="panel-body">
                        <div class="card alert">
                            <div class="card-body">
                                <form id="form_about_section5" action="{{ route('admin.aboutus') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Section Title<span class="text-secondary">(Optional)</span></p>
                                                <input id="section5_title_main" name="section_title" class="form-control">
                                                @error('section_title')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <input type="hidden" name="form_type" value="about_section5" class="form-control">
                                                <input id="about_section5_id" type="hidden" name="about_section5_id" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Section Remarks<span class="text-secondary">(Optional)</span>
                                                </p>
                                                <input id="section5_remarks" name="section_remarks" class="form-control">
                                                @error('section_remarks')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Add Image<span class="text-danger">(Size:60x60)*</span></p>
                                                <input type="file" id="section5_image" class="form-control input-focus fileInput" onchange="validateImage(this)" name="image">
                                                @error('image')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <img id="section5_image_preview" src="#" alt="Image Preview" style="display: none;width:200px">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Tilte<span class="text-danger">*</span></p>
                                                <input id="section5_title" name="title" class="form-control" required>
                                                @error('title')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Description<span class="text-danger">(min
                                                        character:10)*</span></p>
                                                <textarea required class="editor form-control ckeditor" id="section5_description" name="description" placeholder="Enter description here"></textarea>
                                                @error('description')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <input type="submit" class="btn btn-primary btn-flat m-b-10 m-l-5" value="Submit">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="card-header">
                    <h4>Section Five List (what Clients say):</h4>
                </div>

                <div class="card table-responsive">
                    <div class="card-body" style="max-height: 409px; overflow-y: auto; padding:0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sr.N.</th>
                                    <th>Section Title</th>
                                    <th>Section Remarks</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($data['bannerSectionFives'])
                                @foreach ($data['bannerSectionFives'] as $bannerSectionFive)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $bannerSectionFive->section_title }}</td>
                                    <td>{{ $bannerSectionFive->section_remarks }}</td>
                                    <td>{{ $bannerSectionFive->title }}</td>
                                    <td>
                                        <div style="text-align: center;">
                                            @if ($bannerSectionFive->image)
                                            <a href="{{ asset('home/aboutus/'.$bannerSectionFive->image) }}" target="_blank"> <img src="{{ asset('home/aboutus/'.$bannerSectionFive->image) }}" alt="Banner Image" style="max-width: 50px; max-height: 40px;">
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-control2">
                                            <label class="switch" for="status-sectionFive{{ $bannerSectionFive->id }}">
                                                <input type="checkbox" id="status-sectionFive{{ $bannerSectionFive->id }}" data-id="{{ $bannerSectionFive->id }}" onchange="toggleStatus(this, 'about_sectionFive')" {{ $bannerSectionFive->status ? 'checked' :
                                    '' }}>
                                                <div class="slider round">
                                                    <span class="off">Inactive</span>
                                                    <span class="on">Active</span>
                                                </div>
                                            </label>
                                        </div>
                                    </td>

                                    <td style="text-align: center">
                                        <a href="javascript:void(0)" onclick="editGenericSection({{ json_encode($bannerSectionFive) }}, 'about_section5')" class="mr-2">
                                            <span class="fa fa-edit text-primary"></span>
                                        </a>
                                        <a href="{{ route('admin.home.aboutUsDelete', ['form_type' => 'about_sectionFive','id' => $bannerSectionFive->id]) }}" onclick="return confirm('Are you sure?')">
                                            <span class="fa fa-trash text-danger"></span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Section Five End -->

        <!-- Section1 Six Start  -->
        <div class="row section_Six">
            <div class="col-lg-6">
                <div class="panel panel-default m-t-15">
                    <div class="card-header">
                        <h4>Add Section Six (strategy & Consulting Services):</h4>
                    </div>
                    <div class="panel-body">
                        <div class="card alert">
                            <div class="card-body">
                                <form id="form_about_section6" action="{{ route('admin.aboutus') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Section Title<span class="text-secondary">(Optional)</span></p>
                                                <input id="section6_title_main" name="section_title" class="form-control">
                                                @error('section_title')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <input type="hidden" name="form_type" value="about_section6" class="form-control">
                                                <input id="about_section6_id" type="hidden" name="about_section6_id" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Section Remarks<span class="text-secondary">(Optional)</span>
                                                </p>
                                                <input id="section6_remarks" name="section_remarks" class="form-control">
                                                @error('section_remarks')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Add Image<span class="text-danger">(Size:60x60)*</span></p>
                                                <input type="file" id="section6_image" class="form-control input-focus fileInput" onchange="validateImage(this)" name="image">
                                                @error('image')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                                <img id="section6_image_preview" src="#" alt="Image Preview" style="display: none;width:200px">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Tilte<span class="text-danger">*</span></p>
                                                <input id="section6_title" name="title" class="form-control" required>
                                                @error('title')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 col">
                                            <div class="form-group">
                                                <p class="text-muted f-s-12">Description<span class="text-danger">(min
                                                        character:10)*</span></p>
                                                <textarea required class="editor form-control ckeditor" id="section6_description" name="description" placeholder="Enter description here"></textarea>
                                                @error('description')
                                                <div class="text-danger">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <input type="submit" class="btn btn-primary btn-flat m-b-10 m-l-5" value="Submit">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="card-header">
                    <h4>Section Six List (strategy & Consulting Services):</h4>
                </div>

                <div class="card table-responsive">
                    <div class="card-body" style="max-height: 409px; overflow-y: auto; padding:0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sr.N.</th>
                                    <th>Section Title</th>
                                    <th>Section Remarks</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($data['bannerSectionSixs'])
                                @foreach ($data['bannerSectionSixs'] as $bannerSectionSix)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $bannerSectionSix->section_title }}</td>
                                    <td>{{ $bannerSectionSix->section_remarks }}</td>
                                    <td>{{ $bannerSectionSix->title }}</td>
                                    <td>
                                        <div style="text-align: center;">
                                            @if ($bannerSectionSix->image)
                                            <a href="{{ asset('home/aboutus/'.$bannerSectionSix->image) }}" target="_blank"> <img src="{{ asset('home/aboutus/'.$bannerSectionSix->image) }}" alt="Banner Image" style="max-width: 50px; max-height: 40px;">
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-control2">
                                            <label class="switch" for="status-sectionSix{{ $bannerSectionSix->id }}">
                                                <input type="checkbox" id="status-sectionSix{{ $bannerSectionSix->id }}" data-id="{{ $bannerSectionSix->id }}" onchange="toggleStatus(this, 'about_sectionSix')" {{ $bannerSectionSix->status ? 'checked' :
                                    '' }}>
                                                <div class="slider round">
                                                    <span class="off">Inactive</span>
                                                    <span class="on">Active</span>
                                                </div>
                                            </label>
                                        </div>
                                    </td>

                                    <td style="text-align: center">
                                        <a href="javascript:void(0)" onclick="editGenericSection({{ json_encode($bannerSectionSix) }}, 'about_section6')" class="mr-2">
                                            <span class="fa fa-edit text-primary"></span>
                                        </a>
                                        <a href="{{ route('admin.home.aboutUsDelete', ['form_type' => 'about_sectionSix','id' => $bannerSectionSix->id]) }}" onclick="return confirm('Are you sure?')">
                                            <span class="fa fa-trash text-danger"></span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Section Six End -->
    </div>

    <script>
        const fileInput = document.getElementById('fileInput');
        const imagePreview = document.getElementById('imagePreview');

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '#';
                imagePreview.style.display = 'none';
            }
        });

        // Edit Functions
        function editBanner(data) {
            $('#banner_id').val(data.id);
            $('#banner_title').val(data.title);
            if (data.banner) {
                $('#imagePreview').attr('src', '{{ asset("home/aboutus") }}/' + data.banner).show();
            }
            $('html, body').animate({ scrollTop: $(".section_one").offset().top }, 500);
        }

        function editFounder(data) {
            $('#founder_id').val(data.id);
            $('#founder_title').val(data.title);
            $('#form_about_founder input[name="name"]').val(data.name);
            if (CKEDITOR.instances['founder_message']) CKEDITOR.instances['founder_message'].setData(data.message);
            // Handle image previews
             if (data.icon) {
                $('#founder_icon_preview').attr('src', '{{ asset("home/aboutus") }}/' + data.icon).show();
            }
             if (data.picture) {
                $('#founder_picture_preview').attr('src', '{{ asset("home/aboutus") }}/' + data.picture).show();
            }
            $('html, body').animate({ scrollTop: $(".section_founder").offset().top }, 500);
        }

        function editSectionTwo(data) {
            $('#section_two_id').val(data.id);
            $('#section2_title').val(data.title);
            
            $('#form_about_section2 input[name="service_a"]').val(data.service_a);
            $('#form_about_section2 input[name="service_b"]').val(data.service_b);
            $('#form_about_section2 input[name="service_c"]').val(data.service_c);
            $('#form_about_section2 input[name="service_d"]').val(data.service_d);
            
            if (CKEDITOR.instances['section2_description']) CKEDITOR.instances['section2_description'].setData(data.description);
            if (CKEDITOR.instances['section2_service_a_description']) CKEDITOR.instances['section2_service_a_description'].setData(data.service_a_description);
            if (CKEDITOR.instances['section2_service_b_description']) CKEDITOR.instances['section2_service_b_description'].setData(data.service_b_description);
            if (CKEDITOR.instances['section2_service_c_description']) CKEDITOR.instances['section2_service_c_description'].setData(data.service_c_description);
            if (CKEDITOR.instances['section2_service_d_description']) CKEDITOR.instances['section2_service_d_description'].setData(data.service_d_description);

            if (data.banner) $('#section2_banner_preview').attr('src', '{{ asset("home/aboutus") }}/' + data.banner).show();
            if (data.service_a_image) $('#servicea_image_preview').attr('src', '{{ asset("home/aboutus") }}/' + data.service_a_image).show();
            if (data.service_b_image) $('#serviceb_image_preview').attr('src', '{{ asset("home/aboutus") }}/' + data.service_b_image).show();
            if (data.service_c_image) $('#servicec_image_preview').attr('src', '{{ asset("home/aboutus") }}/' + data.service_c_image).show();
            if (data.service_d_image) $('#serviced_image_preview').attr('src', '{{ asset("home/aboutus") }}/' + data.service_d_image).show();

            $('html, body').animate({ scrollTop: $(".section_two").offset().top }, 500);
        }

        function editGenericSection(data, type) {
            let form = $('#form_' + type);
            if (type === 'about_section3') {
                $('#section_three_id').val(data.id);
                $('#section3_title_main').val(data.section_title);
                $('#section3_title').val(data.title);
                $('#section3_remarks').val(data.section_remarks);
                if (CKEDITOR.instances['section3_description']) CKEDITOR.instances['section3_description'].setData(data.description);
                if (data.image) $('#section3_image_preview').attr('src', '{{ asset("home/aboutus") }}/' + data.image).show();
                $('html, body').animate({ scrollTop: $(".section_three").offset().top }, 500);
            } else if (type === 'about_section4') {
                $('#about_section4_id').val(data.id);
                $('#section4_title_main').val(data.section_title);
                $('#section4_title').val(data.title);
                $('#section4_remarks').val(data.section_remarks);
                if (CKEDITOR.instances['section4_description']) CKEDITOR.instances['section4_description'].setData(data.description);
                if (data.image) $('#section4_image_preview').attr('src', '{{ asset("home/aboutus") }}/' + data.image).show();
                $('html, body').animate({ scrollTop: $(".section_Four").offset().top }, 500);
            } else if (type === 'about_section5') {
                $('#about_section5_id').val(data.id);
                $('#section5_title_main').val(data.section_title);
                $('#section5_title').val(data.title);
                $('#section5_remarks').val(data.section_remarks);
                if (CKEDITOR.instances['section5_description']) CKEDITOR.instances['section5_description'].setData(data.description);
                if (data.image) $('#section5_image_preview').attr('src', '{{ asset("home/aboutus") }}/' + data.image).show();
                $('html, body').animate({ scrollTop: $(".section_Five").offset().top }, 500);
            } else if (type === 'about_section6') {
                $('#about_section6_id').val(data.id);
                $('#section6_title_main').val(data.section_title);
                $('#section6_title').val(data.title);
                $('#section6_remarks').val(data.section_remarks);
                if (CKEDITOR.instances['section6_description']) CKEDITOR.instances['section6_description'].setData(data.description);
                if (data.image) $('#section6_image_preview').attr('src', '{{ asset("home/aboutus") }}/' + data.image).show();
                $('html, body').animate({ scrollTop: $(".section_Six").offset().top }, 500);
            }
        }
    </script>
   
    <script>
        function toggleStatus(element, $type) {

            var id = $(element).data('id');
            var isStatus = $(element).is(':checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('about_us.toggleStatus') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status: isStatus,
                    form_type: $type
                },
                success: function(response) {
                    if (response.status) {
                        success(response.message);
                    } else {
                        error(response.message);
                    }
                },
                error: function(xhr) {
                    error(xhr.responseText);
                }
            });
        };
    </script>
    @endsection