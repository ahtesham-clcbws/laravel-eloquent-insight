<?php

namespace App\Livewire\Administrator\Corporate;

use App\Models\Corporate;
use App\Models\CorporateCouponRequest;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('administrator.layouts.master')]
class AdminCouponRequestsList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $couponslist;

    public $institutes = [];
    public $selectedInstitute = '';

    public $perPage = 10;

    public bool $selectAll = false;

    public $selectedCupons = [];

    public function mount()
    {
        $instituteIDs = (CorporateCouponRequest::whereNotNull('corporate_id')->groupBy('corporate_id')->pluck('corporate_id', 'corporate_id'))->toArray();
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
        $query = CorporateCouponRequest::orderByDesc('created_at')->with('corporate');

        $query->orderBy('id', 'desc');
        $coupons = $query->paginate($this->perPage);
        // $this->couponslist = $coupons->pluck('id');
        $this->couponslist = $coupons->pluck('id');

        return view('livewire.administrator.corporate.admin-coupon-requests-list', [
            'coupons' => $coupons
        ]);
    }

    public function resetFilters()
    {
        $this->selectedInstitute = '';
        $this->selectAll = false;
    }

    public function deleteSelected()
    {
        CorporateCouponRequest::whereIn('id', $this->selectedCupons)->delete();
        $this->js("success('Successfully delete coupon requests.')");
        $this->selectedCupons = [];
        $this->selectAll = false;
    }
    public function delete($id)
    {
        CorporateCouponRequest::where('id', $id)->delete();
        $this->js("success('Successfully delete coupon request')");
    }
    public function rejectRequest($id)
    {
        $coupon = CorporateCouponRequest::find($id);
        $coupon->status = 'rejected';
        $coupon->save();
        $this->js("success('Successfully reject coupon request')");
    }
}
