@extends('student.layouts.master')
@section('content')


@section('content')


<section class="content mt-5">
    <div class="card">
        <div class="card-body">
            <form id="uploadImage" action="{{route('students.profilePage')}}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="control-label">Name</label>
                                        <input type="text" name="name" value="{{$student->name}}" placeholder="Name" title="Please enter valid Name" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender" class="control-label">Gender</label>
                                        <select name="gender" class="form-control">
                                            <option value="">Select Gender</option>
                                            <option {{$student->gender == 'Male' ? 'selected' : ''}} value="Male">Male</option>
                                            <option {{$student->gender == 'Female' ? 'selected' : ''}} value="Female">FeMale</option>
                                            <option {{$student->gender == 'Transgender' ? 'selected' : ''}} value="Transgender">Transgender</option>
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
                                        <input readonly type="email" id="email" name="email" class="form-control" style="border: 1px solid #aaa;" value="{{$student->email}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col">
                                    <div class="form-group">
                                        <label for="Mobile No." class="control-label">Mobile No.</label>
                                        <input readonly type="number" id="mobile" name="mobile" minlength="10" maxlength="10" required="" class="form-control" value="{{$student->mobile}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col">
                            <div class="form-group">
                                <label for="disability" class="control-label">Person Disability</label>
                                <input type="radio" {{$student->disability == 'Yes' ? 'checked' : ''}} name="disability" value="Yes"> Yes
                                &nbsp; <input type="radio" {{$student->disability == 'No' ? 'checked' : ''}} name="disability" value="No"> No &nbsp;
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="photo">Attach Photograph (Max 2MB, JPG, PNG Only):<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="photograph" class="custom-file-input" value="{{$student->photograph}}" id="photo" onchange="validateImage(this,'imagepdf')" {{$student->photograph ? '' : 'required'}}>
                                        <label class="custom-file-label" for="photo">Choose file</label>
                                        @error('photograph')
                                        <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    @if($student->photograph)
                                    <div class="input-group-append photograph-image" style="margin-left: 10px;margin-top: -4rem;">
                                        <img src="{{ asset('upload/student/'.$student->photograph) }}" alt="photograph" style="width: 7rem;height: 8rem;margin-top: -10re;">
                                    </div>
                                    @endif
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

<script>
    $(document).ready(function() {
        $('#photo').change(function() {
            var formData = new FormData();
            formData.append('photograph', $(this)[0].files[0]);

            $.ajax({
                url: '{{ route("upload.photo") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    success(response.message)
                    location.reload();
                },
                error: function(xhr, status) {
                    error(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection