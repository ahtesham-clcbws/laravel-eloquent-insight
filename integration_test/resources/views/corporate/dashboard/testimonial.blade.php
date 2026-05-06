@extends('corporate.layouts.master') 

@section('content')

<div class="row py-2 pl-3 pr-3">
    <div class="container ">
        <div class="row">
            @if(!$testimonial)
            <div class="col-lg-6">
                <div class="panel panel-default m-t-15">
                    <div class="card-header">
                        <h4> Say About Us:</h4>
                    </div>
                    <div class="panel-body">
                        <div class="card alert">
                            <div class="card-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <p class="text-muted f-s-12">Message*</p>
                                        <textarea style="width: 100%;" id="editor" name="testimonials_msg"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <p class="text-muted f-s-12">Add Profile Pic*</p>
                                        <input required type="file" id="fileInputlogo" class="form-control input-focus" onchange="validateImage(this)" name="profile_image">
                                        <img id="previewFileInputlogo" src="#" alt="Image Preview" style="display: none;width:200px">
                                    </div>

                                    <input type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" value="Submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @endif
            <div class="{{ $testimonial ? 'col-md-12' : 'col-md-6' }} ">
                <div class="card-header">
                    <h4>  Said About Us:</h4>
                </div>
                <div class="card">
                    @if ($testimonial)
                    <div class=" mb-4">
                        <div class="card-header text-end">
                            <!-- <a href="#">Edit</a> -->&nbsp;
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-7 col">
                                    <h5 class="card-title"><b>Name: </b>{{ $testimonial->name }}</h5>
                                    <p class="card-text"><b>Message: </b>{!! $testimonial->message !!}</p>
                                </div>
                                <div class="col-md-5 col"> @if ($testimonial->image)
                                    <img style="width: 64px;" src="{{ asset('home/' . $testimonial->image) }}" class="card-img-top" alt="{{ $testimonial->name }}">
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                    @else
                    <div class="">
                       <h5 class="m-3">You have not submitted a testimonial yet.</h5> 
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    const fileInput = document.getElementById('fileInputlogo');
    const imagePreview = document.getElementById('previewFileInputlogo');

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