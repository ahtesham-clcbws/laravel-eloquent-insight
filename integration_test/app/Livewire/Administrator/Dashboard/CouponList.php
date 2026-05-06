<?php

namespace App\Livewire\Administrator\Dashboard;

use App\Models\Corporate;
use App\Models\CouponCode;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('administrator.layouts.master')]
class CouponList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $couponslist;

    public $prefixes;

    public $selectedPrefix = '';

    public $selectedStatus = '';

    public $values;

    public $selectedValue = '';

    public $institutes = [];

    public $selectedInstitute = '';

    public $selectedValueType = '';

    public $selectedIssued = '';

    public $perPage = 10;

    public $couponCodeSearch = '';

    public bool $selectAll = false;

    public $selectedCupons = [];

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

    public function mount()
    {
        // $this->appliedCount = CouponCode::where('status', 1)->where('is_applied', 1)->count();
        $this->prefixes = CouponCode::whereNotNull('prefix')->groupBy('prefix')->pluck('prefix', 'prefix');
        $this->values = CouponCode::whereNotNull('value')->groupBy('value')->orderBy('value', 'asc')->pluck('value', 'value');
        $instituteIDs = (CouponCode::whereNotNull('corporate_id')->groupBy('corporate_id')->pluck('corporate_id', 'corporate_id'))->toArray();
        $this->institutes = Corporate::whereIn('id', $instituteIDs)->select('institute_name', 'id', 'district_id')->with('district')->get();
    }

    public function updated($property)
    {
        if ($property == 'selectAll') {
            $this->selectedCupons = $this->selectAll ? $this->couponslist : [];
        }
        $this->resetPage();
    }

    public function render()
    {
        $query = CouponCode::orderByDesc('created_at')->with('corporate');

        if (!empty(trim($this->selectedPrefix))) {
            $query->where('prefix', $this->selectedPrefix);
        }
        if (!empty(trim($this->selectedStatus))) {
            // active, insactive, applied
            if ($this->selectedStatus == 'active') {
                $query->where('status', 1)->where('is_applied', 0);
            } else if ($this->selectedStatus == 'inactive') {
                $query->where('status', 0)->where('is_applied', 0);
            } else if ($this->selectedStatus == 'applied') {
                $query->where('is_applied', 1);
            }
        }
        if (!empty(trim($this->selectedIssued))) {
            if ($this->selectedIssued == 'issued') {
                $query->whereNotNull('corporate_id');
            } else if ($this->selectedStatus == 'not-issued') {
                $query->whereNull('corporate_id');
            }
        }
        if (!empty(trim($this->selectedValue))) {
            $query->where('value', $this->selectedValue);
        }
        if (!empty(trim($this->selectedInstitute))) {
            if ($this->selectedInstitute == 'admin-only') {
                $query->whereNull('corporate_id');
            } else {
                $query->where('corporate_id', $this->selectedInstitute);
            }
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

        return view('livewire.administrator.dashboard.coupon-list', [
            'coupons' => $coupons
        ]);
    }

    public function resetSorting()
    {
        $this->sortType = 'id';
        $this->sortDirection = 'desc';
    }

    public function resetFilters()
    {
        $this->selectedPrefix = '';
        $this->selectedStatus = '';
        $this->selectedValue = '';
        $this->selectedValueType = '';
        $this->selectedIssued = '';
    }

    public function deleteSelected()
    {
        CouponCode::whereIn('id', $this->selectedCupons)->where('is_applied', 0)->delete();
        $this->js("success('Successfully delete coupons.')");
        $this->selectedCupons = [];
        $this->selectAll = false;
        // return redirect()->route('coupon.lists');
    }

    public function delete($id)
    {
        CouponCode::where('id', $id)->delete();
        $this->js("success('Successfully delete coupon')");
    }

    public function statusChange($id, $active = false)
    {
        CouponCode::where('id', $id)->update(['status' => $active ? 1 : 0]);
        $this->js("success('Status delete coupon')");
    }
}
