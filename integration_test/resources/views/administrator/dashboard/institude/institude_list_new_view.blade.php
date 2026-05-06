@extends('administrator.layouts.master')

@section('content')

<style>
    /* .coupons-container>.institudeCouponGrid {
        background-color: #dedcdc;
        padding: 12px;
        margin: 4px 4px 0px 0px;
    } */
    .institudeCouponGrid {
        background-color: #b4afaf;
        padding: 12px;
        margin: 14px 0px 0px 0px;
        margin-left: -12px;
        margin-right: -15px;
    }

    /* Adjust the input width and margin */
    .institute-coupon-grid-content-input input.form-control {
        width: 80%;
        margin-right: 10px;
        /* Adjust as needed */
    }

    /* Center the Apply button vertically */
    .institute-coupon-grid-content-input .input-group-append .apply-btn {
        display: flex;
        align-items: center;
    }

    /* Style the Apply button */
    .institute-coupon-grid-content-input .input-group-append .apply-btn {
        cursor: pointer;
        background-color: #007bff;
        /* Example color, adjust as needed */
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
    }

    /* Hover effect for Apply button */
    .institute-coupon-grid-content-input .input-group-append .apply-btn:hover {
        background-color: #0056b3;
        /* Example color, adjust as needed */
    }

    .table td,
    .table th {
        padding: .5rem .5rem;
    }
</style>

