<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\CouponCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = CouponCode::paginate(10);
        return view('Admin.coupon.list');
    }

    public function filter(Request $request)
    {
        // Filter criteria
        $prefix = $request->input('prefix');
        $status = $request->input('status');

        // Query builder for coupon codes
        $query = CouponCode::query();

        // Apply filters if provided
        if ($prefix) {
            $query->where('prefix', 'like', '%' . $prefix . '%');
        }
        if ($status !== null) {
            $query->where('status', $status);
        }

        // Paginate the results
        $coupons = $query->paginate(10); // You can adjust the number of items per page as needed
        // Get distinct prefixes based on the filters applied


        // return view('Admin.coupon.list', compact('coupons'));
        return view('administrator.dashboard.coupon_list', compact('coupons'));
    }

    public function lists(Request $request)
    {
        // $coupons = CouponCode::orderByDesc('created_at')->where('status', 1)->with('corporate')->get();
        $coupons = CouponCode::orderByDesc('created_at')->where('status', 1)->with('corporate')->paginate(10);

        $counts = '';
        $appliedCount = '';
        $prefix = '';
        $codeValue = '';
        $issuedCount = '';

        if ($request->method() == 'POST') {
            $name = $request->input('name');

            $filteredCoupons = $coupons->where('prefix', $name);

            $counts = $filteredCoupons->count();

            $appliedCount = $filteredCoupons->where('is_applied', 1)->count();

            $issuedCount = $filteredCoupons->where('is_issued', 1)->count();

            $codeType = $filteredCoupons->first()?->valueType;

            $prefix = $filteredCoupons->first()?->prefix;

            $codeValue = $filteredCoupons->first()?->value;

            $codeValue = $codeValue ? $codeValue . '  ' . ($codeType == 'amount' ? 'Rs.' : '%') : '';

            return response()->json(['issuedCount' => $issuedCount, 'coupons' => $filteredCoupons, 'counts' => $counts, 'appliedCount' => $appliedCount, 'codeValue' => $codeValue, 'prefix' => $prefix]);
        }
        // $counts = $coupons->count();

        // $appliedCount = $coupons->where('is_applied', 1)->count();

        // return print_r($coupons->toArray());

        return view('administrator.dashboard.coupon_list', compact('issuedCount', 'coupons', 'prefix', 'codeValue'));
    }

    public function saveCoupon(Request $request)
    {
   
        $request->validate([
            'prefix' => 'required | unique:coupon_codes',
            'name' => 'required|unique:coupon_codes',
            'number_of_coupons' => 'required|integer|min:1',
            'discount_type' => 'required',
            'discount_value' => 'required|numeric|min:0',
            'coupon_type' => 'nullable',
            'description' => 'nullable',
        ]);
        // Generate coupon codes
        $prefix = $request->input('prefix');
        $coupon_type = $request->input('coupon_type') ?? null;
        $description = $request->input('description') ?? null;
        $digit = 8; // The length of the random string
        $value = $request->input('discount_value');
        $valueType = $request->input('discount_type');
        $name = $request->input('name');
        $numberOfCoupons = $request->input('number_of_coupons') ?? 1;

        $coupons = [];
        for ($i = 0; $i < $numberOfCoupons; $i++) {
            // Generate a random coupon code
            $randomString = Str::random($digit);
            $randomNumber = mt_rand(1000, 9999); // Generate a random 4-digit number
            $couponCode = $prefix . $randomString . $randomNumber;

            // Check if the coupon code already exists in the database
            while (DB::table('coupon_codes')->where('couponcode', $couponCode)->exists()) {
                $randomString = Str::random($digit);
                $randomNumber = mt_rand(1000, 9999); // Generate a random 4-digit number
                $couponCode = $prefix . '-' . $randomString . $randomNumber;
            }

            // Store coupon details in an array
            $coupons[] = [
                'name' => $name,
                'couponcode' => $couponCode,
                'coupon_type' => $coupon_type,
                'description' => $description,
                'prefix' => $prefix,
                'digit' => $digit,
                'value' => $value,
                'valueType' => $valueType,
                'status' => 1, // Assuming the default status is active
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert coupon codes into the database
        DB::table('coupon_codes')->insert($coupons);

        return redirect()->route('coupon.lists')->with('success', 'Coupons added successfully.');
        // Return a response or redirect as needed
    }



    /**
     * Show the form for creating a new resource.
     */
    public function createCoupon()
    {
        return view('administrator.dashboard.geenrate_coupon_code');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CouponCode $couponCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CouponCode $couponCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CouponCode $couponCode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CouponCode $couponCode)
    {
        //
    }
}
