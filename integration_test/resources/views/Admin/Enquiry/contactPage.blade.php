<!-- resources/views/home.blade.php -->
@extends('layouts.master')

@section('title', 'Admin Panel')

@section('pagetype', 'Quick Contact')

@section('breadcrumb')
    <ol class="breadcrumb text-right">
        <li><a href="{{ route('course.category') }}">Enquiry</a></li>
        <li><a href="{{ route('course.category') }}">Contact</a></li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default m-t-15">
                <div class="panel-heading">Quick Contact</div>
                <div class="panel-body">
                    <div class="card alert">
                        <div class="card-header">
                            <div class="row" style="margin-bottom: 15px">
                                <div class="col-lg-9"></div>
                                <div class="col-lg-2">
                                    <div class="badge badge-primary">Download as excel</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <td><span class="badge badge-primary">Sale</span></td> --}}
                                        @foreach ($enquiry as $enquirys)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $enquirys->fullname }}</td>
                                                <td>{{ $enquirys->mobile }}</td>
                                                <td>{{ $enquirys->message }}</td>
                                                <td>{{ $enquirys->created_at }}</td>
                                                <td style="text-align:center">
                                                    <ul>
                                                        <li class="card-option drop-menu"><i class="ti-settings"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="true" role="link"></i>
                                                            <ul class="card-option-dropdown dropdown-menu">
                                                                <li><a href="#" data-toggle="modal"
                                                                        data-target="#myModal"><i class="ti-loop"></i>
                                                                        Reply</a></li>
                                                                <li><a href="#"><i
                                                                            class="ti-menu-alt"></i>Restrict</a></li>
                                                                <li><a href="#"><i class="ti-menu-alt"></i>Delete</a>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <style>
                                                .email-container {
                                                    background-color: #f8f9fa;
                                                    padding: 20px;
                                                    border-radius: 10px;
                                                    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                                                }

                                                .email-form {
                                                    border: 1px solid #ced4da;
                                                    padding: 20px;
                                                    border-radius: 10px;
                                                    background-color: #fff;
                                                }

                                                .email-form textarea {
                                                    resize: none;
                                                }
                                            </style>
                                            <div id="myModal" class="modal fade custom" role="dialog">
                                                <div class="modal-dialog modal-xl">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="pop-up">
                                                            <div class="pop-up2">
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal">&times;</button>
                                                                <div class="container mt-4">
                                                                    <div class="row">
                                                                        <div class="col-md-7 mx-auto">
                                                                            <div class="email-container">
                                                                                <h4 class="mb-4"
                                                                                    style="font-weight: bold">Compose New
                                                                                    Email</h4>
                                                                                <hr>
                                                                                <form
                                                                                    action="{{ route('enquiry.replymail') }}"
                                                                                    method="POST"
                                                                                    enctype="multipart/form-data">
                                                                                    @csrf
                                                                                    <div class="form-group">
                                                                                        <label for="emailTo">To:</label>
                                                                                        <input type="email" name="emailto"
                                                                                            class="form-control"
                                                                                            id="emailTo"
                                                                                            placeholder="Enter recipient email">
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label for="emailCc">Cc:</label>
                                                                                        <input type="email" name="emailcc"
                                                                                            class="form-control"
                                                                                            id="emailCc"
                                                                                            placeholder="Enter cc email">
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="emailSubject">Subject:</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            name="emailsubject"
                                                                                            id="emailSubject"
                                                                                            placeholder="Enter subject">
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="emailBody">Message:</label>

                                                                                        <textarea id="editor" name="title"></textarea>
                                                                                        <div id="wordCount"></div>
                                                                                        <!-- Display word count here -->
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="attachment">Attachment:</label>

                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <input type="file" id="fileInput"
                                                                                            class="form-control input-focus"
                                                                                            name="image">
                                                                                        <img id="imagePreview"
                                                                                            src="#"
                                                                                            alt="Image Preview"
                                                                                            style="display: none;width:200px">

                                                                                    </div>

                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <button type="submit"
                                                                                                class="btn btn-primary btn-block">Send</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>

                                                                                <script>
                                                                                    // Function to count words in the textarea
                                                                                    function countWords() {
                                                                                        const textarea = document.getElementById('editor');
                                                                                        const wordCountDisplay = document.getElementById('wordCount');
                                                                                        const words = textarea.value.trim().split(/\s+/);
                                                                                        wordCountDisplay.textContent = `Word Count: ${words.length}`;
                                                                                    }

                                                                                    // Attach event listener to the textarea for word count
                                                                                    document.getElementById('editor').addEventListener('input', countWords);
                                                                                </script>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- SECTION: POP UP END -->
                                                    </div>
                                                </div>
                                            </div>
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


    <!-- Initialize CKEditor -->
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
