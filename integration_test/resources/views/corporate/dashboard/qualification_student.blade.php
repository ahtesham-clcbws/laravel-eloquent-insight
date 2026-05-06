@extends('student.layouts.master')

@section('content')

<!-- Form step start -->
@include('student.layouts.form_arrow_step')
<!-- Form step end -->
@if($student->is_final_submitted) <style>input, select{pointer-events: none;}</style>@endif
<?php

use App\Models\BoardAgencyStateModel;

$qualifications = BoardAgencyStateModel::all();

?>
<!-- InstanceBeginEditable name="Content Area" -->
<div class="container pagecontentbody">
    <div class="tab-content" >
        <div class="pagebody row" >
            <form method="post" action="{{ route('students.addQualifications') }}" enctype="multipart/form-data">
                @csrf
                <div class="container col-md-9 " style="border: 1px solid #c6cbd0;padding: 8px 25px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="qualification">Qualification:<span class="text-danger">*</span></label><br>
                            <select id="qualification" name="qualification" class="form-control form-select" required onchange="getScholarshipCategory(this.value)" value="{{$student->qualification}}">
                                <option value="">--Select qualification--</option>
                                @foreach($qualifications as $qualification)
                                <option value="{{$qualification->id}}" {{$student->qualification == $qualification->id ? 'selected' : ''}}>
                                    {{$qualification->name}}
                                </option>
                            @endforeach     
                            </select>
                            @error('qualification')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="scholarship_category">Scholarship Category:<span class="text-danger">*</span></label><br>
                            <select id="scholarship_category" name="scholarship_category" class="form-control form-select" required value="{{$student->scholarship_category}}">
                                <option value="">--Select Category--</option>
                                <option value="category1" {{$student->scholarship_category == 'category1' ? 'selected' : ''}}>Category 1</option>
                                <option value="category2" {{$student->scholarship_category == 'category2' ? 'selected' : ''}}>Category 2</option>
                                <option value="category3" {{$student->scholarship_category == 'category3' ? 'selected' : ''}}>Category 3</option>
                            </select>
                            @error('scholarship_category')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="scholarship_opted_for">Scholarship Opted For:<span class="text-danger">*</span></label><br>
                            <select id="scholarship_opted_for" name="scholarship_opted_for" class="form-control form-select" required value="{{$student->scholarship_opted_for}}">
                                <option value="">--Select Option--</option>
                                <option value="option1" {{$student->scholarship_opted_for == 'option1' ? 'selected' : ''}}>Option 1</option>
                                <option value="option2" {{$student->scholarship_opted_for == 'option2' ? 'selected' : ''}}>Option 2</option>
                                <option value="option3" {{$student->scholarship_opted_for == 'option3' ? 'selected' : ''}}>Option 3</option>
                            </select>
                            @error('scholarship_opted_for')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="test_center_a">Choice of Test Centre (A):<span class="text-danger">*</span></label><br>
                            <select id="test_center_a" name="test_center_a" class="form-control form-select" value="{{$student->test_center_a}}" required>
                                <option value="">--Select Center--</option>
                                @foreach($choiceCenterA as $center1)
                                <option value="{{$center1->Id}}" {{$student->test_center_a ?? $student->district_id == $center1->id ? 'selected' : ''}}>{{$center1->name}}</option>
                                @endforeach
                            </select>
                            @error('test_center_a')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="test_center_b">Choice of Test Centre (B):<span class="text-danger">*</span></label><br>
                            <select id="test_center_b" name="test_center_b" class="form-control form-select" required value="{{$student->test_center_a}}">
                                <option value="">--Select Center--</option>
                                @foreach($choiceCenterB as $center1)
                                <option value="{{$center1->id}}" {{$student->test_center_b == $center1->id ? 'selected' : ''}}>{{$center1->name}}</option>
                                @endforeach</select>
                            @error('test_center_b')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <hr />
                        </div>
                        <div class="col-md-3 d-grid">
                            <button type="submit" class="btn btn-theme submitform">Save and Next</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        if("{{$student->scholarship_category}}"){
            console.log({{$student->scholarship_category}})
            getScholarshipCategory("{{$student->scholarship_category}}",'Yes') 
        }
function getScholarshipCategory(id, type=null){
    $.get('/students/get_scholarship_category/' + id+'/'+type, function(response) {
        if(response.status){
console.log(response.data !=null)
        var data = response.data;
        if(response.data !=null){

            $('#scholarship_category').empty().append('<option value="">--Select Option--</option>');
            $.each(response.data, function(index, st) {
        var selected = ({{$student->scholarship_category ?? 'null'}} == st.id) ? 'selected' : '';

         $('#scholarship_category').append('<option value="' + st.id + '" ' + selected + '>' + st.name + '</option>');
        });
        }

         if(response.scholarOptedFor.length > 0){

        $('#scholarship_opted_for').empty().append('<option value="">--Select Scholarship Opted For--</option>');
         $.each(response.scholarOptedFor, function(index, optedfor) {
            var selected = ({{$student->scholarship_opted_for ?? 'null'}} == optedfor.id) ? 'selected' : '';
            $('#scholarship_opted_for').append('<option value="' + optedfor.id + '" ' + selected + '>' + optedfor.name + '</option>');
         });
         }
        }else{
            error(response.message)
        }

      });
 
}

    </script>
    <!-- InstanceEndEditable -->
    @endsection('content')