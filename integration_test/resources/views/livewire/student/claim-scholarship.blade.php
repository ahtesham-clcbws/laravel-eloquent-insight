<div>
    <style>
        .line-with-button {
            position: relative;
        }

        .round-button {
            position: absolute;
            right: 0;
            margin-top: -16px;
            margin-right: 26px;
            transform: translateY(-50%);
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            text-align: center;
            cursor: pointer;
        }

        .round-button:focus {
            outline: none;
        }

        .pagebody {
            background: white;
        }
    </style>
    <div class="pagecontentbody container">
        <div class="tab-content">
            <div class="row pt-3">
                <div class="col">
                    <h5>
                        Claim Form
                    </h5>
                </div>
            </div>
            <div class="pagebody row pt-2">
                <div class="col-md-11 container" style="border: 1px solid #c6cbd0;padding: 8px 25px;">
                    @if($claimForm)
                        <div class="alert {{ $claimForm->status == 'confirmed' ? 'alert-success' : ($claimForm->status == 'rejected' ? 'alert-danger' : 'alert-warning') }} mb-3 d-flex justify-content-between align-items-center">
                            <span><strong>Status:</strong> {{ ucfirst(str_replace('-', ' ', $claimForm->status)) }}</span>
                            @if($claimForm->status == 'pending-processing')
                                <span class="badge bg-light text-dark">Submitted & Under Review</span>
                            @endif
                        </div>
                    @endif
                    <form wire:submit.prevent="save">
                        <div class="row">
                            <div class="col-md-6 col" wire:key="choice-1">
                                <h5>
                                    Choice One
                                </h5>
                                @include('livewire.student.partials.choice-fields', ['index' => 1])
                            </div>

                            <div class="col-md-6 col" wire:key="choice-2">
                                <h5>
                                    Choice Two
                                </h5>
                                @include('livewire.student.partials.choice-fields', ['index' => 2])
                            </div>
                        </div>
                        <hr class="line-with-button">
                        <span class="round-button add-center-c"
                            wire:click="toggleMore">{{ $showMore ? '-' : '+' }}</span>

                        @if ($showMore)
                            <div class="row center-c-cl">
                                <div class="col-md-6 col" wire:key="choice-3">
                                    <h5>
                                        Choice Third
                                    </h5>
                                    @include('livewire.student.partials.choice-fields', ['index' => 3])
                                </div>

                                <div class="col-md-6 col" wire:key="choice-4">
                                    <h5>
                                        Choice Fourth
                                    </h5>
                                    @include('livewire.student.partials.choice-fields', ['index' => 4])
                                </div>
                            </div>
                        @endif

                        @if(!$claimForm || $claimForm->status != 'confirmed')
                            <div class="row justify-content-center mb-4 mt-3">
                                <div class="col-md-3 d-grid">
                                    <button class="btn btn-theme btn-primary submitform" type="submit">
                                        <span wire:loading.remove>Submit Claim</span>
                                        <span wire:loading>Processing...</span>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-success text-center mt-3">
                                <i class="fa fa-check-circle mr-2"></i> This claim form has been confirmed and cannot be modified.
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
