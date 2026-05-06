@extends('administrator.layouts.master')
@section('content')
<style>
    .select2-selection__choice__display {
        color: black;
    }

    .commaSeperatedSpan:not(:last-child)::after {
        content: ", ";
        font-weight: 700;
    }
</style>
</style>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.min.js"></script>

<div class="row py-5 pl-3 pr-3">
    <div class="container p-0">
        <div class="dashboard-container mb-5">
            @error('Error')
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @enderror

            <div class="row border-bottom mt-3 pb-3">
                {{-- Scholarship Category --}}
                <div class="col-md-3 col-sm-6 col-12">
                    <form class="card" method="post" id="educationForm" style=" box-shadow: 0px 0px 5px 1px #17a2b887 !important; ">
                        @csrf
                        @error('educationError')
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        @error('educationSuccess')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        <div class="card-header bg-info">Scholarship Category</div>
                        <input type="number" name="id" class="d-none" id="education_id" value="0">
                        <input name="form_name" id="educationFormName" class="d-none" value="education_form">
                        <div class="card-body" style="min-height: 199px;">
                            <div class="mb-4">
                                <label for="education_name" class="form-label"> Scholarship Category</label>
                                <input type="text" class="form-control form-control-sm" id="education_name" name="name" required placeholder="Enter Scholarship Category">
                            </div>
                            <div class="btn-group btn-group-sm w-100" role="group">
                                <button type="button" class="btn btn-danger resetbtn" id="educationReset" onclick="resetForm('education')">Reset</button>
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-9 col-sm-6 col-12">
                    <div class="card" style=" box-shadow: 0px 0px 5px 1px #17a2b887 !important; ">
                        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-sm datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Scholarship Category</th>
                                        <th scope="col">Is Featured</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['educations'] as $key => $education)
                                    <tr>
                                        <td scope="row">{{ $key + 1 }}</td>
                                        <td>{{ $education['name'] }}</td>
                                        <td>
                                            <div class="form-control2">
                                                <label class="switch" for="featured-{{ $education->id }}">
                                                    <input type="checkbox" id="featured-{{ $education->id }}" data-id="{{ $education->id }}" class="educations-status-toggle" {{ $education->is_featured ? 'checked' : '' }}>
                                                    <div class="slider round">
                                                        <span class="off">No</span>
                                                        <span class="on">Yes</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <a href="javascript:void(0);"><i class="bi bi-pencil-square text-success me-2" onclick="editForm({{ $education['id'] }}, '{{ $education['name'] }}', 'education')"></i></a>
                                            <a href="javascript:void(0);"><i class="bi bi-trash2-fill text-danger" onclick="deleteEducationType({{ $education['id'] }})"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row border-bottom mt-3 pb-3">
                {{-- Scholarship Category child 2 / Board - exam - state --}}
                <div class="col-md-3 col-sm-6 col-12">
                    <form class="card" method="post" id="class_group_examForm">
                        @csrf
                        @error('examError')
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        @error('examSuccess')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        <div class="card-header bg-dark">
                            Education Type
                        </div>
                        <input type="number" name="id" class="d-none" id="class_group_exam_id" value="0">
                        <input name="form_name" id="class_group_examFormName" class="d-none" value="exam_form">
                        <div class="card-body" style="min-height: 251px;">
                            <div class="mb-3">
                                <label for="exam_education_type_id" class="form-label">Scholarship Category</label>
                                <select class="form-select form-select-sm" id="exam_education_type_id" name="exam_education_type_id" {{ count($data['educations']) ? '' : 'disabled' }} required>
                                    <option value=""></option>
                                    @foreach ($data['educations'] as $key => $education)
                                    <option value="{{ $education['id'] }}">{{ $education['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="class_group_exam_name" class="form-label"> Education Type</label>
                                <select class="form-select form-select-sm" multiple id="class_group_exam_name" name="name[]" required>
                                    @foreach($data['class_data'] as $class)
                                    <option value="{{ $class->id }}"> {{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="btn-group btn-group-sm w-100" role="group">
                                <button type="button" class="btn btn-danger resetbtn" id="class_group_examReset" onclick="resetForm('class_group_exam')">Reset</button>
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-9 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-sm datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Scholarship Category</th>
                                        <th scope="col">Education Type</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['exam'] as $key => $exam)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>

                                        <td>@if(!empty($exam->education['name']))
                                            {{ $exam->education['name'] }}
                                            @else
                                            {{ '-' }}
                                            @endif
                                        </td>
                                        <td>
                                            @php $examNames = []; @endphp
                                            @foreach($exam->class_exam as $class_group_exam_name)
                                            <span class="commaSeperatedSpan">{{ $class_group_exam_name->name }}</span>
                                            @php $examNames[] = $class_group_exam_name->name; @endphp
                                            @endforeach
                                        </td>
                                        <td class="text-end">
                                            <a href="javascript:void(0);"><i class="bi bi-pencil-square text-success me-2" onclick="editForm( {{ $exam['id'] }}, '{{ implode(', ', $examNames) }}', 'class_group_exam', '{{ $exam['education_type_id'] }}')"></i></a>
                                            <a href="javascript:void(0);"><i class="bi bi-trash2-fill text-danger" onclick="deleteClassGroup({{ $exam['id'] }}, {{ $exam['education_type_id'] }})"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row border-bottom mt-3 pb-3">
                {{-- Scholarship Category child 2 / Board - exam - state --}}
                <div class="col-md-3 col-sm-6 col-12">
                    <form class="card" method="post" id="boardForm" style=" box-shadow: 0px 0px 5px 1px #ffc10799 !important; ">
                        @csrf
                        @error('boardError')
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        @error('boardSuccess')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        <div class="card-header bg-warning">
                            Qualification
                        </div>
                        <input type="number" name="id" class="d-none" id="board_id" value="0">
                        <input name="form_name" id="boardFormName" class="d-none" value="board_form">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="board_education_type_id" class="form-label">Scholarship Category</label>
                                <select class="form-select form-select-sm" id="board_education_type_id" name="education_type_id" {{ count($data['educations']) ? '' : 'disabled' }} onchange="educationTypeChange(this.value)" required>
                                    <option value=""></option>
                                    @foreach ($data['educations'] as $key => $education)
                                    <option value="{{ $education['id'] }}">{{ $education['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3" id="board_class_group">
                                <label for="board_class_group_exam" class="form-label">Education Type</label>
                                <select class="form-select form-select-sm" id="board_class_group_exam" name="classes_group_exams_id" disabled required>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="board_name" class="form-label">Qualification</label>
                                <select class="form-select form-select-sm" multiple id="board_name" name="name[]" required>
                                    @foreach($data['boards'] as $board)
                                    <option value="{{ $board->id }}">{{ $board->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="btn-group btn-group-sm w-100" role="group">
                                <button type="button" class="btn btn-danger resetbtn" id="boardReset" onclick="resetForm('board')">Reset</button>
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-9 col-sm-6 col-12">
                    <div class="card" style=" box-shadow: 0px 0px 5px 1px #ffc10799 !important; ">
                        <div class="card-body" style="max-height: 356px; overflow-y: auto;">
                            <table class="table table-sm datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Scholarship Category</th>
                                        <th scope="col">Education Type</th>
                                        <th scope="col">Qualification</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['gn_exam_agency_board'] as $key => $board)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>

                                        <td>
                                            @if(!empty($board->education['name']))
                                            {{ $board->education['name'] }}
                                            @else
                                            {{ '-' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($board->classesGroupExam['name']))
                                            {{ $board->classesGroupExam['name'] }}
                                            @else
                                            {{ '-' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($board['board_id']))
                                            @php $boardNames = []; @endphp
                                            @foreach(json_decode($board['board_id']) as $board1)
                                            @php 
                                                $b = \App\Models\BoardAgencyStateModel::find($board1);
                                                if($b) $boardNames[] = $b->name;
                                            @endphp
                                            <span class="commaSeperatedSpan">{{ $b ? $b->name : '-' }}</span>
                                            @endforeach
                                            @else
                                            {{ '-' }}
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="javascript:void(0);">
                                                <i class="bi bi-pencil-square text-success me-2" onclick="editForm({{ $board['id'] }}, '{{ $board['board_id'] }}', 'board', '{{ $board->education_type_id }}', '', '', '{{ $board->classes_group_exams_id }}')">
                                                </i>
                                            </a>
                                            <a href="javascript:void(0);">
                                                <i class="bi bi-trash2-fill text-danger" onclick="deleteExamAgencyBoard({{ $board['education_type_id'] }}, '{{ $board['classes_group_exams_id'] }}', '{{ $board['board_id'] }}', '{{ $board['id'] }}')">
                                                </i>
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
            <div class="row border-bottom mt-3 pb-3">
                {{-- Scholarship Category child 2 / Board - exam - state --}}
                <div class="col-md-3 col-sm-6 col-12">
                    <form class="card" method="post" id="otherExamForm" style=" box-shadow: 0px 0px 5px 1px #14a413 !important; ">
                        @csrf
                        @error('otherExamError')
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        @error('otherExamSuccess')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        <div class="card-header " style="background-color: #14a413 !important;">
                            Scholarship Opted For
                        </div>
                        <input type="number" name="id" class="d-none" id="otherExam_id" value="0">
                        <input name="form_name" id="otherExamFormName" class="d-none" value="otherExam_form">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="other_exam_education_type_id" class="form-label">Scholarship Category</label>
                                <select class="form-select form-select-sm" id="other_exam_education_type_id" name="education_type_id" onchange="other_exam_education_type_change(this.value)" {{ count($data['educations']) ? '' : 'disabled' }} required>
                                    <option value=""></option>
                                    @foreach ($data['educations'] as $key => $education)
                                    <option value="{{ $education['id'] }}">{{ $education['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="other_exam_class_group_exam_id" class="form-label">Education Type</label>
                                <select class="form-select form-select-sm" id="other_exam_class_group_exam_id" name="classes_group_exams_id" onchange="other_exam_classes_group_exams_change(this.value)" disabled required>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="other_exam_agency_board_university_id" class="form-label">Qualification</label>
                                <select class="form-select form-select-sm" id="other_exam_agency_board_university_id" name="agency_board_university_id" disabled required>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="other_exam_name" class="form-label">Scholarship Opted For</label>
                                <select class="form-select form-select-sm" multiple id="other_exam_name" name="name[]" required>
                                    @foreach($data['gn_other_exam_classes'] as $gn_other_exam_classes)
                                    <option value="{{ $gn_other_exam_classes->id }}">{{ $gn_other_exam_classes->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="btn-group btn-group-sm w-100" role="group">
                                <button type="button" class="btn btn-danger resetbtn" id="otherExamReset" onclick="resetForm('otherExam')">Reset</button>
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-9 col-sm-6 col-12">
                    <div class="card" style=" box-shadow: 0px 0px 5px 1px #14a413 !important; ">
                        <div class="card-body" style="max-height: 435px; overflow-y: auto;">
                            <table class="table table-sm datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Scholarship Category</th>
                                        <th scope="col">Education Type</th>
                                        <th scope="col">Qualification</th>
                                        <th scope="col">Scholarship Opted For</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['other_exam_classes'] as $key => $other_exam_class)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>

                                            @if(!empty($other_exam_class->education['name']))
                                            {{ $other_exam_class->education['name'] }}
                                            @else
                                            {{ '-' }}
                                            @endif
                                        </td>
                                        <td>

                                            @if(!empty($other_exam_class->classesGroupExam['name']))
                                            {{ $other_exam_class->classesGroupExam['name'] }}
                                            @else
                                            {{ '-' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($other_exam_class->boardAgencyState['name']))
                                            {{ $other_exam_class->boardAgencyState['name'] }}
                                            @else
                                            {{ '-' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($other_exam_class['other_exam_id']))
                                            @php $optedNames = []; @endphp
                                            @foreach(json_decode($other_exam_class['other_exam_id']) as $opted_id)
                                                @php 
                                                    $opt = \App\Models\Gn_OtherExamClassDetailModel::find($opted_id);
                                                    if($opt) $optedNames[] = $opt->name;
                                                @endphp
                                                <span class="commaSeperatedSpan">{{ $opt ? $opt->name : '-' }}</span>
                                            @endforeach
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="javascript:void(0);"><i class="bi bi-pencil-square text-success me-2" onclick="editForm({{ $other_exam_class['id'] }}, '{{ $other_exam_class['other_exam_id'] }}', 'otherExam' , '{{ $other_exam_class['education_type_id'] }}','{{ $other_exam_class['agency_board_university_id'] }}','','{{ $other_exam_class['classes_group_exams_id'] }}')"></i></a>
                                            <a href="javascript:void(0);"><i class="bi bi-trash2-fill text-danger" onclick="deleteOtherExamClass({{ $other_exam_class['education_type_id'] }},{{ $other_exam_class['classes_group_exams_id'] }},{{ $other_exam_class['agency_board_university_id'] }})"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row border-bottom mt-3 pb-3">
                {{-- Start Result exam subject mapping --}}
                <div class="col-md-3 col-sm-6 col-12">
                    <form class="card" method="post" id="resultSubjectMapForm" style=" box-shadow: 0px 0px 5px 1px #1c26c9 !important; ">
                        @csrf
                        @error('resultSubjectMapFormError')
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        @error('resultSubjectMapFormSuccess')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        <div class="card-header " style="color:#fff;background-color: #1c26c9 !important;">
                            Scholarship Opted For
                        </div>
                        <input type="number" name="id" class="d-none" id="resultSubjectMapForm_id" value="0">
                        <input name="form_name" id="resultSubjectFormName" class="d-none" value="resultSubjectMapForm">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="other_exam_education_type_id" class="form-label">Scholarship Category</label>
                                <select class="form-select form-select-sm" id="other_exam_education_type_sub_id" name="education_type_id" onchange="other_exam_education_type_change(this.value,'resultMapping')" {{ count($data['educations']) ? '' : 'disabled' }} required>
                                    <option value=""></option>
                                    @foreach ($data['educations'] as $key => $education)
                                    <option value="{{ $education['id'] }}">{{ $education['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="classes_group_exams_id" class="form-label">Education Type</label>
                                <select class="form-select form-select-sm" id="other_exam_class_group_exam_sub_id" name="classes_group_exams_id" onchange="other_exam_classes_group_exams_sub_change(this.value)" disabled required>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="agency_board_university_id" class="form-label">Qualification</label>
                                <select class="form-select form-select-sm" id="other_exam_agency_board_university_sub_id" onchange="other_exam_classes_scholarship_opt_sub_change(this.value)" name="agency_board_university_id" disabled required>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="other_exam_name_sub_id" class="form-label">Scholarship Opted For</label>
                                <select class="form-select form-select-sm" multiple id="other_exam_name_sub_id" name="name[]" disabled required>
                                    <option value=""></option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="result_subject_mapping_id" class="form-label">Subject`s</label>
                                <select class="form-select form-select-sm" multiple id="result_subject_mapping_id" name="subject_id[]" required>
                                    <option value=""></option>
                                    @foreach($data['subjects'] as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="btn-group btn-group-sm w-100" role="group">
                                <button type="button" class="btn btn-danger resetbtn" id="resultSubjectMapFormReset" onclick="resetSubjectMappingForm()">Reset</button>
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-9 col-sm-6 col-12">
                    <div class="card" style=" box-shadow: 0px 0px 5px 1px #1c26c9 !important; ">
                        <div class="card-body" style="max-height: 517px; overflow-y: auto;">
                            <table class="table table-sm datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr.N.</th>
                                        <th scope="col">Scholarship Category</th>
                                        <th scope="col">Education Type</th>
                                        <th scope="col">Qualification</th>
                                        <th scope="col">Scholarship Opted For</th>
                                        <th scope="col" style="text-align: center;">Subjects</th>
                                        <th scope="col" style="text-align: center;">
                                            Subject Max marks
                                        </th>
                                        <th scope="col" style="text-align: center;">Result Excel Sample</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['resultSubjectMappings'] as $key => $resultSubjectMapping)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>

                                            @if(!empty($resultSubjectMapping->education['name']))
                                            {{ $resultSubjectMapping->education['name'] }}
                                            @else
                                            {{ '-' }}
                                            @endif
                                        </td>
                                        <td>

                                            @if(!empty($resultSubjectMapping->classesGroupExam['name']))
                                            {{ $resultSubjectMapping->classesGroupExam['name'] }}
                                            @else
                                            {{ '-' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($resultSubjectMapping->boardAgencyState['name']))
                                            {{ $resultSubjectMapping->boardAgencyState['name'] }}
                                            @else
                                            {{ '-' }}
                                            @endif
                                        </td>

                                        <td>
                                            <span class="commaSeperatedSpan">{{ ($resultSubjectMapping->scholarshipOptedFor('name')) }}</span>
                                        </td>

                                        <td>
                                            <span class="commaSeperatedSpan">{{ ($resultSubjectMapping->subjects('name')) }}</span>

                                        </td>
                                        <td>
                                            <div class="col-md-6 col text-end">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal{{$key+1}}">
                                                    Fill Subjects Details
                                                </button>
                                            </div>
                                        </td>
                                        <td><a class="btn btn-success" href="{{route('admin.exportMarkFillExcel',encodeId($resultSubjectMapping->id))}}"> Generate Student MarkFill Template Excel &nbsp;<i class="bi bi-download text-dark"></i></a>
                                        </td>

                                        <td class="text-end">
                                            <a href="javascript:void(0);">
                                                <i class="bi bi-pencil-square text-success me-2" onclick="editForm({{ $resultSubjectMapping->id }}, '{{ $resultSubjectMapping->name }}', 'resultSubjectMapForm', '{{ $resultSubjectMapping->education_type_id }}', '{{ $resultSubjectMapping->agency_board_university_id }}', '{{ $resultSubjectMapping->subject_id }}', '{{ $resultSubjectMapping->classes_group_exams_id }}')"></i>
                                            </a>
                                            <a href="javascript:void(0);"><i class="bi bi-trash2-fill text-danger" onclick="deleteresultSubjectMapFormClass({{ $resultSubjectMapping->id }})"></i></a>
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
<!-- Student paper import Subject details start modal -->
@foreach ($data['resultSubjectMappings'] as $key => $resultSubjectMapping)
<div class="modal fade" id="importModal{{$key+1}}" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importModalLabel">Subject Max Marks Insert</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="card-body" style="max-height: 517px; overflow-y: auto;">
            <table class="table table-sm datatable">
                <thead>
                    <tr>
                        <th scope="col">Sr.N.</th>
                        <th scope="col">Subject Name</th>
                        <th scope="col">Max Marks</th>
                        <th scope="col">Total Qs</th>
                        <th scope="col">Wrong Ded.</th>
                        <th scope="col">Skipped Ded.</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resultSubjectMapping->subjectPaperDetails as $index => $subjectPaperDetail)
                    <tr data-subject-id="{{ $subjectPaperDetail->subject_id }}" data-subject-mapping-id="{{ $subjectPaperDetail->subject_mapping_id }}">
                            @csrf
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $subjectPaperDetail->subject_name }}</td>
                            <td>
                                <input style="height: 42px;width:70px;font-size: 14px;text-align: center;" type="number" step="0.01" class="num_digit max-marks-input" name="max_marks" value="{{ $subjectPaperDetail->max_marks }}" placeholder="Value">
                            </td>
                            <td>
                                <input style="height: 42px;width:70px;font-size: 14px;text-align: center;" type="number" class="num_digit total-questions-input" name="total_questions" value="{{ $subjectPaperDetail->total_questions }}" placeholder="Value">
                            </td>
                            <td>
                                <input style="height: 42px;width:70px;font-size: 14px;text-align: center;" type="number" step="0.01" class="num_digit negative-marks-wrong-input" name="negative_marks_wrong" value="{{ $subjectPaperDetail->negative_marks_wrong }}" placeholder="Value">
                            </td>
                            <td>
                                <input style="height: 42px;width:70px;font-size: 14px;text-align: center;" type="number" step="0.01" class="num_digit negative-marks-skipped-input" name="negative_marks_skipped" value="{{ $subjectPaperDetail->negative_marks_skipped }}" placeholder="Value">
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-primary save-btn-mapp">Save</button>
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
@endforeach
<script type="text/javascript" src="{{ asset('js/admineducationtypes.js') }}"></script>

<script>
    $('.educations-status-toggle').on('change', function() {
        var courseId = $(this).data('id');
        var isFeatured = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: "{{ route('toggle.featured') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: courseId,
                type: 'educationType',
                is_featured: isFeatured
            },
        
            success: function(response) {
                if (response.status) {
                    success(response.message);
                } else {
                    error(response.message);
                }
            },
            error: function(xhr) {
                error(xhr.responseText);
            }
        });
    });
</script>
<script>
   $(document).ready(function() {
    inititateSelect2();
    $('.save-btn-mapp').on('click', function(e) {
        e.preventDefault();

        var $btn = $(this);

        var form = $(this).closest('tr');

        var subjectId = form.data('subject-id');
        var subjectMappingId = form.data('subject-mapping-id');
        var maxMarks = form.find('.max-marks-input').val();
        var totalQuestions = form.find('.total-questions-input').val();
        var negativeMarksWrong = form.find('.negative-marks-wrong-input').val();
        var negativeMarksSkipped = form.find('.negative-marks-skipped-input').val();

        var formData = new FormData();
        formData.append('subject_id', subjectId);
        formData.append('subjectMapping_id', subjectMappingId);
        formData.append('max_marks', maxMarks);
        formData.append('total_questions', totalQuestions);
        formData.append('negative_marks_wrong', negativeMarksWrong);
        formData.append('negative_marks_skipped', negativeMarksSkipped);


        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '{{ route("admin.subjectPaperDetailsAdd") }}',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            contentType: false,
            processData: false,
            success: function(response) {
              if(response.status){
                success(response.message)

                $btn.text('Update')
              }else{
                error(response.message)
              }
            },
            error: function(xhr, status, error) {
                error(xhr.responseText)
            }
        });
    });
});
</script>

<!-- /#page-content-wrapper -->
@endsection('content')