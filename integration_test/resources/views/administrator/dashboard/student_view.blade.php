@extends('administrator.layouts.master')

@section('title')
    Student
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-md-9">

            </div>
            <div class="col-md-3">
                <a class="btn btn-info Exam float-end" href="{{ route('admin.home') }}" style="width: 6rem; height: 2.65rem;">
                    <svg class="icon" style="width: 2rem; height: 2rem;" viewBox="100 0 1024 1024" fill="#ffffff"
                        version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z"
                                fill=""></path>
                        </g>
                    </svg>
                    back
                </a>
            </div>

        </div>
        <div class="row">
            <main class="col-md-12 col-lg-12">
                <div class="custom-dashboard pb-2 pt-3">
                    <style>
                        @media print {
                            #studenthiddenTable {
                                display: table !important;
                            }

                            .d-none {
                                display: none;
                            }
                        }
                    </style>
                    <section class="content admin-1 mt-3">
                        <div class="row corporate-cards">
                            <div class="col-md-6 col-12">
                                <div class="card">
                                    <div class="d-flex justify-content-between align-items-center p-2"
                                        style="background-color:#19467a ; color: white;">
                                        <h5>Registration Details: </h5>
                                        <h5>Expires at 19-May-2024</h5>
                                        <a href="{{ route('admin.print.studentView', $student->id) }}"
                                            target="_blank"><button class="btn btn-success" type="button"
                                                style="display:flex; float:right;"><i class="fa fa-print"></i></button></a>
                                    </div>
                                    <br>
                                    <div class="card-body">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table-bordered table-hover table" id="studentTable">
                                                    <tbody>
                                                        <tr>
                                                            <td><b>Name</b></td>
                                                            <td class="information-txt" colspan="2">{{ $student->name }}
                                                            </td>
                                                            <td class="userImageCell" rowspan="3" colspan="2">
                                                                <img class="img-fluid"
                                                                    src="{{ $student->photograph ? '/storage/' . $student->photograph : '/student/images/th_5.png' }}"
                                                                    style="width: 100%;height: auto;">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Mobile</b></td>
                                                            <td class="information-txt" colspan="2">
                                                                {{ $student->mobile }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Email ID</b></td>
                                                            <td class="information-txt" colspan="2">{{ $student->email }}
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Address</b></td>
                                                            <td class="information-txt" colspan="3">
                                                                {{ $student->address }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="information-txt"><b>City</b></td>
                                                            <td class="information-txt">
                                                                {{ $student->district?->name }} / {{ $student->pincode }}
                                                            </td>
                                                            <td><b>State</b></td>
                                                            <td>{{ $student->state?->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td> <b>Qualification</b></td>
                                                            <td class="information-txt">
                                                                {{ $student->qualifications?->name ?? 'N/A' }} </td>
                                                            <td><b>Scholarship Category</b></td>
                                                            <td class="information-txt">
                                                                @if (!empty($student->scholarship_category))
                                                                    @php($scholarship_categ = DB::table('education_type')->where('id', $student->scholarship_category)->first())
                                                                    {{ $scholarship_categ->name }}
                                                                @else
                                                                    NA
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td> <b>Scholarship Opted For</b> </td>
                                                            <td class="information-txt">
                                                                {{ $student->scholarShipOptedFor?->name ?? 'N/A' }}</td>
                                                            <td>
                                                                <b>Centre Choice (A/B)</b>
                                                            </td>
                                                            <td class="information-txt">
                                                                <b>A:</b>
                                                                @php($center = DB::table('districts')->where('id', $student->test_center_a)->first())
                                                                {{ $center?->name }} @if ($student->choiceCenterB)
                                                                    @php($center2 = DB::table('districts')->where('id', $student->test_center_b)->first())
                                                                    <br /><b>B:</b> {{ $center2->name }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <b>Father Occupation</b> &nbsp;&nbsp;&nbsp;
                                                            </td>
                                                            </td>
                                                            </td>
                                                            <td>{{ $student->father_occupation }} </td>
                                                            <td><b>Mother Occupation: </b> </td>
                                                            <td>{{ $student->mother_occupation }}</td>
                                                        </tr>
                                                        <tr class="d-none">
                                                            <td colspan="2"><b>Action</b></td>
                                                            <td style="text-align: center;" colspan="2"> <button
                                                                    class="btn btn-link text-danger action-button"
                                                                    type="button"><b>Discontinue</b></button>
                                                                <!-- <button class="btn btn-link text-info action-button" type="button" onclick="showReply()">Reply</button> -->
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><b>Status</b></td>
                                                            <td class="bg-success text-center" colspan="2"> <span
                                                                    class="text-white">Active</span> </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table-bordered table-hover d-none table"
                                                    id="studenthiddenTable">
                                                    <tbody>
                                                        <tr>
                                                            <td class="userImageCell"> </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><b>Name</b></td>
                                                            <td class="information-txt">{{ $student->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><b>Mobile</b></td>
                                                            <td class="information-txt" colspan="2">
                                                                {{ $student->mobile }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><b>Email</b></td>
                                                            <td class="information-txt" colspan="2">
                                                                {{ $student->email }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><b>Education Type</b></td>
                                                            <td class="information-txt" colspan="2">-</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><b>Class</b></td>
                                                            <td class="information-txt" colspan="2">--</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><b>Institute Name</b></td>
                                                            <td class="information-txt" colspan="2"> - </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><b>Institute Code</b></td>
                                                            <td class="information-txt" colspan="2"> - </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><b>Subscription</b></td>
                                                            <td colspan="2"> Expires at: </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><b>Status</b></td>
                                                            <td class="bg-success" colspan="2"> <span
                                                                    class="text-white">Active</span> </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 d-none">
                                <div class="table-responsive">
                                    <table class="table-bordered border-primary corporate-table table">
                                        <tbody>
                                            <tr>
                                                <th>Actions</th>
                                                <td> <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#rejectBox" type="button">Reject</button> <button
                                                        class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#replyBox" type="button">Reply</button> </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="toast align-items-center position-absolute bottom-0 end-0 mb-3 border-0 text-white"
                        id="responseToast" data-delay="5000" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body" id="responseToastMessage"> </div>
                            <button class="btn-close btn-close-white m-auto me-2" data-bs-dismiss="toast" type="button"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        function printTable() {
            var tableContents = document.getElementById("studenthiddenTable").outerHTML;
            var printWindow = window.open("", "Print Window");
            printWindow.document.write(tableContents);
            printWindow.print();
            printWindow.close();
        }
    </script>
    <script>
        if ("{{ $student->scholarship_category }}") {
            console.log(<?= $student->scholarship_category ?>)
            getScholarshipCategory("{{ $student->scholarship_category }}", 'Yes')
        }

        function getScholarshipCategory(id, type = null) {
            $.get('/administrator/get_admin_scholarship_category/' + id + '/' + type, function(response) {
                if (response.status) {
                    console.log(response.data != null)
                    var data = response.data;
                    if (response.data != null) {

                        $('#scholarship_category').empty().append('<option value="">--Select Option--</option>');
                        $.each(response.data, function(index, st) {
                            var selected = (<?= $student->scholarship_category ?? 'null' ?> == st.id) ?
                                'selected' : '';

                            $('#scholarship_category').append('<option value="' + st.id + '" ' + selected +
                                '>' + st.name + '</option>');
                        });
                    }
                } else {
                    error(response.message)
                }

            });

        }

        if ("{{ $student->scholarship_opted_for }}" != "") {
            getScholarshipoptedfor("{{ $student->scholarship_category }}")
        }

        function getScholarshipoptedfor(id) {
            $qulificationid = $('#qualification').val();

            $.get('/administrator/get_admin_scholarship_opted_for/' + id + '/' + $qulificationid, function(response) {
                if (response.scholarOptedFor.length > 0) {

                    $('#scholarship_opted_for').empty().append(
                        '<option value="">--Select Scholarship Opted For--</option>');
                    $.each(response.scholarOptedFor, function(index, optedfor) {
                        var selected = (<?= $student->scholarship_opted_for ?? 'null' ?> == optedfor.id) ?
                            'selected' : '';
                        $('#scholarship_opted_for').append('<option value="' + optedfor.id + '" ' +
                            selected + '>' + optedfor.name + '</option>');
                    });
                } else {
                    $('#scholarship_opted_for').empty().append(
                        '<option value="">--Select Scholarship Opted For--</option>');

                    error(response.message)
                }

            });
        }
    </script>
    <!-- /#page-content-wrapper -->
@endsection('content')
