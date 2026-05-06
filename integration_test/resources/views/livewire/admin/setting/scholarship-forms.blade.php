<div class="row px-3" style="min-height: 92vh;">
    <div class="col-lg-12">
        <div class="m-t-15 m-2">
            <div class="row justify-content-space-between py-2">
                <div class="col-md-6 col">
                    <h2>Scholarship Forms</h2>
                </div>
            </div>


            <div class="row g-3">
                <div class="col-md-3 col-sm-6 col-12">
                    <form class="card overflow-hidden" style="border-radius: 5px !important; box-shadow: none !important;"
                        wire:submit="addForms">
                        @csrf
                        <div class="card-header bg-dark">
                            Scholarship + District Forms
                        </div>
                        <div class="card-body" style="min-height: 251px;">
                            <div class="mb-3">
                                <label class="form-label" for="education_type_id">Scholarship Category</label>
                                <select class="form-select-sm form-select" wire:model.live="form.education_type_id"
                                    required>
                                    <option value=""></option>
                                    @foreach (\App\Models\EducationType::select('id', 'name')->get() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="education_type_id">State</label>
                                <select class="form-select-sm form-select" wire:model.live="form.selectedState"
                                    <?= $form->education_type_id ? '' : 'disabled' ?> required>
                                    <option value=""></option>
                                    @foreach (\App\Models\State::select('id', 'name', 'status')->get() as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="education_type_id">District</label>
                                <select class="form-select-sm form-select" wire:model.live="form.district_id"
                                    wire:key="{{ $form->selectedState }}" <?= $form->selectedState ? '' : 'disabled' ?>
                                    required>
                                    <option value=""></option>
                                    @foreach (\App\Models\District::where('state_id', $form->selectedState)->get() as $district)
                                        <option value="{{ $district->id }}"
                                            {{ $district->districtScholarshipLimits()->where('education_type_id', $form->education_type_id)->exists() ? 'disabled' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="education_type_id">Forms</label>
                                <input class="form-control form-control-sm" type="number" min="1"
                                    wire:model="form.max_registration_limit" <?= $form->district_id ? '' : 'disabled' ?>
                                    required />
                            </div>

                            <div class="btn-group btn-group-sm w-100" role="group">
                                <button class="btn btn-success"
                                    type="submit">{{ isset($form->formsData) && $form->formsData ? 'Update' : 'Add' }}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-9 col-sm-6 col-12">
                    <div class="card card-body" style="border-radius: 5px !important; box-shadow: none !important;">
                        <div class="d-flex mb-3 gap-3">
                            <select class="form-select-sm form-select" wire:model.live="selectedCategory">
                                <option value="">Scholarship Category ...</option>
                                @foreach (\App\Models\EducationType::select('id', 'name')->whereHas('DistrictScholarshipLimits')->get() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <select class="form-select-sm form-select" wire:model.live="selectedState">
                                <option value="">All States ...</option>
                                @foreach (\App\Models\State::get(['id', 'name']) as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                            <select class="form-select-sm form-select" wire:model.live="selectedDistrict"
                                wire:key="{{ $selectedState }}" <?= !$selectedState ? 'disabled' : '' ?>>
                                <option value="">All Districts ...</option>
                                @foreach (\App\Models\District::select('id', 'name')->where('state_id', $selectedState)->whereHas('DistrictScholarshipLimits')->get() as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="table-responsive">
                            <table class="table-bordered table-sm table">
                                <thead>
                                    <tr>
                                        <th class="text-start">S.No</th>
                                        <th>Scholarship</th>
                                        <th>District</th>
                                        <th>Roll No's</th>
                                        <th>Applied Forms</th>
                                        <th>Issued Forms</th>
                                        {{-- <th>Forms / Students / Roll.No</th> --}}
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($formsData as $index => $entity)
                                        <tr>
                                            <td class="text-start"><b>{{ $index + 1 }}</b></td>
                                            {{-- <td class="text-start"><b>{{ $entity->id }}</b></td> --}}
                                            <td>{{ $entity->EducationType->name }}</td>
                                            <td>{{ $entity->district->name }}</td>
                                            <td>{{ $entity->roll_numbers_count }}</td>
                                            <td class="text-success font-weight-bold">{{ $entity->students_count }}</td>
                                            <td>{{ $entity->max_registration_limit }}</td>
                                            {{-- <td>{{ $entity->max_registration_limit . ' - ' . $entity->students_count . ' - ' . $entity->roll_numbers_count }}
                                            </td> --}}
                                            <td>
                                                <div class="d-flex gap-2 border-0">
                                                    <button
                                                        class="btn btn-xs {{ $roll_numbers ? 'btn-secondary' : 'btn-info' }}">
                                                        <i class="bi bi-pencil-square"
                                                            wire:click="editForms({{ $entity->id }})"></i>
                                                    </button>
                                                    <button
                                                        class="btn btn-xs {{ $roll_numbers ? 'btn-secondary' : 'btn-danger' }}">
                                                        <i class="bi bi-trash2"
                                                            wire:click="deleteForm({{ $entity->id }})"
                                                            wire:confirm.prompt="Are you sure?\n\nType DELETE to confirm|DELETE"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2">
                            {{ $formsData->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
