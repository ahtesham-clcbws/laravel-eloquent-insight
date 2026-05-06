@extends('administrator.layouts.master')
@section('title')
    Faq List
@endsection


@section('content')
    <style>
        .faq_w1 h1, h2, h3, h4, h5, h6, p {
            font-size: 13px;
        }
        .card {
            box-shadow: 0px 0px 5px 1px #ffc1074d !important;
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
        }
        .panel-heading {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
        }
    </style>
    <div class="p-4">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="panel-heading" id="formHeading">Add FAQ</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.home.faqSave') }}" method="POST" id="faqForm">
                            @csrf
                            <input type="hidden" name="id" id="faq_id" value="0">
                            <div class="row">
                                <div class="col-md-12 col">
                                    <div class="form-group mb-3">
                                        <label class="form-label text-muted f-s-12">Title</label>
                                        <textarea class="ckeditor" id="editor" name="title" style="width: 100%;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col">
                                    <div class="form-group mb-3">
                                        <label class="form-label text-muted f-s-12">Details</label>
                                        <textarea class="ckeditor" id="editor1" name="details" style="width: 100%;"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12 col text-center">
                                    <button type="button" class="btn btn-danger btn-flat m-b-10" id="resetBtn" onclick="resetFaqForm()" style="display:none;">Cancel</button>
                                    <input class="btn btn-warning btn-flat m-b-10 m-l-5" type="submit" id="submitBtn" value="Submit">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <div class="panel-heading"> FAQ List</div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                        <tbody>
                            @foreach ($faq as $faqs)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td class="faq_w1">
                                        {!! $faqs->title !!}<br />
                                        <small>{!! $faqs->details !!}</small>
                                    </td>
                                    <td>
                                        <div class="form-control2">
                                            <label class="switch" for="status-{{ $faqs->id }}">
                                                <input id="status-{{ $faqs->id }}" data-id="{{ $faqs->id }}"
                                                    type="checkbox" onchange="toggleStatus(this, 'home_faqs')"
                                                    {{ $faqs->status ? 'checked' : '' }}>
                                                <div class="slider round">
                                                    <span class="off">Inactive</span>
                                                    <span class="on">Active</span>
                                                </div>
                                            </label>
                                        </div>
                                    </td>

                                    <td class="text-end">
                                        <a href="javascript:void(0);" onclick="editFaq({{ $faqs->id }}, {{ json_encode($faqs->title) }}, {{ json_encode($faqs->details) }})">
                                            <i class="bi bi-pencil-square text-success me-2"></i>
                                        </a>
                                        <a href="{{ route('admin.home.faqDelete', ['id' => $faqs->id]) }}" onclick="return confirm('Are you sure you want to delete this FAQ?')">
                                            <i class="bi bi-trash2-fill text-danger"></i>
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

    @push('custom-scripts')
    <script>
        function editFaq(id, title, details) {
            $('#faq_id').val(id);
            CKEDITOR.instances['editor'].setData(title);
            CKEDITOR.instances['editor1'].setData(details);
            $('#submitBtn').val('Update');
            $('#formHeading').text('Update FAQ');
            $('#resetBtn').show();
            // Scroll to form
            $('html, body').animate({
                scrollTop: $("#faqForm").offset().top - 100
            }, 500);
        }

        function resetFaqForm() {
            $('#faq_id').val(0);
            CKEDITOR.instances['editor'].setData('');
            CKEDITOR.instances['editor1'].setData('');
            $('#submitBtn').val('Submit');
            $('#formHeading').text('Add FAQ');
            $('#resetBtn').hide();
        }
    </script>
    @endpush
@endsection
