@extends('administrator.layouts.master')
@section('title')
Home Slider List
@endsection
@section('content')

<div class="row py-5 pl-3 pr-3">
    <div class="container p-0">
        <div class="dashboard-container mb-5">
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default m-t-15">
                        <div class="panel-heading">
                            <h4 class="panel-title"><strong>Add Slider</strong></h4>
                        </div>
                        <div class="panel-body">
                            <div class="card alert">
                                <div class="card-body">
                                    <form action="{{ route('admin.home.saveSlider') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                        <div class="col-md-12 col">
                                            <div class="form-group">
                                                <label for="image">Add Slider Image (Max 2MB, JPG, PNG Only):<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input class="form-control input-focus" type="file" name="image" id="fileInput" onchange="validateImage(this)" required>
                                                        @error('signature')
                                                        <div class="text-danger">{{$message}}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="input-group-append image-img-view" style="margin-left: 10px;margin-top: -2rem;">
                                                        <img src="#" alt="Image Preview" style="display:none; width: 7rem;height: 8rem;margin-top: -10re;" id="imagePreview">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col mb-3 d-flex">
                                        <label for="remark" class="mr-3">Remark</label>
                                            <input type="text" name="remark" placeholder="ENter remarks(Optional)" class="form-control input-focus">
                                        </div>
                                        <div class="col-md-4 col text-end " style="margin-left:-12px">
                                            <input type="submit" style="border-radius: 3px;" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                                        </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-6">
                    <h4 class="panel-title"><strong>Slider List</strong></h4>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Image</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sliders as $slider)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        <a target="_blank" class="btn btn-outline-secondary btn-success" href="{{ asset('home/slider/'.$slider->image) }}" style="background: none;border: none;">
                                            <img src="{{ asset('home/slider/'.$slider->image) }} " style="width: 43px;margin-top: -10px;border-radius:10px;height: 50px;">
                                    </td>
                                    <td>{{$slider->remark}}</td>
                                    <td>
                                    <div class="form-control2">
                                        <label class="switch" for="status-{{ $slider->id }}">
                                            <input type="checkbox" id="status-{{ $slider->id }}" data-id="{{ $slider->id }}" onchange="toggleStatus(this, 'home_slider')" {{ $slider->status ? 'checked' : '' }}>
                                            <div class="slider round">
                                                <span class="off">Inactive</span>
                                                <span class="on">Active</span>
                                            </div>
                                        </label>
                                    </div>
                                    </td>
                                    <td style="text-align: center">
                                        <a href="{{ route('admin.home.deleteSlider', ['id' => $slider->id]) }}">
                                            <span class="fa fa-trash"></span>
                                        </a>
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
</script>



@endsection