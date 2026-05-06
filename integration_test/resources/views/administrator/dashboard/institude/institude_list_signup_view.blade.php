@extends('administrator.layouts.master')

@section('content')

    <style>
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

    <div class="custom-dashboard pb-2 pt-3">
        <div class="w-100 dashboard-header mb-4">
            <h2 class="d-inline-block">
                <!--<i class="bi bi-house-fill"></i>-->
            </h2>
        </div>

        <input class="d-none" id="franchiseId" value="{{ $corporate->id }}">
        <section class="content admin-1">
            <div class="row corporate-cards">
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header" style="background-color:#19467a ; color: white;">
                            <div>
                                <h5>Enquiry Detail: </h5>
                            </div>

                            <div>
                                <h5>Created on: {{ $corporate->created_at }}</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table-bordered table-hover table">
                                        <tbody>
                                            <tr>
                                                <td colspan="2"><b>Name</b></td>
                                                <td class="information-txt">{{ $corporate->name }}</td>
                                                <td class="userImageCell" rowspan="2">
                                                    <img id="profile_img"
                                                        src="{{ asset('/storage/' . $corporate->attachment) }}"
                                                        style="width:80px;height:80px;border:1px solid #c2c2c2;  ">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>Mobile</b></td>
                                                <td class="information-txt">{{ $corporate->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>Email</b></td>
                                                <td class="information-txt" colspan="2">{{ $corporate->email }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>Address</b></td>
                                                <td class="information-txt" colspan="2">{{ $corporate->address }}
                                                    &nbsp;&nbsp;, <strong>City:
                                                        &nbsp;</strong>{{ $corporate->district?->name . ', ' . $corporate->state?->name }}
                                                    <!-- <strong>State: &nbsp;</strong>Uttar Pradesh -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <b>Inst. Name</b>
                                                </td>
                                                <td class="information-txt" colspan="2">{{ $corporate->institute_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>Institute Type</b></td>
                                                <td class="information-txt" colspan="2">
                                                    <span class="commaList">{{ $corporate->type_institution }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>Interested For</b></td>
                                                <td class="information-txt" colspan="2">
                                                    <span class="commaList">{{ $corporate->interested_for }}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2"><b>Action</b></td>
                                                <td colspan="2">
                                                    <button class="btn btn-link text-info action-button" type="button"
                                                        onclick="showReply()">Reply</button>

                                                    @if (!is_null($corporate->signup_at))
                                                        @if (!$corporate->signup_approved)
                                                            <button class="btn btn-link text-success action-button"
                                                                type="button" onclick="showSignupApprove()">SignUp
                                                                Approve</button>
                                                        @endif
                                                    @endif

                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>Status</b></td>
                                                <td class="bg-info" colspan="2">
                                                    @if (!$corporate->is_approved)
                                                        <span class="text-white">New</span>
                                                    @else
                                                        <span class="text-white">Approved</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>Established Year</b></td>
                                                <td colspan="2">
                                                    {{ $corporate->established_year }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>Branch Code</b></td>
                                                <td colspan="2">
                                                    {{ $corporate->institude_code }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>Subscription</b></td>
                                                <td colspan="2">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-nowrap" colspan="2"><b>Institute Images PDF</b></td>
                                                <td class="information-txt" colspan="2">

                                                    <a href="{{ url('/storage/' . $corporate->attachment_profile) }}"
                                                        target="_blank">
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
                                <button class="btn btn-success action-button-reply"
                                    onclick="submitReply('reply', this)">Submit</button>
                            </div>
                        </div>
                    </div>

                    <div class="card" id="signup-approve-show" style="display: none;">
                        <div class="card-header" style="background-color: #18c968;color: #fff;align-items: center;">
                            <h5>Sign Up approve</h5>
                        </div>
                        <div class="card-body">
                            <div class="modalLoader" id="signup-approve-loader" style="display: none;">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mid-content">
                                <textarea id="signup-approve-message" name="type" cols="30" rows="10">We are happy to inform you that your business request signup approved by our Authorisation Team.
                         
                        </textarea>
                            </div>
                            <div class="control-area">
                                <button class="btn btn-danger" onclick="closeBox()">Close</button>
                                <button class="btn btn-success action-button-signup-approve"
                                    onclick="submitReply('signup-approve', this)">Submit</button>
                            </div>
                        </div>
                    </div>

                    @if ($corporate->signup_approved == 0)
                        <form class="card d-none" id="reply-hidden">
                            <div class="card-header" style="background-color:#19467a; color: #fff;">
                                <h5>Set Franchise Type/Role/Subscription</h5>
                            </div>
                            <div class="card-body">

                                <div class="d-flex mb-2">

                                    <label class="box-heading text-end">
                                        Type Institution
                                    </label>

                                    <div class="box-input" id="franchiseTypeDiv">
                                        <span class="input-group-text" id="addon-wrapping">
                                            <i class="bi bi-briefcase-fill"></i>
                                        </span>
                                        <select class="form-control">
                                            <option value="">Select institution</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="d-flex mb-2">

                                    <label class="box-heading text-end">
                                        Select Role
                                    </label>

                                    <div class="box-input" id="franchiseRoleDiv">
                                        <span class="input-group-text" id="addon-wrapping"><i
                                                class="bi bi-gear-fill"></i></span>
                                        <div class="dropdown" data-control="checkbox-dropdown">
                                            <label class="dropdown-label" id="label2">Select</label>

                                            <div class="dropdown-list">
                                                <label class="dropdown-option" for="">
                                                    <input data-toggle="check-all" type="checkbox" value="Selection 0" />
                                                    <a class="dropdown-option" data-toggle="check-all" href="#"
                                                        style="margin-left: -12px;
                                                                                                                            color: #19467a;
                                                                                                                            margin-top: -4px;">
                                                        Select all
                                                    </a>
                                                </label>

                                                <label class="dropdown-option">
                                                    <input class="franchise_role" name="role[]" type="checkbox"
                                                        value="franchise_manager" />
                                                    Manager
                                                </label>

                                                <label class="dropdown-option">
                                                    <input class="franchise_role" name="role[]" type="checkbox"
                                                        value="franchise_creator" />
                                                    Creator
                                                </label>

                                                <label class="dropdown-option">
                                                    <input class="franchise_role" name="role[]" type="checkbox"
                                                        value="franchise_publisher" />
                                                    Publisher
                                                </label>

                                                <label class="dropdown-option">
                                                    <input class="franchise_role" name="role[]" type="checkbox"
                                                        value="franchise_verifier" />
                                                    Verifier
                                                </label>

                                                <label class="dropdown-option">
                                                    <input class="franchise_role" name="role[]" type="checkbox"
                                                        value="franchise_reviewer" />
                                                    Reviewer
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="d-flex mb-2">

                                    <label class="box-heading text-end">
                                        Duration
                                    </label>

                                    <div class="box-input">
                                        <div class="input-group">
                                            <label class="input-group-text" for="inputGroupSelect01"><i
                                                    class="bi bi-clock-fill"></i></label>
                                            <select class="form-select" id="inputGroupSelect01" name="days"
                                                data-style="btn-new">
                                                <option value="0">No subscription
                                                </option>
                                                <option value="3">3 days
                                                </option>
                                                <option value="7">7 days
                                                </option>
                                                <option value="15">15
                                                    days
                                                </option>
                                                <option value="30">30
                                                    days
                                                </option>
                                                <option value="60">60
                                                    days
                                                </option>
                                                <option value="90">3
                                                    months
                                                </option>
                                                <option value="120">4
                                                    months</option>
                                                <option value="150">5
                                                    months</option>
                                                <option value="180">6
                                                    months</option>
                                                <option value="270">9
                                                    months</option>
                                                <option value="365">1
                                                    year
                                                </option>
                                                <option value="730">2
                                                    year
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mb-2">

                                    <label class="box-heading text-end">
                                        Person name
                                    </label>

                                    <div class="box-input">
                                        <!-- <div class="box-icon"></div> -->
                                        <div class="input-group flex-nowrap">
                                            <span class="input-group-text" id="addon-wrapping"><i
                                                    class="bi bi-person-fill"></i></span>
                                            <input class="form-control" name="name" type="text"
                                                value="{{ $corporate->name }}" placeholder="Person name">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mb-2">

                                    <label class="box-heading text-end">
                                        Institute name
                                    </label>

                                    <div class="box-input">
                                        <!-- <div class="box-icon"></div> -->
                                        <div class="input-group flex-nowrap">
                                            <span class="input-group-text" id="addon-wrapping"><i
                                                    class="bi bi-mortarboard-fill"></i></span>
                                            <input class="form-control" name="institute_name" type="text"
                                                value="{{ $corporate->institute_name }}" placeholder="Institute name">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mb-2">

                                    <label class="box-heading text-end">
                                        Mobile number
                                    </label>

                                    <div class="box-input">
                                        <!-- <div class="box-icon"></div> -->
                                        <div class="input-group flex-nowrap">
                                            <span class="input-group-text" id="addon-wrapping"><i
                                                    class="bi bi-phone-fill"></i></span>
                                            <input class="form-control" name="mobile" type="number"
                                                value="{{ $corporate->phone }}" placeholder="Mobile number">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mb-2">

                                    <label class="box-heading text-end">
                                        E-mail
                                    </label>

                                    <div class="box-input">
                                        <!-- <div class="box-icon"></div> -->
                                        <div class="input-group flex-nowrap">
                                            <span class="input-group-text" id="addon-wrapping"><i
                                                    class="bi bi-envelope-fill"></i></span>
                                            <input class="form-control" name="email" type="email"
                                                value="{{ $corporate->email }}" placeholder="E-mail">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mb-2">

                                    <label class="box-heading text-end">
                                        Password
                                    </label>

                                    <div class="box-input">
                                        <!-- <div class="box-icon"></div> -->
                                        <div class="input-group flex-nowrap">
                                            <span class="input-group-text" id="addon-wrapping"><i
                                                    class="bi bi-key-fill"></i></span>
                                            <input class="form-control" name="password" type="password"
                                                placeholder="Password">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mb-2">

                                    <label class="box-heading text-end">
                                        Institute Code
                                    </label>

                                    <div class="box-input">
                                        <!-- <div class="box-icon"></div> -->
                                        <div class="input-group flex-nowrap">
                                            <span class="input-group-text" id="addon-wrapping"><i
                                                    class="bi bi-house-fill"></i></span>
                                            <input class="form-control" name="institude_code" type="text"
                                                value="{{ $corporate->institude_code }}" placeholder="Institute code">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mb-2">

                                    <label class="box-heading text-end">
                                        Required box
                                    </label>

                                    <div class="box-input">
                                        <!-- <div class="box-icon"></div> -->
                                        <div class="input-group flex-nowrap">
                                            <span class="input-group-text" id="addon-wrapping"><i
                                                    class="bi bi-compass-fill"></i></span>
                                            <input class="form-control" type="password" placeholder="Required box">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mb-2">

                                    <label class="box-heading text-end">
                                        Allowed content
                                    </label>

                                    <div class="box-input">
                                        <!-- <div class="box-icon"></div> -->
                                        <div class="input-group">
                                            <label class="input-group-text" for="inputGroupSelect02"><i
                                                    class="bi bi-intersect"></i></label>
                                            <select class="form-select" id="inputGroupSelect02" name="allowed_to_upload">
                                                <option value="0">No
                                                </option>
                                                <option value="1">Yes
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mb-2">

                                    <label class="box-heading text-end">
                                        Status
                                    </label>

                                    <div class="box-input">
                                        <!-- <div class="box-icon"></div> -->
                                        <div class="input-group">
                                            <label class="input-group-text" for="inputGroupSelect03"><i
                                                    class="bi bi-person-badge-fill"></i></label>
                                            <select class="form-select" id="inputGroupSelect03" name="status">
                                                <!-- <option selected>Select Option</option> -->
                                                <option value="inactive">
                                                    Inactive</option>
                                                <option value="active">
                                                    Active</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mb-2">

                                    <label class="box-heading text-end">
                                    </label>

                                    <div class="box-input">
                                        <button class="btn btn-success" type="submit">Save</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </section>
        <div class="toast align-items-center position-absolute bottom-0 end-0 mb-3 border-0 text-white" id="responseToast"
            data-delay="5000" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="responseToastMessage">
                </div>
                <button class="btn-close btn-close-white m-auto me-2" data-bs-dismiss="toast" type="button"
                    aria-label="Close"></button>
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

        function closeBox() {
            $('#signup-approve-show').hide();
            $('#reply-show').hide();
        }

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

        var replyBox = $('#reply-show')
        var signApprove = $('#signup-approve-show')

        function showReply() {
            replyBox.show();
            signApprove.hide();
        }


        function showSignupApprove() {
            replyBox.hide();
            signApprove.show();
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
            formData.append('corporate_id', "{{ $corporate->id }}")
            formData.append('message', message.val())
            formData.append('type', type)
            formData.append('name', "{{ $corporate->name }}")
            formData.append('id', "{{ $corporate->id }}")
            formData.append('phone', "{{ $corporate->phone }}")
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'))

            console.log(Array.from(formData));

            $.ajax({
                url: "{{ route('institute.institute_status') }}",
                type: 'post',
                data: formData,
                contentType: false,
                processData: false
            }).done(function(data) {
                loader.hide();
                actionbtn.attr('disabled', false);

                if (data.success) {
                    success(data.message)
                    if (type == 'signup-approve') {
                        actionbtn.hide()

                        setTimeout(function() {
                            window.location.href = "{{ route('institute.list.signup') }}";
                        }, 2000)
                    }
                    if (type == 'reply') {
                        actionbtn.hide();
                    }

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
        });
    </script>

    <!-- /#page-content-wrapper -->
@endsection('content')
