<div class="row mt-2">
    <div class="col-md-12 col mb-2">
        <label class="form-label">Institute Name<span
                class="text-danger">{{ in_array($index, [1, 2]) ? '*' : '' }}</span></label>
        <input class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important"
            placeholder="Enter Name of institute" wire:model="institude_name{{ $index }}" />
        @error('institude_name' . $index)
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-12 col mb-2">
        <label class="form-label">Institute’s Director’s Name</label>
        <input class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important"
            placeholder="Enter Name of Director" wire:model="institude_director{{ $index }}" />
        @error('institude_director' . $index)
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-12 col mb-2">
        <label class="form-label">Institute/ Contact Details<span
                class="text-danger">{{ in_array($index, [1, 2]) ? '*' : '' }}</span></label>
        <input class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important"
            placeholder="Enter Mobile No" wire:model="institude_mobile{{ $index }}" />
        @error('institude_mobile' . $index)
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-12 col mb-2">
        <label class="form-label">Whatsapp Contact</label>
        <input class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important"
            placeholder="Enter Whatsapp No" wire:model="whatsapp_no{{ $index }}" />
    </div>
    <div class="col-md-12 col mb-2">
        <label class="form-label">Institute E-mail Id</label>
        <input class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important"
            placeholder="Enter Email" wire:model="institude_email{{ $index }}" />
    </div>
    <div class="col-md-6 col mb-2">
        <label class="form-label">State<span
                class="text-danger">{{ in_array($index, [1, 2]) ? '*' : '' }}</span></label>
        <input class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important"
            placeholder="State" wire:model="state{{ $index }}" readonly />
    </div>
    <div class="col-md-6 col mb-2">
        <label class="form-label">City<span
                class="text-danger">{{ in_array($index, [1, 2]) ? '*' : '' }}</span></label>
        <select class="form-control form-select"
            style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important"
            wire:model="city_id{{ $index }}">
            <option value="">--Select City--</option>
            @foreach ($cities as $city)
                <option value="{{ $city->id }}">{{ $city->name }}</option>
            @endforeach
        </select>
        @error('city_id' . $index)
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-12 mb-2">
        <label class="form-label">Institute Address<span
                class="text-danger">{{ in_array($index, [1, 2]) ? '*' : '' }}</span></label>
        <input class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important"
            placeholder="Institute Address" wire:model="institude_address{{ $index }}" />
        @error('institude_address' . $index)
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-12 mb-2">
        <label class="form-label">Desired Course Detail<span
                class="text-danger">{{ in_array($index, [1, 2]) ? '*' : '' }}</span></label>
        <input class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important"
            placeholder="Desired Course Detail*" wire:model="desired_course_detail{{ $index }}" />
        @error('desired_course_detail' . $index)
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 col mb-2">
        <label class="form-label">Course Fee<span
                class="text-danger">{{ in_array($index, [1, 2]) ? '*' : '' }}</span></label>
        <input class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important"
            wire:model="course_fee{{ $index }}" placeholder="Enter fee" />
        @error('course_fee' . $index)
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 col mb-2">
        <label class="form-label">Course Duration<span
                class="text-danger">{{ in_array($index, [1, 2]) ? '*' : '' }}</span></label>
        <input class="form-control" style="height:calc(2rem + 2px) !important; padding:0 0 0 3px !important"
            wire:model="course_duration{{ $index }}" placeholder="Enter Duration" />
        @error('course_duration' . $index)
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-12 col mb-2">
        <label class="form-label">Institute Prospectus</label>
        <input class="form-control" type="file" wire:model="institude_prospectus{{ $index }}">
        <div wire:loading wire:target="institude_prospectus{{ $index }}">Uploading...</div>
        @if ($this->{"institude_prospectus$index"} && is_string($this->{"institude_prospectus$index"}))
            <div class="mt-1">
                <a class="small" href="/upload/{{ $this->{"institude_prospectus$index"} }}" target="_blank">View
                    Current File</a>
            </div>
        @endif
        @error('institude_prospectus' . $index)
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
</div>
