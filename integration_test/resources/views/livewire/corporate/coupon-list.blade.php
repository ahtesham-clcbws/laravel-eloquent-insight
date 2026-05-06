<div class="h-100">
    <style>
        .boxShadow {
            margin: 10px auto;
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .coupons_table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #dee2e6;
        }

        .coupons_table td,
        .coupons_table th {
            padding: 2px;
            vertical-align: middle;
        }

        .coupons_table .sortHead {
            cursor: pointer;
            min-width: 100px;
        }

        .headerGridBox .flex-fill {
            min-width: 100px;
        }

        .opacity-half {
            opacity: 0.5;
        }
    </style>

    <h3 style="padding-top: 10px;padding-left: 10px;">
        Coupon List:
    </h3>
    <div class="row">
        <div class="col-lg-12 col-md-12 col" style="margin-left: auto;margin-right:auto">

            <div class="container boxShadow d-flex">
                <div class="d-flex flex-wrap gap-2 align-items-end headerGridBox" style="width: 100%;">
                    <div class="flex-fill">
                        <label for="couponCode">Coupon Code:</label>
                        <select class="form-select" id="couponCode" wire:model.live="selectedPrefix">
                            <option value="">All</option>
                            @foreach($prefixes as $value)
                            <option value="{{$value}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-fill">
                        <label for="couponStatuses">Status:</label>
                        <select class="form-select" id="couponStatuses" wire:model.live="selectedStatus">
                            <option value="">All</option>
                            <option value="applied">Applied only</option>
                        </select>
                    </div>
                    <div class="flex-fill">
                        <label for="couponValues">Value:</label>
                        <select class="form-select" id="couponValues" wire:model.live="selectedValue">
                            <option value="">All</option>
                            @foreach($values as $value)
                            <option value="{{$value}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-fill">
                        <label for="couponValueTypes">Value type:</label>
                        <select class="form-select" id="couponValueTypes" wire:model.live="selectedValueType">
                            <option value="">All</option>
                            <option value="amount">Amount</option>
                            <option value="percentage">Percentage</option>
                        </select>
                    </div>
                    @if (!empty(trim($selectedPrefix)) || !empty(trim($selectedStatus)) || !empty(trim($selectedValue)) || !empty(trim($selectedValueType)))
                    <div class="flex-fill">
                        <button class="btn btn-danger w-100" wire:click="resetFilters"><i class="bi bi-x-lg"></i> Reset</button>
                    </div>
                    @endif

                </div>
            </div>
            <!-- datatablecl -->
            <div class="container boxShadow">


                <div class="d-flex justify-content-between mb-2">
                    <div class="d-flex flex-wrap gap-2 align-items-end">
                        <div class="flex-fill">
                            <select class="form-select" id="showResutsPerPage" wire:model.live="perPage">
                                <option value="10">10 Results</option>
                                <option value="20">20 Results</option>
                                <option value="30">30 Results</option>
                                <option value="50">50 Results</option>
                                <option value="100">100 Results</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="flex-fill">
                            <input class="form-control" type="search" id="couponCodeSearch" wire:model.live="couponCodeSearch" placeholder="Coupon code search" />
                        </div>
                    </div>
                </div>

                <table class="table coupons_table" style="width:100%">
                    <thead class="thead-light">
                        <tr class="">
                            <th>#</th>
                            <th class="sortHead" data-type="prefix">
                                <span>Prefix</span>
                                <span type="button" class="<?= $sortType == 'prefix' ? '' : 'opacity-half' ?> d-none">
                                    @if ($sortType == 'prefix' && $sortDirection == 'asc')
                                    <i class="bi bi-sort-alpha-down"></i>
                                    @else
                                    <i class="bi bi-sort-alpha-up"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="sortHead" data-type="name">
                                <span>Name</span>
                                <span type="button" class="<?= $sortType == 'name' ? '' : 'opacity-half' ?> d-none">
                                    @if ($sortType == 'name' && $sortDirection == 'asc')
                                    <i class="bi bi-sort-alpha-down"></i>
                                    @else
                                    <i class="bi bi-sort-alpha-up"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="sortHead" data-type="couponcode">
                                <span>Coupon Code</span>
                                <span type="button" class="<?= $sortType == 'couponcode' ? '' : 'opacity-half' ?> d-none">
                                    @if ($sortType == 'couponcode' && $sortDirection == 'asc')
                                    <i class="bi bi-sort-alpha-down"></i>
                                    @else
                                    <i class="bi bi-sort-alpha-up"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="sortHead" data-type="issued">
                                <span>Issued to</span>
                                <span type="button" class="<?= $sortType == 'issued' ? '' : 'opacity-half' ?> d-none">
                                    @if ($sortType == 'issued' && $sortDirection == 'asc')
                                    <i class="bi bi-sort-alpha-down"></i>
                                    @else
                                    <i class="bi bi-sort-alpha-up"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="sortHead" data-type="value">
                                <span>Value</span>
                                <span type="button" class="<?= $sortType == 'value' ? '' : 'opacity-half' ?> d-none">
                                    @if ($sortType == 'value' && $sortDirection == 'asc')
                                    <i class="bi bi-sort-alpha-down"></i>
                                    @else
                                    <i class="bi bi-sort-alpha-up"></i>
                                    @endif
                                </span>
                            </th>
                            <th class="sortHead" data-type="status">
                                <span>Status</span>
                                <span type="button" class="<?= $sortType == 'status' ? '' : 'opacity-half' ?> d-none">
                                    @if ($sortType == 'status' && $sortDirection == 'asc')
                                    <i class="bi bi-sort-alpha-down"></i>
                                    @else
                                    <i class="bi bi-sort-alpha-up"></i>
                                    @endif
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table-striped table-striped-coupon">
                        @if (count($coupons))
                        @foreach ($coupons as $coupon)
                        <tr style="<?= $coupon->is_applied ? 'background-color: #2180614d;' : ($coupon->status ? '' : 'color: red;') ?>">
                            <td style="font-size: 13px">{{ $loop->index+1 }}</td>
                            <td style="font-size: 13px">{{ $coupon->prefix }}</td>
                            <td style="font-size: 13px">
                                {{ $coupon->name }}
                                @if (!empty(trim($coupon->description)))
                                <br /><small class="wrap">{{ $coupon->description }}</small>
                                @endif
                            </td>
                            <td style="font-size: 13px">{{ $coupon->couponcode }}</td>
                            <td style="font-size: 13px">{{ $coupon->corporate?->institute_name }}</td>
                            <td style="font-size: 13px">{{ $coupon->valueType == 'amount' ? '₹ ' : '' }}{{ $coupon->value }}{{ $coupon->valueType == 'amount' ? '' : '%' }}</td>
                            <td style="font-size: 13px">{{ $coupon->is_applied ? 'Applied' : 'Active' }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td rowspan="7" colspan="7" class="text-center py-5">
                                <h2>No results found</h2>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <div class="">
                    {{ $coupons->onEachSide(3)->links('vendor.livewire.bootstrap') }}
                </div>
            </div>

        </div>
    </div>
</div>