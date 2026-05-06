@extends('administrator.layouts.master')
@section('title')
Govt Website
@endsection

@section('content')
<div class="row py-5 pl-3 pr-3">
    <div class="container p-0">
        <div class="dashboard-container mb-5">
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default m-t-15">
                        <div class="panel-heading">
                            <h4 class="panel-title"><strong id="form-title">Add Govt Website Details</strong></h4>
                        </div>
                        <div class="panel-body">
                            <div class="card alert">
                                <div class="card-body">
                                    <form action="{{ route('admin.home.savegovtwebsite') }}" method="POST" enctype="multipart/form-data" id="govt-form">
                                        @csrf
                                        <input type="hidden" name="id" id="website-id">
                                        <div class="row">
                                            <div class="col-md-6 col">
                                                <div class="form-group">
                                                    <p>Website Url</p>
                                                    <input type="url" placeholder="https://example.gov.in" name="website_link" id="website_link" required class="form-control input-focus">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col">
                                                <div class="form-group">
                                                    <p>Remarks</p>
                                                    <input type="text" name="remark" id="remark" placeholder="Enter Remarks" class="form-control input-focus">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <p>Add Logo</p>
                                            <div class="input-group">
                                                <input type="file" id="fileInput" class="form-control input-focus" name="image">
                                                <div id="preview-container" style="display: none; margin-top: 10px;">
                                                    <img id="imagePreview" src="#" alt="Image Preview" style="width: 100px; border-radius: 5px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <input type="submit" id="submit-text" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                                            <button type="button" id="reset-btn" class="btn btn-default btn-flat m-b-10 m-l-5" style="display: none;">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <h4>Govt Website List</h4>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sr.No.</th>
                                    <th>Image</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($website as $websites)
                                <tr>
                                    <th scope="row">{{$loop->iteration}}</th>
                                    <td>
                                        <div style="width: 185px; word-wrap: break-word;">
                                            <a href="{{ $websites->website_link }}" target="_blank" class="text-primary d-block mb-1">
                                                {{ $websites->website_link}}
                                            </a>
                                            @php
                                                $imageUrl = str_starts_with($websites->image, 'govt_websites/') 
                                                    ? Storage::url($websites->image) 
                                                    : asset('home/courses/' . $websites->image);
                                            @endphp
                                            <a href="{{ $imageUrl }}" target="_blank">
                                                <img src="{{ $imageUrl }}" style="width: 50px; border-radius: 10px; border: 1px solid #ddd;">
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{$websites->remark}}</td>
                                    <td>
                                        <div class="form-control2">
                                            <label class="switch" for="status-{{ $websites->id }}">
                                                <input type="checkbox" id="status-{{ $websites->id }}" data-id="{{ $websites->id }}" onchange="toggleStatus(this, 'gov_website')" {{ $websites->status ? 'checked' : '' }}>
                                                <div class="slider round">
                                                    <span class="off">Inactive</span>
                                                    <span class="on">Active</span>
                                                </div>
                                            </label>
                                        </div>
                                    </td>
                                    <td style="text-align: center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="javascript:void(0)" class="edit-btn mr-2" 
                                                data-id="{{ $websites->id }}" 
                                                data-link="{{ $websites->website_link }}" 
                                                data-remark="{{ $websites->remark }}"
                                                data-image="{{ $imageUrl }}">
                                                <span class="fa fa-pencil text-info"></span>
                                            </a>
                                            <a href="{{ route('admin.home.deleteGovtwebsite', ['id' => $websites->id]) }}" onclick="return confirm('Are you sure?')">
                                                <span class="fa fa-trash text-danger"></span>
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

<script>
    $(document).ready(function() {
        const fileInput = document.getElementById('fileInput');
        const imagePreview = document.getElementById('imagePreview');
        const previewContainer = document.getElementById('preview-container');

        // File preview logic
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Edit functionality
        $('.edit-btn').on('click', function() {
            const data = $(this).data();
            
            $('#website-id').val(data.id);
            $('#website_link').val(data.link);
            $('#remark').val(data.remark);
            
            $('#form-title').text('Edit Govt Website');
            $('#submit-text').text('Update Details');
            $('#reset-btn').show();
            
            if (data.image) {
                imagePreview.src = data.image;
                previewContainer.style.display = 'block';
            }
            
            // Scroll to form
            $('html, body').animate({
                scrollTop: $("#govt-form").offset().top - 100
            }, 500);
        });

        // Reset form
        $('#reset-btn').on('click', function() {
            $('#govt-form')[0].reset();
            $('#website-id').val('');
            $('#form-title').text('Add Govt Website Details');
            $('#submit-text').text('Submit');
            $(this).hide();
            previewContainer.style.display = 'none';
            imagePreview.src = '#';
        });
    });
</script>
@endsection