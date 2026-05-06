<div class="container-fluid pagecontentbody pt-4">
    <div class="tab-content">
        <div class="pagebody px-4">
            <form wire:submit="saveAndReview">
                @csrf
                <div class="form-group">
                    <div class="col-md-7 col">
                        <label for="is_gov_exam_participated">Did you participate in any Govt/ Competitive Exam(s)? <span class="text-danger">*</span>: </label>

                        <input type="radio" wire:model.live="is_gov_exam_participated" id="is_gov_exam_participated_yes" value="yes" style="width: 15px; height: 15px">
                        <label for="is_gov_exam_participated_yes">Yes</label>

                        <input type="radio" wire:model.live="is_gov_exam_participated" id="is_gov_exam_participated_no" value="no" style="width: 15px; height: 15px">
                        <label for="is_gov_exam_participated_no">No</label>
                        @error('is_gov_exam_participated')
                        <small class="text-danger d-block">{{$message}}</small>
                        @enderror
                    </div>
                    @if ($is_gov_exam_participated == 'yes')
                    <div class="row is_gov_exam_participated_input">
                        <div class="col">
                            <label for="govt_exams_1">Govt. Exam <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="text" class="form-control" id="govt_exams_1" wire:model.blur="govt_exams_1" placeholder="Enter exam name">
                                </div>
                                <div class="input-group-append">
                                    <select id="exam_one_year" wire:model.blur="exam_one_year" class="form-control form-select">
                                        <option value="">Year &nbsp; &nbsp;</option>
                                        @for ($year = date('Y'); $year >= date('Y')-15; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="input-group-append">
                                    <input style=" width: 7rem;" type="text" wire:model.blur="exam_one_result" class="form-control text_on_side_input" id="exam_one_result" placeholder="Result.">
                                </div>
                            </div>

                            @error('govt_exams_1')
                            <small class="text-danger d-block">{{$message}}</small>
                            @enderror
                            @error('exam_one_year')
                            <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                            @error('exam_one_result')
                            <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    @endif
                </div>
                <div class="form-group">

                    <div class="col-md-9 col">
                        <label for="govt_exams"> Did you apply career without barrier
                            scholarship exam? <span class="text-danger">* &nbsp;</span></label>

                        <input type="radio" wire:model.live="is_apply_career_without_barrier" value="yes" style="width: 15px;height:15px">
                        Yes &nbsp;
                        <input type="radio" wire:model.live="is_apply_career_without_barrier" value="no" style="width: 15px;height:15px">
                        No
                        @error('is_apply_career_without_barrier')
                        <small class="text-danger d-block">{{$message}}</small>
                        @enderror
                    </div>

                    @if ($is_apply_career_without_barrier == 'yes')
                    <div class="row career_without_barrier">
                        <div class="col">
                            <label for="career_exams_1">Exam 1. <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <select name="career_exams_1" class="form-control text_on_side_input" id="career_exams_1" placeholder="Enter name exam.">
                                        <option value="">Select Scholarship category</option>
                                        @foreach(App\Models\EducationType::all() as $education)
                                        <option {{$student->career_exams_1 == $education->name ? 'selected' : ''}} value="{{$education->name}}">{{$education->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group-append">
                                    <select id="year" name="year" class="form-control form-select">
                                        <option value="">Year &nbsp; &nbsp;</option>
                                        @for ($year = date('Y'); $year >= date('Y')-15; $year--)
                                        <option value="{{ $year }}" {{$student->year == $year ? 'selected' : ''}}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="input-group-append">
                                    <input style=" width: 7rem;" type="text" name="career_one_result" class="form-control text_on_side_input" id="career_one_result" placeholder="Result." value="{{$student->career_one_result}}">
                                </div>
                            </div>
                            <i toggle="#password-field" class="fa fa-fw  field-icon text_on_input">1.</i>
                            @error('career_exams_1')
                            <small class="text-danger d-block">{{$message}}</small>
                            @enderror
                            @error('year')
                            <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                            @error('career_one_result')
                            <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="career_exams_2">Exam 2. </label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <select name="career_exams_2" class="form-control text_on_side_input" id="career_exams_2" placeholder="Enter name exam." value="{{$student->career_exams_2}}">
                                        <option value="">Select Scholarship category</option>
                                        @foreach(App\Models\EducationType::all() as $education)
                                        <option {{$student->career_exams_2 == $education->name ? 'selected' : ''}} value="{{$education->name}}">{{$education->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('career_exams_2')
                                    <small class="text-danger d-block">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="input-group-append">
                                    <select id="test_center_a" name="career_two_year" class="form-control form-select">
                                        <option value="">Year &nbsp; &nbsp;</option>
                                        @for ($year = date('Y'); $year >= date('Y')-15; $year--)
                                        <option value="{{ $year }}" {{$student->career_two_year == $year ? 'selected' : ''}}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                    @error('career_two_year')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="input-group-append">
                                    <input style=" width: 7rem;" type="text" name="career_two_result" class="form-control text_on_side_input" id="career_two_result" placeholder="Result." value="{{$student->career_two_result}}">
                                    @error('career_two_result')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="input-group-append">
                                </div>
                            </div>
                            <i toggle="#password-field" class="fa fa-fw  field-icon text_on_input">2.</i>
                        </div>
                    </div>
                    @endif

                </div>
                <div class="row">
                    <div class="col-md-6 col">
                        <div class="form-group">
                            <label for="family_income">Family Income:</label>
                            <br>
                            <select id="family_income" wire:model.blur="family_income" class="form-control form-select">
                                <option value="">--Select Family Income--</option>
                                <option value="1">Less than 1L</option>
                                <option value="2">1L to 2L</option>
                                <option value="3">2L to 3L</option>
                                <option value="4">3L to 5L</option>
                                <option value="5">5L and above</option>
                            </select>
                            @error('family_income')
                            <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col">
                        <div class="form-group">
                            <label for="mother_occupation">Father/Mother’s Occupation:</label>
                            <div class="input-group">
                                <div class="input-group-append" style="width: 50%;">
                                    <input type="text" class="form-control" id="father_occupation" wire:model.blur="father_occupation" placeholder="Father Occupation">
                                    @error('father_occupation')
                                    <small class="text-danger d-block">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="input-group-append" style="width: 50%;">
                                    <input type="text" class="form-control" id="mother_occupation" wire:model.blur="mother_occupation" placeholder="Mother Occupation">
                                    @error('mother_occupation')
                                    <small class="text-danger d-block">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Personal Details -->
                <!-- Educational Qualification -->
                <!-- Additional Optional Information -->

                <div class="row custom-row-patty">
                    <div class="col-md-6 col">
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <label for="photo">Attach Photograph (Max 2MB, JPG, PNG Only):<span class="text-danger">*</span></label>
                                @if(($photo && $photo->temporaryUrl()) || $student->photograph)
                                <img src="{{ $photo?->temporaryUrl() ?? asset('/storage/'.$student->photograph) }}" class="img-fluid pb-3" style="max-height: 100px;" alt="photograph">
                                @endif
                            </div>
                            <input type="file" wire:model.blur="photo" class="form-control" id="photo" {{$student->photograph ? '' : 'required'}}>
                            @error('photo')
                            <small class="text-danger d-block">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col">
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <label for="signature">Attach Signature (Max 2MB, JPG, PNG Only):<span class="text-danger">*</span></label>
                                @if(($sign && $sign->temporaryUrl()) || $student->signature)
                                <img src="{{ $sign?->temporaryUrl() ?? asset('/storage/'.$student->signature) }}" class="img-fluid pb-3" style="max-height: 100px;" alt="Signature">
                                @endif
                            </div>
                            <input type="file" wire:model.blur="sign" class="form-control" id="signature" {{$student->signature ? '' : 'required'}}>
                            @error('sign')
                            <small class="text-danger d-block">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col mb-3 mt-2">
                        <div class="form-check">

                            <input type="checkbox" wire:model.live="terms_conditions" class="form-check-input" id="terms_conditions"> <!-- Checked value -->
                            <label class="form-check-label" for="terms_conditions"><span class="text-danger">*</span> <b>I agree for Career without Barrier’s @if($student->terms_conditions && $termsCondition) <a style="text-decoration: underline;" href="{{ asset('home/'.$termsCondition->terms_condition_pdf) }}" target="_blank"> Terms & Conditions </a></b> &nbsp;&nbsp; &check; @endif</label>

                        </div>
                        @error('terms_conditions')
                        <small class="text-danger d-block">{{$message}}</small>
                        @enderror
                    </div>
                </div>

                <div class="row justify-content-center">

                    <div class="col-md-3 mt-2 col d-grid">
                        <button type="submit" class="btn btn-theme submitform">Save and Review</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>