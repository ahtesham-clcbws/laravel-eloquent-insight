<?php

namespace App\Livewire\Administrator\Corporate;

use App\Models\Corporate;
use App\Models\CouponCode;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('administrator.layouts.master')]
class InstituteCouponlist extends Component
{
    public Corporate $corporate;

    use WithPagination, WithoutUrlPagination;

    public $couponslist;

    public $prefixes;
    public $selectedPrefix = '';

    public $selectedStatus = '';

    public $values;
    public $selectedValue = '';

    public $selectedValueType = '';

    public $perPage = 10;

    public $couponCodeSearch = '';

    public $sortType = 'id';
    public $sortDirection = 'desc';


    public function sort($type)
    {
        if ($type == $this->sortType) {
            if ($this->sortDirection == 'asc') {
                $this->sortDirection = 'desc';
            } else {
                $this->sortDirection = 'asc';
            }
        }
    }

    public function updated($property)
    {
        $this->resetPage();
    }

    public function mount($corporateId)
    {
        $this->corporate = Corporate::find($corporateId);
        $this->prefixes = CouponCode::whereNotNull('prefix')->where('corporate_id', $corporateId)->where('status', 1)->groupBy('prefix')->pluck('prefix', 'prefix');
        $this->values = CouponCode::whereNotNull('value')->where('corporate_id', $corporateId)->where('status', 1)->groupBy('value')->orderBy('value', 'asc')->pluck('value', 'value');
    }


    public function render()
    {
        $query = CouponCode::where('corporate_id', $this->corporate->id)->where('status', 1)->orderByDesc('created_at');

        if (!empty(trim($this->selectedPrefix))) {
            $query->where('prefix', $this->selectedPrefix);
        }
        if (!empty(trim($this->selectedStatus))) {
            // active, insactive, applied
            if ($this->selectedStatus == 'applied') {
                $query->where('is_applied', 1);
            }
        }
        if (!empty(trim($this->selectedValue))) {
            $query->where('value', $this->selectedValue);
        }

        if (!empty(trim($this->selectedValueType))) {
            $query->where('valueType', $this->selectedValueType);
        }
        if (!empty(trim($this->couponCodeSearch))) {
            $query->where('couponcode', 'LIKE', '%' . $this->couponCodeSearch . '%');
        }

        $query->orderBy($this->sortType, $this->sortDirection);
        $coupons = $query->paginate($this->perPage);
        // $this->couponslist = $coupons->pluck('id');
        $this->couponslist = $coupons->pluck('id');

        return view('livewire.administrator.corporate.institute-couponlist', [
            'coupons' => $coupons
        ]);
    }

    public function resetFilters()
    {
        $this->selectedPrefix = '';
        $this->selectedStatus = '';
        $this->selectedValue = '';
        $this->selectedValueType = '';
    }
}
