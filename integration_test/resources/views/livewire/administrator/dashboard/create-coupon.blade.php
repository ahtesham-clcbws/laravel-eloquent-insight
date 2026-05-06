<div>
    <div class="row">
        <h5>
            <div class="panel-heading py-3">Generate CouponCode:</div>
        </h5>
    </div>
    <div class="row">
        <div class="col-md-8 col-lg-8 col" style="margin-left: auto;margin-right:auto">
            <div class="panel panel-default m-t-15">
                <div class="panel-body">
                    <div class="card alert">
                        <div class="card-body">
                            <form wire:submit.prevent="save">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <p class="text-dark m-b-15 f-s-12">Prefix<span class="text-danger">*</span></p>
                                            <input type="text" wire:model.live="prefix" list="prefix-list" class="form-control input-focus" placeholder="Add Prefix">
                                            @if($existingCouponsCount > 0)
                                                <small class="text-success">Already created: <b>{{ $existingCouponsCount }}</b> coupons</small>
                                            @endif
                                            <datalist id="prefix-list">
                                                @foreach($existingPrefixes as $p => $data)
                                                    <option value="{{ $p }}">
                                                @endforeach
                                            </datalist>
                                            @error('prefix')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <p class="text-dark m-b-15 f-s-12">Name of Coupon<span class="text-danger">*</span></p>
                                            <input type="text" wire:model="name" class="form-control input-focus" placeholder="Enter name of ">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <p class="text-dark m-b-15 f-s-12">Coupon Type</p>
                                            <select class="form-control" id="coupon-type" wire:model="coupon_type">
                                                <option value="">Select Coupon Type</option>
                                                <option value="special">Special</option>
                                                <option value="paid_students">Paid Students</option>
                                                <option value="all_students">All Students</option>
                                            </select>
                                            @error('coupon_type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <p class="text-dark m-b-15 f-s-12">Discount Value Type<span class="text-danger">*</span></p>
                                            <select wire:model="discount_type" class="form-control">
                                                <option value="">Select Value Type</option>
                                                <option value="amount">Rupee</option>
                                                <option value="percentage">Percentage</option>
                                            </select>
                                            @error('discount_type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <p class="text-dark m-b-15 f-s-12">Discount Value<span class="text-danger">*</span></p>
                                            <input type="number" wire:model="discount_value" class="form-control input-focus" placeholder="Only Number i.e 5">
                                            @error('discount_value')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <p class="text-dark m-b-15 f-s-12">Number of Coupons<span class="text-danger">*</span></p>
                                            <input type="number" wire:model="number_of_coupons" class="form-control input-focus" placeholder="Only Number i.e 5">
                                            @error('number_of_coupons')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <p class="text-dark m-b-15 f-s-12">Description</p>
                                            <input type="text" wire:model="description" class="form-control input-focus">
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col text-center">
                                        <button type="submit" class="btn btn-warning btn-flat m-b-10 m-l-5" wire:target="save" wire:loading.attr="disabled">
                                            <span wire:target="save" wire:loading.remove>Submit</span>
                                            <span wire:target="save" wire:loading>Processing...</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
