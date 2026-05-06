@extends('corporate.layouts.master')
@section('content')
    <div class="pagecontentbody mt-5 pt-5">
        <div class="px-4">
            <div class="pagebody removebg-color">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title">Student List</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="btn-group btn-group-sm me-3">
                                            <button type="button" class="btn btn-outline-success updateAdmitCardStatusAll">Release All Selected</button>
                                            <button type="button" class="btn btn-outline-danger StopAdmitCardStatusAll">Stop All Selected</button>
                                        </div>
                                        <label class="me-2 mb-0">Show:</label>
                                        <select class="form-select form-select-sm w-auto" id="studentTypeFilter">
                                            <option value="" {{ request('type') != 'new' ? 'selected' : '' }}>All Students</option>
                                            <option value="new" {{ request('type') == 'new' ? 'selected' : '' }}>New Students</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table-bordred table-hover table bg-white">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll" class="form-check-input"></th>
                                                <th>Name</th>
                                                <th>Admsn. #</th>
                                                <th>Paid Amount</th>
                                                <th>Discount Amount</th>
                                                <th>Voucher name</th>
                                                <th>Voucher Code</th>
                                                <th>Scholarship category</th>
                                                <th>Scholarship opted for</th>
                                                <th>DOB</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($students as $student)
                                                <tr>
                                                    <?php
                                                        $studCode = $student->studentCode->first();
                                                        if ($studCode && !$studCode->is_coupan_code_applied) {
                                                            $studCode = null;
                                                        }
                                                    ?>
                                                    <td>
                                                        @if($studCode && !$studCode->issued_admitcard)
                                                            <input type="checkbox" class="rowCheckbox form-check-input" data-studcode_id="{{ $studCode->id }}">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $student->name }}
                                                        @if ($student->isNew)
                                                            <span class="badge bg-success">New</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $studCode->application_code }}</td>
                                                    <td>{{ 'Rs.' . $studCode?->fee_amount }}</td>
                                                    <td>{{ 'Rs.' . $studCode?->coupan_value }}</td>
                                                    <td>{{ $studCode?->voucher?->name }}</td>
                                                    <td>{{ $studCode?->voucher?->couponcode }}</td>
                                                    <td> {{ $student->scholarShipCategory?->name ?? 'N/A' }}</td>
                                                    <td> {{ $student->scholarShipOptedFor?->name ?? 'N/A' }}</td>
                                                    <td>{{ $student->dob ?? '-' }}</td>
                                                    <td>
                                                        @if ($studCode?->issued_admitcard)
                                                            <span class="badge bg-primary">Admit Card Issued</span>
                                                        @else
                                                            @if ($studCode?->corporate_stop_admitcard)
                                                                <a class="btn btn-success btn-sm changeStatus"
                                                                    data-studcode_id="{{ $studCode->id }}" data-status="1"
                                                                    href="#">Activate AdmitCard</a>
                                                            @else
                                                                <a class="btn btn-danger btn-sm changeStatus"
                                                                    data-studcode_id="{{ $studCode->id }}" data-status="0"
                                                                    href="#">Stop AdmitCard</a>
                                                            @endif
                                                        @endif
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
    <script>
        new DataTable('table', {
            responsive: true,
            "columnDefs": [{
                "orderable": false,
                "targets": 0
            }]
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#selectAll').click(function() {
                $('.rowCheckbox').prop('checked', this.checked);
            });

            $('.rowCheckbox').change(function() {
                if ($('.rowCheckbox:checked').length == $('.rowCheckbox').length) {
                    $('#selectAll').prop('checked', true);
                } else {
                    $('#selectAll').prop('checked', false);
                }
                updateAdmitCardStatus([$(this).data('studcode_id')], $(this).is(':checked') ? 1 : 0);
            });

            $('.changeStatus').click(function(e) {
                e.preventDefault();
                var studcodeId = $(this).data('studcode_id');
                var status = $(this).data('status');
                updateAdmitCardStatus([studcodeId], status);
            });

            $('.updateAdmitCardStatusAll').click(function(e) {
                e.preventDefault();
                var studcodeIds = [];
                $('.rowCheckbox:checked').each(function() {
                    studcodeIds.push($(this).data('studcode_id'));
                });
                if (studcodeIds.length === 0) {
                    error('Please select at least one student.');
                    return;
                }
                updateAdmitCardStatus(studcodeIds, 1);
            });

            $('.StopAdmitCardStatusAll').click(function(e) {
                e.preventDefault();
                var studcodeIds = [];
                $('.rowCheckbox:checked').each(function() {
                    studcodeIds.push($(this).data('studcode_id'));
                });
                if (studcodeIds.length === 0) {
                    error('Please select at least one student.');
                    return;
                }
                updateAdmitCardStatus(studcodeIds, 0);
            });

            $('#studentTypeFilter').change(function() {
                var type = $(this).val();
                var url = new URL(window.location.href);
                if (type) {
                    url.searchParams.set('type', type);
                } else {
                    url.searchParams.delete('type');
                }
                window.location.href = url.toString();
            });

            function updateAdmitCardStatus(studcodeIds, status) {
                $.ajax({
                    url: '{{ route('corporate.update.admitcard.status') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        studcode_ids: studcodeIds,
                        status: status
                    },
                    success: function(response) {
                        if (response.status) {
                            success(response.message);
                            location.reload();
                        }
                    }
                });
            }
        });
    </script>
@endsection('content')
