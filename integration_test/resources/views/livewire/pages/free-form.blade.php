<div>
    <div class="w-100" style="margin-top:72px;background-color:#f26b3c;">
        <div class="container py-5 pb-4 text-center">
            <h3 class="text-white" style="font-size:22px; font-weight:900;">
                Authorised Coaching Institutes, School/ Colleges, Trust/ Societies, Social Workers<br />For<br />Free Form Disribution in Uttar Pradesh
            </h3>
        </div>
    </div>
    <style>
        .small {
            font-size: 80%;
        }
    </style>
    <div class="container py-5">
        <div class="d-flex flex-column flex-lg-row justify-content-between">
            <div class="d-flex gap-2">
                <div class="input-group mb-3 w-auto">
                    @if (count($districts))
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="selectCity">Select city</label>
                        </div>
                        <select class="custom-select" id="selectCity" wire:model.change="selectedDistrict">
                            <option value='' selected>All Cities</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="input-group mb-3 ml-3 w-auto">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="showEntries">Show</label>
                    </div>
                    <select class="custom-select" id="showEntries" wire:model.change="entriesPerPage">
                        @foreach ($entiresArray as $entry)
                            <option value="{{ $entry }}">{{ $entry == 0 ? 'All' : $entry }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="input-group mb-3 w-auto">
                <input class="form-control" type="text" aria-label="Search" placeholder="Search institute/school ..."
                    wire:model.live="search">
                <div class="input-group-append">
                    <i class="input-group-text fa fa-search"></i>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-bordered table" id="institutes-table" style="line-height: 1.4;">
                <thead>
                    <tr>
                        <th scope="col"><small class="font-weight-bold">#</small>
                        <th scope="col"><small class="font-weight-bold">City/State</small>
                        <th scope="col"><small class="font-weight-bold">Authorised person</small>
                        <th scope="col"><small class="font-weight-bold">Institute/School/Person<br />E-mail &
                                Mobile</small>
                        <th scope="col"><small class="font-weight-bold">Address</small>
                        <th scope="col"><small class="font-weight-bold">100% Discount<br />Coupon</small>
                        <th scope="col"><small class="font-weight-bold">Other Discount<br />Coupon</small>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mainInstitutes as $institute)
                        <tr>
                            <td class="small">{{ $loop->index + 1 }}</td>
                            <td class="small text-primary">{{ $institute->district->name }}</td>
                            <td class="small">
                                <div class="media text-muted d-flex flex-column">
                                    <img class="d-block mr-2 rounded"
                                        data-src="{{ $institute->attachment }}"
                                        data-holder-rendered="true"
                                        src="{{ $institute->attachment }}"
                                        alt="{{ $institute->name }}" style="width: 32px; height: 32px;">
                                    <p class="media-body lh-125 text-primary mb-0">
                                        {{ $institute->name }}
                                    </p>
                                </div>
                            </td>
                            <td class="small">
                                <span class="text-primary"><b>{{ $institute->institute_name }}</b></span><br />
                                <small>{{ $institute->phone }}</small><br />
                                <small>{{ $institute->message }}</small>
                            </td>
                            <td class="small">{{ $institute->address }}</td>
                            <td>
                                <span class="badge badge-success px-3 py-2">100% Free <i class="fa fa-check"></i></span><br />
                                <small class="text-danger font-weight-bold">Limited Forms</small>
                            </td>
                            <td>
                                @if ($institute->available_button)
                                    <span class="badge badge-primary px-3 py-2">Upto 60% <i class="fa fa-check"></i></span><br />
                                    <small class="text-danger font-weight-bold">Available</small>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @foreach ($institutes as $institute)
                        <tr>
                            <td class="small">{{ $loop->index + 1 + count($mainInstitutes) }}</td>
                            <td class="small text-primary">{{ $institute->district && $institute->district->name ? $institute->district->name : '' }}</td>
                            <td class="small">
                                <div class="media text-muted d-flex flex-column">
                                    <img class="d-block mr-2 rounded"
                                        data-src="{{ asset('/storage/' . $institute->attachment) }}"
                                        data-holder-rendered="true"
                                        src="{{ asset('/storage/' . $institute->attachment) }}"
                                        alt="{{ $institute->name }}" style="width: 32px; height: 32px;">
                                    <p class="media-body lh-125 text-primary mb-0">
                                        {{ $institute->name }}
                                    </p>
                                </div>
                                <!-- {{ $institute->attachment }}<br /> -->
                                <!-- {{ $institute->name }} -->
                            </td>
                            <td class="small">
                                <span class="text-primary">{{ $institute->institute_name }}</span><br />
                                {{ $institute->email }}<br />
                                {{ $institute->phone }}
                            </td>
                            <td class="small">{{ $institute->address }}, {{ $institute->pincode }}</td>
                            <td>
                                <small class="text-danger font-weight-bold">Limited</small><br />
                                <span class="badge badge-success px-3 py-2">100% Free <i class="fa fa-check"></i></span>
                            </td>
                            <td>
                                <small class="text-danger font-weight-bold">Available</small><br />
                                <span class="badge badge-primary px-3 py-2">Upto 60% <i class="fa fa-check"></i></span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($entriesPerPage > 0)
            <div class="">
                {{ $institutes->links() }}
            </div>
        @endif

    </div>
</div>
