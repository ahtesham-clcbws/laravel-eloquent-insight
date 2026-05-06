<div>
    <div class="position-absolute d-none"
        style="height:-webkit-fill-available;width:-webkit-fill-available;z-index:99999;background:#00000070"
        wire:loading.class.remove="d-none">
        <div class="d-flex justify-content-center align-items-center w-100 h-100">
            <div class="spinner-grow text-primary" role="status" style="width: 4rem; height: 4rem;">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div class="row px-3">
        <style>
            .select2-container--default .select2-selection--single {
                padding: 5px .5rem;
            }

            .select2-container .select2-selection--single {
                height: 32px;
            }

            .select2-selection__choice {
                color: black !important;
            }
        </style>
        <div class="col-lg-12">
            <div class="panel panel-default m-t-15">
                <div class="row justify-content-space-between m-2 py-2">
                    <div class="col-md-6 col">
                        <h2>Student Roll No</h2>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">

                        <div class="row row-cols-4">
                            <div>
                                <div class="mb-3">
                                    <select class="form-control form-select" id="district-ids"
                                        wire:model.live="district_id" required>
                                        <option value="">All Districts</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ $city->id == $district_id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('district_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 rounded border px-2 py-1">
                                    <label class="form-label d-flex justify-content-between align-self-end">Gender:
                                        @if (!empty($genders))
                                            <small class="text-primary" style="cursor:pointer;"
                                                wire:click="clearFilter('gender')">clear</small>
                                        @endif
                                    </label>
                                    @php
                                        $allGenders = ['Male', 'Female', 'Transgender'];
                                    @endphp
                                    @foreach ($allGenders as $gender)
                                        <div class="form-check ml-2">
                                            <input class="form-check-input" id="{{ 'gender_' . $gender }}"
                                                type="checkbox" value="{{ $gender }}" wire:model.live="genders">
                                            <label class="form-check-label" for="{{ 'gender_' . $gender }}">
                                                {{ $gender }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <div class="mb-3 rounded border px-2 py-1">
                                    <label class="form-label d-flex justify-content-between align-self-end">Scholarship
                                        Types: @if (!empty($scholarhips))
                                            <small class="text-primary" style="cursor:pointer;"
                                                wire:click="clearFilter('scholarhip')">clear</small>
                                        @endif
                                    </label>
                                    <div style="max-height: 128px;">
                                        @foreach (\App\Models\EducationType::get() as $entity)
                                            <div class="form-check ml-2">
                                                <input class="form-check-input" id="{{ 'scholarhip_' . $entity->id }}"
                                                    type="checkbox" value="{{ $entity->id }}"
                                                    wire:model.live="scholarhips">
                                                <label class="form-check-label"
                                                    for="{{ 'scholarhip_' . $entity->id }}">
                                                    {{ $entity->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="mb-3 rounded border px-2 py-1">
                                    <label class="form-label d-flex justify-content-between align-self-end">Class/Exam:
                                        @if (!empty($classes))
                                            <small class="text-primary" style="cursor:pointer;"
                                                wire:click="clearFilter('class')">clear</small>
                                        @endif
                                    </label>
                                    <div style="max-height: 128px; overflow-x: auto">
                                        @php
                                            $classGroupId = \App\Models\Gn_EducationClassExamAgencyBoardUniversity::whereIn(
                                                'education_type_id',
                                                $scholarhips,
                                            )
                                                ->get()
                                                ->pluck('board_agency_exam_id');
                                            $classes = \App\Models\BoardAgencyStateModel::whereIn('id', $classGroupId)
                                                ->select('id', 'name')
                                                ->get();
                                        @endphp
                                        @if (!empty($scholarhips))
                                            @if (count($classes))
                                                @foreach ($classes as $entity)
                                                    <div class="form-check ml-2">
                                                        <input class="form-check-input"
                                                            id="{{ 'class_' . $entity->id }}" type="checkbox"
                                                            value="{{ $entity->id }}" wire:model.live="classes">
                                                        <label class="form-check-label"
                                                            for="{{ 'class_' . $entity->id }}">
                                                            {{ $entity->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="d-flex justify-content-center align-items-center h-full p-5"
                                                    style="height: 128px; color: gray;">No results found</div>
                                            @endif
                                        @else
                                            <div class="d-flex justify-content-center align-items-center h-full p-5"
                                                style="height: 128px; color: gray;">Please select scholarship type to
                                                get
                                                the class/exam list</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex gap-4">
                                    <div class="form-check ml-2">
                                        <input class="form-check-input" id="show_all_roll_number" type="radio"
                                            value="A" wire:model.live="roll_number_filter">
                                        <label class="form-check-label" for="show_all_roll_number">
                                            All
                                        </label>
                                    </div>
                                    <div class="form-check ml-2">
                                        <input class="form-check-input" id="show_with_roll_number" type="radio"
                                            value="B" wire:model.live="roll_number_filter">
                                        <label class="form-check-label" for="show_with_roll_number">
                                            With Roll-No
                                        </label>
                                    </div>
                                    <div class="form-check ml-2">
                                        <input class="form-check-input" id="show_without_roll_number" type="radio"
                                            value="C" wire:model.live="roll_number_filter">
                                        <label class="form-check-label" for="show_without_roll_number">
                                            Without Roll-No
                                        </label>
                                    </div>
                                </div>

                                <button class="btn btn-warning" wire:click="clearFilter">Reset All Filters</button>

                                @php
                                    $confirmMessage = 'Are you sure you want to reset all roll numbers?';
                                    if ($district_id || !empty($genders) || !empty($scholarhips) || !empty($classes)):
                                        $confirmMessage = 'Are you sure you want to reset filtered roll numbers?';
                                    endif;
                                @endphp
                                <button class="btn btn-danger" wire:confirm="{{ $confirmMessage }}"
                                    wire:click="resetRollNumbers">Reset Roll Numbers</button>

                                @php
                                    $generateConfirmMessage =
                                        'Are you sure you want to create roll numbers by current selection?';
                                    if (isFirstTimeRollNumberGeneration()):
                                        $generateConfirmMessage =
                                            'This action will lock your scholarship forms limits, as you will not able to change the limits in this whole term.\n\nAre you sure you want to create roll numbers by current selection?\n\nFor first time roll number generation starts, it will take some time, please be patient.';
                                    endif;
                                @endphp
                                <button class="btn btn-info" wire:confirm="{{ $generateConfirmMessage }}"
                                    wire:click="generateRollNumbers">Generate Roll
                                    Numbers</button>
                            </div>

                        </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-bordered table">
                                <thead>
                                    <tr>
                                        <th scope="col">##</th>
                                        <th scope="col">Student Name</th>
                                        <th scope="col">Email/Mobile</th>
                                        <th scope="col">District<br />Centre</th>
                                        <th scope="col">Appl No</th>
                                        <th scope="col">Roll No</th>
                                        <th scope="col">Payment & Voucher</th>
                                        <th scope="col">Qualification</th>
                                        <th scope="col">Scholarship Category</th>
                                        <th scope="col">Scholarship Opted For</th>
                                        <th scope="col">Dated</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                        <tr>
                                            <th scope="text-left">{{ $loop->index + 1 }}</th>
                                            <td class="text-nowrap">
                                                {{ $student->name }}<br />
                                                <span>{{ $student->gender }}</span><br />
                                                <span>{{ $student->dob }}</span>
                                            </td>
                                            <td>{{ $student->email }}<br />
                                                {{ $student->mobile }}<br />
                                                {{ $student->login_password }}
                                            </td>
                                            <td>{{ $student->district?->name }}</td>
                                            <td>{{ $student->latestStudentCode?->application_code ? $student->latestStudentCode?->application_code : 'N/A' }}
                                            </td>
                                            <td>{{ !empty($student->latestStudentCode?->roll_no) ? $student->latestStudentCode?->roll_no : 'N/A' }}
                                            </td>
                                            <td class="text-nowrap">
                                                ₹&nbsp;{{ $student->studentPayment && count($student->studentPayment) && !empty($student->studentPayment[0]) && $student->studentPayment[0]->payment_amount ? $student->studentPayment[0]->payment_amount : '0' }}
                                                <br />
                                                {{ $student->latestStudentCode?->coupan_code ? $student->latestStudentCode?->coupan_code : '' }}
                                                {!! $student->latestStudentCode?->coupan_code
                                                    ? '<br />' .
                                                        ($student->latestStudentCode?->corporate_name
                                                            ? $student->latestStudentCode?->corporate_name
                                                            : 'SQS Foundation, Kanpur')
                                                    : '' !!}
                                            </td>
                                            <td>{{ $student->qualifications?->name }}</td>
                                            <td>{{ $student->scholarShipCategory?->name ?? 'N/A' }}</td>
                                            <td>{{ $student->scholarShipOptedFor?->name ?? 'N/A' }}</td>
                                            <td>{{ date('d-M-Y', strtotime($student->created_at)) }}</td>
                                            <td style="text-align:center">
                                                <a class="btn btn-primary"
                                                    href="{{ route('admin.student', $student->id) }}"
                                                    style="text-decoration: none;"></i> View</a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <select class="form-control form-select" style="max-width:150px;" wire:model.live="perPage">
                            <option value="5">5 per page</option>
                            <option value="10">10 per page</option>
                            <option value="20">20 per page</option>
                            <option value="30">30 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                        <div>
                            {{ $students->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