<div class="pt-3 pb-2 custom-dashboard">
    <div class="w-100 dashboard-header mb-4">
        <h2 class="d-inline-block">
            <!--<i class="bi bi-house-fill"></i>-->
        </h2>
    </div>

    <input class="d-none" id="franchiseId" value="{{$corporate->id}}">
    <section class="content admin-1">
        <div class="row corporate-cards">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header" style="background-color:#19467a ; color: white;">
                        <div>
                            <h5>Enquiry Detail: </h5>
                        </div>

                        <div>
                            <h5>Created on: {{$corporate->created_at}}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <td colspan="2"><b>Name</b></td>
                                            <td class="information-txt">{{$corporate->name}}</td>
                                            <td rowspan="2" class="userImageCell">
                                                @if ($corporate->attachment)
                                                <img id="profile_img" src="{{ asset('/storage/'.$corporate->attachment)}}" style="width:80px;height:80px;border:1px solid #c2c2c2;">
                                                @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 100 100">
                                                    <rect width="100%" height="100%" fill="#000000" />
                                                    <path fill="#FFA500" d="M36.015 37.99h3.79v23.14h-2.2q-.52 0-.86-.17-.34-.17-.66-.57l-12.08-15.42q.09 1.06.09 1.95v14.21h-3.79V37.99h2.26q.27 0 .47.03.2.02.35.09.15.08.3.21.14.14.32.36l12.12 15.49-.08-1.1q-.03-.55-.03-1.01V37.99Zm20.35-.64-9.28 23.83q-.27.73-.87 1.1-.6.37-1.22.37h-1.68l9.34-23.9q.26-.68.79-1.04.52-.36 1.23-.36h1.69Zm8.37 15.04h7.36l-2.81-7.69q-.21-.51-.44-1.22-.22-.7-.44-1.52-.21.82-.44 1.53-.22.71-.43 1.24l-2.8 7.66Zm5.87-14.4 9.09 23.14h-3.33q-.56 0-.91-.28t-.53-.7l-1.72-4.72h-9.59l-1.73 4.72q-.12.37-.49.68-.37.3-.91.3h-3.36l9.1-23.14h4.38Z" />
                                                </svg>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Mobile</b></td>
                                            <td class="information-txt">{{$corporate->phone}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Email</b></td>
                                            <td class="information-txt" colspan="2">{{$corporate->email}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Address</b></td>
                                            <td class="information-txt" colspan="2">{{$corporate->address}}
                                                &nbsp;&nbsp;, <strong>City: &nbsp;</strong>{{ $corporate->district?->name . ', '.$corporate->state?->name}}
                                                <!-- <strong>State: &nbsp;</strong>Uttar Pradesh -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <b>Inst. Name</b>
                                            </td>
                                            <td class="information-txt" colspan="2">{{ $corporate->institute_name}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Institute Type</b></td>
                                            <td class="information-txt" colspan="2">
                                                <span class="commaList">{{ $corporate->type_institution}}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Interested For</b></td>
                                            <td class="information-txt" colspan="2">

                                                <span class="commaList">{{$corporate->interested_for}}</span>
                                                <!-- <span class="commaList">Offline Test (At Centers/Schools )</span>
                                                <span class="commaList">Study Notes (Book/Paper Notes)</span> -->
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2"><b>Action</b></td>
                                            <td colspan="2">
                                                <button type="button" class="btn btn-link text-danger action-button" onclick="showReject()">Reject</button>
                                                @if( !$corporate->is_approved )
                                                <button type="button" class="btn btn-link action-button action-button-pending" onclick="showPending()" style="color: #F48134;">Pending</button>
                                                <button type="button" class="btn btn-link text-success action-button action-button-approve" onclick="showApproved()">Approve</button>
                                                @endif
                                                <button type="button" class="btn btn-link text-info action-button" onclick="showReply()">Reply</button>
                                                @if( $corporate->is_approved )
                                                <button type="button" class="btn btn-link text-info action-button-coupon-allotment" onclick="showCouponAllotment()">Coupon Allotment</button>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Status</b></td>
                                            <td class="bg-info" colspan="2">
                                                @if( !$corporate->is_approved )
                                                <span class="text-white">New</span>
                                                @else
                                                <span class="text-white">Approved</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Established Year</b></td>
                                            <td colspan="2">
                                                {{$corporate->established_year}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Branch Code</b></td>
                                            <td colspan="2">
                                                {{$corporate->institude_code}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Subscription</b></td>
                                            <td colspan="2">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-nowrap"><b>Institute Images PDF</b></td>
                                            <td class="information-txt" colspan="2">

                                                <a href="{{ url('/storage/'.$corporate->attachment_profile) }}" target="_blank">
                                                    Open to View
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if( $corporate->is_approved )
            <div class="col-md-6 col-12">
                <div class="card" id="reply-show">
                    <div class="card-header" style="background-color:#0DCAF0; color: #fff;">
                        <h5>Reply</h5>
                    </div>
                    <div class="card-body">
                        <div class="modalLoader" id="reply-loader" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="mid-content">
                            <textarea id="reply-message" name="type" cols="30" rows="10"></textarea>
                        </div>
                        <div class="control-area">
                            <button class="btn btn-danger" onclick="closeBox()">Close</button>
                            <button class="btn btn-success action-button-reply" onclick="submitReply('reply', this)">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="card" id="approved-show">
                    <div class="card-header" style="background-color:#18c968; color: #fff;">
                        <h5>Approve</h5>
                    </div>
                    <div class="card-body">
                        <div class="modalLoader" id="approved-loader" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="mid-content">
                            <textarea id="approved-message" name="type" cols="30" rows="10">
                            @if($corporate->institude_code)    
                            Please use this {{ $corporate->institude_code  }} branch code, to signup so we can go further on your request.
                            @endif
                            </textarea>
                        </div>
                        <div class="control-area">
                            <button class="btn btn-danger" onclick="closeBox()">Close</button>
                            <button class="btn btn-success approvedbtn" onclick="submitReply('approved', this)">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="card" id="reject-show">
                    <div class="card-header" style="background-color:#ff0000; color: #fff;">
                        <h5>Reason to reject</h5>
                    </div>
                    <div class="card-body">
                        <div class="modalLoader" id="reject-loader" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="mid-content">
                            <textarea id="reject-message" name="type" cols="30" rows="10">We are sorry to inform you that your business request is rejected by our Authorisation Team.
                                The rejection was made, due to insufficient/wrong information while physical inspection by the authorisation
                                team.</textarea>
                        </div>
                        <div class="control-area">
                            <button class="btn btn-danger" onclick="closeBox()">Close</button>
                            <button class="btn btn-success action-button-reject" onclick="submitReply('reject', this)">Submit</button>
                        </div>
                    </div>
                </div>

            </div>
            @endif
            <div class="col-md-6 col-12">
                <div class="card" id="reply-show">
                    <div class="card-header" style="background-color:#0DCAF0; color: #fff;">
                        <h5>Reply</h5>
                    </div>
                    <div class="card-body">
                        <div class="modalLoader" id="reply-loader" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="mid-content">
                            <textarea id="reply-message" name="type" cols="30" rows="10"></textarea>
                        </div>
                        <div class="control-area">
                            <button class="btn btn-danger" onclick="closeBox()">Close</button>
                            <button class="btn btn-success action-button-reply" onclick="submitReply('reply', this)">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="card" id="approved-show">
                    <div class="card-header" style="background-color:#18c968; color: #fff;">
                        <h5>Approve</h5>
                    </div>
                    <div class="card-body">
                        <div class="modalLoader" id="approved-loader" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="mid-content">
                            <textarea id="approved-message" name="type" cols="30" rows="10"> @if($corporate->institude_code)Please use this {{ $corporate->institude_code  }} branch code, to signup so we can go further on your request.
                            @endif
                            </textarea>
                        </div>
                        <div class="control-area">
                            <button class="btn btn-danger" onclick="closeBox()">Close</button>
                            <button class="btn btn-success approvedbtn" onclick="submitReply('approved', this)">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="card" id="reject-show">
                    <div class="card-header" style="background-color:#ff0000; color: #fff;">
                        <h5>Reason to reject</h5>
                    </div>
                    <div class="card-body">
                        <div class="modalLoader" id="reject-loader" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="mid-content">
                            <textarea id="reject-message" name="type" cols="30" rows="10">We are sorry to inform you that your business request is rejected by our Authorisation Team.
                                The rejection was made, due to insufficient/wrong information while physical inspection by the authorisation
                                team.</textarea>
                        </div>
                        <div class="control-area">
                            <button class="btn btn-danger" onclick="closeBox()">Close</button>
                            <button class="btn btn-success action-button-reject" onclick="submitReply('reject', this)">Submit</button>
                        </div>
                    </div>
                </div>
                @if( !$corporate->is_approved )
                <div class="card" id="pending-show">
                    <div class="card-header" style="background-color:#F48134; color: #fff;">
                        <h5>Reason for pending</h5>
                    </div>
                    <div class="card-body">
                        <div class="modalLoader" id="pending-loader" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="mid-content">
                            <textarea id="pending-message" name="type" cols="30" rows="10"></textarea>
                        </div>
                        <div class="control-area">
                            <button class="btn btn-danger" onclick="closeBox()">Close</button>
                            <button class="btn btn-success action-button-pending" onclick="submitReply('pending', this)">Submit</button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-7 d-none">
                <div class="table-responsive">
                    <table class="table table-bordered border-primary corporate-table">
                        <tbody>
                            <tr>
                                <th>Actions</th>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveBox">Ok</button>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectBox">Reject</button>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#replyBox">Reply</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <div class="toast align-items-center text-white border-0 position-absolute bottom-0 end-0 mb-3" data-delay="5000" role="alert" aria-live="assertive" aria-atomic="true" id="responseToast">
        <div class="d-flex">
            <div class="toast-body" id="responseToastMessage">
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    $(".institudeCouponGrid").on('click', function() {
        $(".institute-coupon-grid-content-input").not($(this).parent().next()).hide();

        if ($(this).data('couponremain') == 0) {
            error('The Coupon is not avaialable.');
            return;
        }
        $(this).parent().next().toggle();
    });

    $('.select2').select2();

    function showToast(message, bgColor) {
        var responseToastMessage = $('#responseToastMessage');
        responseToastMessage.html('');
        responseToastMessage.html(message);
        var responseToast = document.getElementById('responseToast');
        responseToast.classList.remove('bg-success');
        responseToast.classList.remove('bg-danger');
        responseToast.classList.remove('bg-warning');
        responseToast.classList.add(bgColor);
        responseToast = new bootstrap.Toast(responseToast);
        responseToast.show();
    }
    var rejectBox = $('#reject-show')
    var approvedBox = $('#approved-show')
    var pendingBox = $('#pending-show')
    var replyBox = $('#reply-show')
    var formBox = $('#reply-hidden')
    var couponAllotment = $('#coupon-allotment-show')

    function showReply() {
        rejectBox.hide();
        approvedBox.hide();
        pendingBox.hide();
        replyBox.show();
        formBox.hide();
    }

    function showCouponAllotment() {
        rejectBox.hide();
        approvedBox.hide();
        pendingBox.hide();
        replyBox.hide();
        formBox.hide();
        couponAllotment.show();
    }

    function showReject() {
        rejectBox.show();
        approvedBox.hide();
        pendingBox.hide();
        replyBox.hide();
        formBox.hide();
    }

    function showApproved() {
        rejectBox.hide();
        approvedBox.show();
        pendingBox.hide();
        replyBox.hide();
        formBox.hide();
    }

    function showPending() {
        rejectBox.hide();
        approvedBox.hide();
        pendingBox.show();
        replyBox.hide();
        formBox.hide();
    }

    function closeBox() {
        rejectBox.hide();
        approvedBox.hide();
        pendingBox.hide();
        replyBox.hide();
        formBox.hide();
        couponAllotment.hide();
    }

    function closeMessage(messageBox, loader) {
        messageBox.removeClass('is-invalid');
        messageBox.removeClass('is-valid');
        loader.hide();
        rejectBox.hide();
        approvedBox.hide();
        pendingBox.hide();
        replyBox.hide();
        formBox.hide();
    }

    function submitReply(type, button) {
        const message = $('#' + type + '-message');
        const actionbtn = $('.action-button-' + type);
        actionbtn.attr('disabled', true);
        if (!message.val()) {
            message.addClass('is-invalid');
            message.removeClass('is-valid');
            return;
        } else {
            message.addClass('is-valid');
            message.removeClass('is-invalid');
        }
        const loader = $('#' + type + '-loader');
        loader.show();
        var formData = new FormData();
        formData.append('type', "{{$corporate->id}}")
        formData.append('message', message.val())
        formData.append('type', type)
        formData.append('name', "{{$corporate->name}}")
        formData.append('id', "{{$corporate->id}}")
        formData.append('phone', "{{$corporate->phone}}")
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'))

        console.log(Array.from(formData));

        $.ajax({
            url: "{{route('institute.institute_status')}}",
            // url: '',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false
        }).done(function(data) {
            loader.hide();
            actionbtn.attr('disabled', false);

            if (data.success) {
                success(data.message)
                if (type == 'approved') {
                    $('.approvedbtn').hide()
                    $('.action-button-pending').hide()
                    $('.action-button-approve').hide();
                    message.val("Please use this " + data.code + " branch code, to signup so we can go further on your request.");

                }
                if (type == 'reply') {
                    $('.approvedbtn').hide()
                    $('.action-button-pending').hide()
                    $('.action-button-reply').hide();
                }
                if (type == 'reject') {
                    $('.approvedbtn').hide()
                    $('.action-button-pending').hide()
                    $('.action-button-reject').hide();
                }
                if (type == 'pending') {
                    $('.approvedbtn').hide()
                    $('.action-button-pending').hide()
                }
                closeBox();
            } else {
                errors(data.message)
                actionbtn.attr('disabled', false);

            }

        }).fail(function(err) {
            console.log('error', err)
            showToast(err.statusText, 'bg-danger');
            errors(data.message)
            loader.hide();
            actionbtn.attr('disabled', false);

            // closeMessage(message, loader);
        });
    }

    $('.apply-btn').on('click', function() {
        $(this).attr('disabled', true);
        var prefix = $(this).data('prefix');
        var corporateId = $(this).data('corporate');
        var remainCount = $(this).data('remaincount');
        var enteredValue = $(this).closest('.institute-coupon-grid-content-input').find('input').val();
        var $btn = $(this);

        if (remainCount == 0) {
            $(".institute-coupon-grid-content-input").not($(this).parent().next()).hide();
            error('The Coupon is not avaialable.');
        }

        if (!enteredValue || isNaN(enteredValue) || enteredValue == 0) {
            error('Please enter a valid number.');
            return;
        }

        // Check if the entered value has at most 4 digits
        if (enteredValue.length > 4) {
            error('Please enter a number with at most 4 digits.');
            return;
        }

        if (remainCount < enteredValue) {

            error('Please enter the number below the remain coupon.');
            return;
        }
        // Make AJAX call

    });
</script>

<!-- /#page-content-wrapper -->
@endsection('content')