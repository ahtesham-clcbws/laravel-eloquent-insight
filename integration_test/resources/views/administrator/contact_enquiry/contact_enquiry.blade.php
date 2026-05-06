@extends('administrator.layouts.master')
@section('content')

<style>
    .right-top-modal .modal-dialog {
        position: fixed;
        top: 10px;
        right: 10px;
        margin: 0;
        transform: translate3d(100%, 0, 0);
        transition: transform 0.3s ease-out;
    }

    .right-top-modal.show .modal-dialog {
        transform: translate3d(0, 0, 0);
    }
</style>
<div class="row py-2 pl-2 pr-3">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 text-start">
                <h4>Contact Enquiry List: </h4>
            </div>
        </div>
    </div>
    <div class="card p-0">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered inqueryTable">
                            <thead>
                                <tr>
                                    <th>Sr.No.</th>
                                    <th>Details</th>
                                    <th>Reason</th>
                                    <th>City</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th style="text-align:center">Reply Mail</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contactInfo as $key => $contact)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        {{ $contact->fullname }}<br />
                                        {{ $contact->mobile }}<br />
                                        {{ $contact->email }}
                                    </td>
                                    <td>{{ $contact->reason_contact}}</td>
                                    <td>{{ $contact->city}}</td>
                                    <td>{{ $contact->message}}</td>
                                    <td>
                                        <div class="form-control2">
                                            <label class="switch" for="featured-{{ $contact->id }}">
                                                <input type="checkbox" id="featured-{{ $contact->id }}" data-id="{{ $contact->id }}" class="toggle-featured" {{ $contact->is_featured ? 'checked' : '' }}>
                                                <div class="slider round">
                                                    <span class="off">Inactive</span>
                                                    <span class="on">Active</span>
                                                </div>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        @if($contact->email)
                                        <div class="col-md-12 col text-end">
                                            <button type="button" class="btn btn-primary" onclick="openModal(<?= $contact->id ?>)">
                                                Reply
                                            </button>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.contactEnquiryDelete', $contact->id) }}" class="btn btn-danger" style="text-decoration: none;"></i> Delete</a>
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
@foreach ($contactInfo as $key => $contact)
@if(is_null($contact->email))
@break
@endif
<div class="modal fade" id="importModal{{$key+1}}" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Mail Template:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <form method="post" action="{{route('admin.conatctEnqueryEeplyMail',$contact->id)}}" id="mail_Form{{$key}}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col">
                                <div>
                                    <strong>Name: </strong>{{$contact->fullname}}<br />
                                    <strong>Email: </strong>{{$contact->email}}<br />
                                    <strong>Phone: </strong>{{$contact->mobile}}<br />
                                    <strong>Reason: </strong>{{$contact->reason_contact}}<br />
                                    <strong>City: </strong>{{$contact->city}}<br />
                                    <strong>Message: </strong>{{$contact->message}}<br />
                                </div>
                            </div>
                            <div class="col-md-12 col">
                                <div class="mid-content">
                                    <textarea class="ckeditor" name="email_message" cols="30" rows="10" style="width: 100%;"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 col mt-4 text-center">
                                <button type="submit" class="btn btn-primary">
                                    Send
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach


<script src="{{asset('ckeditor/ckeditor.js')}}"></script>

<!-- Initialize CKEditor -->
<script>
    CKEDITOR.instances['IdOfCKEditorTextArea'].setData(data['body']);
</script>
<script>
    $(document).ready(function() {
        $('.inqueryTable').DataTable(); // Initialize DataTables
    });

    function openModal(contactId) {
        $('#importModal' + contactId).modal('show');
    }
    $(document).ready(function() {
    });
</script>
@endsection