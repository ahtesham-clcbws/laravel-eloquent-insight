<?php

namespace App\Livewire\Administrator\Dashboard;

use Illuminate\Support\Str;
use App\Models\CouponCode;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('administrator.layouts.master')]
class CreateCoupon extends Component
{
    public $prefix;
    public $name;
    public $coupon_type;
    public $discount_type;
    public $discount_value;
    public $number_of_coupons;
    public $description;
    public $existingPrefixes = [];
    public $existingCouponsCount = 0;

    protected $rules = [
        'prefix' => 'required|alpha_num',
        'name' => 'required',
        'number_of_coupons' => 'required|integer|min:1',
        'discount_type' => 'required',
        'discount_value' => 'required|numeric|min:0',
        'coupon_type' => 'nullable',
        'description' => 'nullable',
    ];

    public function mount()
    {
        $counts = CouponCode::select('prefix', DB::raw('count(*) as aggregate'))
            ->groupBy('prefix')
            ->pluck('aggregate', 'prefix');

        $this->existingPrefixes = CouponCode::orderByDesc('id')
            ->get(['prefix', 'name', 'coupon_type', 'valueType', 'value', 'description'])
            ->unique('prefix')
            ->whereNotNull('prefix')
            ->map(function ($item) use ($counts) {
                $item->count = $counts[$item->prefix] ?? 0;
                return $item;
            })
            ->keyBy('prefix')
            ->toArray();
    }

    public function updatedPrefix($value)
    {
        // Sanitize prefix: remove spaces and non-alphanumeric characters
        $sanitized = preg_replace('/[^a-zA-Z0-9]/', '', $value);
        if ($sanitized !== $value) {
            $this->prefix = $sanitized;
            $value = $sanitized;
        }

        if (isset($this->existingPrefixes[$value])) {
            $data = $this->existingPrefixes[$value];

            // Explicitly cast and handle nulls to ensure Livewire tracking
            $this->name = (string) ($data['name'] ?? '');
            $this->coupon_type = (string) ($data['coupon_type'] ?? '');
            $this->discount_type = (string) ($data['valueType'] ?? '');
            $this->discount_value = $data['value'] ?? 0;
            $this->description = (string) ($data['description'] ?? '');
            $this->existingCouponsCount = $data['count'] ?? 0;
        } else {
            $this->existingCouponsCount = 0;
        }
    }

    public function save()
    {
        $this->validate();

        $digit = 8; // The length of the random string
        $coupons = [];

        for ($i = 0; $i < $this->number_of_coupons; $i++) {
            // Generate a random coupon code
            $randomString = Str::random($digit);
            $randomNumber = mt_rand(1000, 9999);
            $couponCode = $this->prefix . $randomString . $randomNumber;

            // Check if the coupon code already exists in the database
            while (DB::table('coupon_codes')->where('couponcode', $couponCode)->exists()) {
                $randomString = Str::random($digit);
                $randomNumber = mt_rand(1000, 9999);
                $couponCode = $this->prefix . '-' . $randomString . $randomNumber;
            }

            $coupons[] = [
                'name' => $this->name,
                'couponcode' => $couponCode,
                'coupon_type' => $this->coupon_type,
                'description' => $this->description,
                'prefix' => $this->prefix,
                'digit' => $digit,
                'value' => $this->discount_value,
                'valueType' => $this->discount_type,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('coupon_codes')->insert($coupons);

        session()->flash('success', 'Coupons added successfully.');

        return redirect()->route('coupon.lists');
    }

    public function render()
    {
        return view('livewire.administrator.dashboard.create-coupon');
    }
}
