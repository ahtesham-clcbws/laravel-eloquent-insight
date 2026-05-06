@extends('administrator.layouts.master')
@section('content')
<style>
    .select2-selection__choice__display {
        color: black;
    }
</style>
<div class="container py-3">
    <div class="dashboard-container mb-5">
        {{-- Subject --}}
        <div class="row border-bottom pb-2">
            {{-- Subject Form --}}
            <div class="col-md-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Add / Update Subject
                    </div>
                    <form class="card-body" method="post" id="subject_form">
                        @error('subjectError')
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        @error('subjectSuccess')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        @csrf
                        <input type="number" name="id" class="d-none" id="subject_id" value="0">
                        <input name="form_name" id="subject_form_name" class="d-none" value="subject_form">
                        <div class="mb-3">
                            <label for="part_name" class="form-label">Classes</label>
                            <select class="form-select form-select-sm" {{ count($data['class_data']) > 0 ? '' : 'disabled' }} id="subject_class_id" name="class_id" required>
                                <option value=""></option>
                                @foreach ($data['class_data'] as $key => $class_data)
                                <option value="{{ $class_data['id'] }}">{{ $class_data['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subject_name" class="form-label"> Name / Title (No Special characters)</label>
                            <select class="form-select form-select-sm" multiple id="subject_name" name="name[]" required>
                                @foreach($data['subjects'] as $subjects)
                                <option value="{{ $subjects->id }}">{{ $subjects->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="btn-group btn-group-sm w-100" role="group">
                            <button type="submit" class="btn btn-success">Save</button>
                            <button type="button" class="btn btn-danger resetbtn" id="subject_reset" onclick="resetForm('subject')">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Subject Table --}}
            <div class="col-md-9 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Class</th>
                                    <th scope="col">Subject</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['subject_data_display'] as $key => $subject_data)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>@if(isset($subject_data->class['name'])) {{ $subject_data->class['name'] }} @endif</td>
                                    <td>
                                        @if(!empty($subject_data['subject_id']))
                                        @foreach(json_decode($subject_data['subject_id']) as $subject_id)
                                        <span class="commaSeperatedSpan">{{ \App\Models\Subject::find($subject_id)?->name }}</span>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="javascript:void(0);"><i class="bi bi-pencil-square text-success me-2" onclick="editForm({{ $subject_data['id'] }}, '{{ $subject_data['subject_id'] }}', 'subject','','',{{ $subject_data->classes_group_exams_id }})"></i></a>
                                        <a href="javascript:void(0);"><i class="bi bi-trash2-fill text-danger" onclick="deleteSubject({{ $subject_data['id'] }})"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        {{-- Part --}}
        <div class="row border-bottom pb-2 mt-2">
            {{-- Part Form --}}
            <div class="col-md-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        Add / Update Subject Part
                    </div>
                    <form class="card-body" method="post" id="part_form">
                        @error('partError')
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        @error('partSuccess')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        @csrf
                        <input type="number" name="id" class="d-none" id="part_id" value="0">
                        <input name="form_name" id="part_form_name" class="d-none" value="part_form">
                        <div class="mb-3">
                            <label for="part_name" class="form-label">Classes</label>
                            <select class="form-select form-select-sm" id="subject_part_class_id" name="class_id" onchange="partClassChange(this.value)" required>
                                <option value=""></option>
                                @foreach ($data['class_data'] as $key => $class_data)
                                <option value="{{ $class_data['id'] }}">{{ $class_data['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="part_name" class="form-label">Subject</label>
                            <select class="form-select form-select-sm" {{ count($data['subjects']) > 0 ? '' : 'disabled' }} id="part_subject_id" name="subject_id" required>
                                <option value=""></option>
                                @foreach ($data['subjects'] as $key => $subject)
                                <option value="{{ $subject['id'] }}">{{ $subject['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="part_name_id" class="form-label"> Part Name</label>
                            <select class="form-select form-select-sm" multiple id="part_name_id" name="name[]">
                                @foreach($data['gn_subject_parts'] as $gn_subject_parts)
                                <option value="{{ $gn_subject_parts->id }}">{{ $gn_subject_parts->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="btn-group btn-group-sm w-100" role="group">
                            <button type="submit" class="btn btn-success">Save</button>
                            <button type="button" class="btn btn-danger resetbtn" id="part_reset" onclick="resetForm('part')">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Part Table --}}
            <div class="col-md-9 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body" style="max-height: 356px; overflow-y: auto;">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Class</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Subject Part</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['subject_parts'] as $key => $subject_part)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>
                                        @if(!empty($subject_part['classes_group_exams_id']))
                                        @if(isset($subject_part->class['name'])) {{ $subject_part->class['name'] }} @endif
                                        @else
                                        {{ '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($subject_part->subject['name']))
                                        {{ $subject_part->subject['name'] }}
                                        @else
                                        {{ '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($subject_part['subject_part_id']))
                                        @foreach(json_decode($subject_part['subject_part_id']) as $subject_part_id)
                                        <span class="commaSeperatedSpan">{{ \App\Models\SubjectPart::find($subject_part_id)->name }}</span>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="javascript:void(0);"><i class="bi bi-pencil-square text-success me-2" onclick="editForm({{ $subject_part['id'] }}, '{{ $subject_part['subject_part_id'] }}', 'part', '{{ $subject_part['subject_id'] }}','',{{ $subject_part['classes_group_exams_id'] }})"></i></a>
                                        <a href="javascript:void(0);"><i class="bi bi-trash2-fill text-danger" onclick="deleteSubjectPart({{ $subject_part['subject_id'] }},{{ $subject_part['id'] }})"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Chapter Table --}}
        <div class="row border-bottom pb-2 mt-2">
            {{-- Lesson Form --}}
            <div class="col-md-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        Add / Update Chapter
                    </div>
                    <form class="card-body" method="post" id="lesson_form">
                        @error('lessonError')
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        @error('lessonSuccess')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        @csrf
                        <input type="number" name="id" class="d-none" id="lesson_id" value="0">
                        <input name="form_name" id="lesson_form_name" class="d-none" value="lesson_form">
                        <div class="mb-3">
                            <label for="part_name" class="form-label">Classes</label>
                            <select class="form-select form-select-sm" id="lession_class_id" onchange="lessionClassChange(this.value)" name="class_id" required>
                                <option value=""></option>
                                @foreach ($data['class_data'] as $key => $class_data)
                                <option value="{{ $class_data['id'] }}">{{ $class_data['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="part_name" class="form-label">Subject</label>
                            <select class="form-select form-select-sm" onchange="lessonSubjectChange(this.value)" {{ count($data['subjects']) > 0 ? '' : 'disabled' }} id="lesson_subject_id" name="subject_id" required>
                                <option value=""></option>
                                @foreach ($data['subjects'] as $key => $subject)
                                <option value="{{ $subject['id'] }}">{{ $subject['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="part_name" class="form-label">Subject Part</label>
                            <select class="form-select form-select-sm" id="lesson_subject_part_id" name="subject_part_id" disabled required>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="lesson_name_id" class="form-label"> Chapter Name</label>
                            <select class="form-select form-select-sm" multiple id="lesson_name_id" name="name[]" required>
                                @foreach($data['gn_subject_part_lessons'] as $gn_subject_part_lessons)
                                <option value="{{ $gn_subject_part_lessons->id }}">{{ $gn_subject_part_lessons->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- <div class="mb-3">
                                <label for="lesson_name" class="form-label">Chapter Name</label>
                                <input type="text" name="name" class="form-control form-control-sm" id="lesson_name" required>
                            </div> -->
                        <div class="btn-group btn-group-sm w-100" role="group">
                            <button type="submit" class="btn btn-success">Save</button>
                            <button type="button" class="btn btn-danger resetbtn" id="lesson_reset" onclick="resetForm('lesson')">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Lesson Table --}}
            <div class="col-md-9 col-sm-6 col-12">
                <div class="card">
                    <div class="card-body" style="max-height: 433px; overflow-y: auto;">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Class</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Subject Part</th>
                                    <th scope="col">Chapter</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['subject_part_lessons'] as $key => $subject_part_lesson)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>@if(isset($subject_part_lesson->class['name'])){{ $subject_part_lesson->class['name'] }} @endif</td>
                                    <td>{{ $subject_part_lesson->subject['name'] }}</td>
                                    <td>{{ $subject_part_lesson->subject_part['name'] }}</td>
                                    <td>
                                        @if(!empty($subject_part_lesson['chapter_id']))
                                        @foreach(json_decode($subject_part_lesson['chapter_id']) as $chapter_id)
                                        <span class="commaSeperatedSpan">{{ \App\Models\SubjectPartLesson::find($chapter_id)->name }}</span>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="javascript:void(0);"><i class="bi bi-pencil-square text-success me-2" onclick="editForm({{ $subject_part_lesson['id'] }}, '{{ $subject_part_lesson['chapter_id'] }}' , 'lesson', '{{ $subject_part_lesson['subject_id'] }}', '{{ $subject_part_lesson['subject_part_id'] }}',{{ $subject_part_lesson['classes_group_exams_id'] }})"></i></a>
                                        <a href="javascript:void(0);"><i class="bi bi-trash2-fill text-danger" onclick="deleteSubjectPartChapter({{ $subject_part_lesson['subject_id'] }},{{ $subject_part_lesson['subject_part_id'] }},{{ $subject_part_lesson['id'] }})"></i></a>
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
<script type="text/javascript" src="{{ asset('js/adminsubjects.js?v=6') }}"></script>

<!-- /#page-content-wrapper -->
@endsection('content')