<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTestRequest;
use App\Http\Requests\UpdateTestRequest;
use App\Models\ContactInfo;
use App\Models\Corporate;
use App\Models\Student;
use App\Models\Test;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $institutes = Corporate::where('is_approved', 1)
        //     ->where('signup_approved', 1)
        //     ->whereNotNull('signup_at')
        //     ->orderBy('id', 'desc')
        //     ->paginate(1)->toArray();

        // $cities = Corporate::select('city')->groupBy('city')->pluck('city')->toArray();

        // $user = User::find(1);
        // $user->name = 'Ahtesham-'.time();
        // $user->password = Hash::make('123456789');
        // $user->mobile = 9810763314;
        // return print_r($cities);
        // return view('components.mail.template');

        $student = Student::find(10);
        $student_data = [
            'name' => $student->name,
            'city' => $student->district?->name ? $student->district->name : null,
            'application_no' => $student->latestStudentCode->application_code,
            'mobile' => $student->mobile,
            'email' => $student->email,
        ];

        $institute = Corporate::find(3);
        $institute_data = [
            'name' => $institute->name,
            'institute_name' => $institute->institute_name,
            'email' => $institute->email,
            'city' => $institute->district?->name ? $institute->district->name : null,
            'mobile' => $institute->phone,
            'code' => $institute->institude_code,
        ];
        
        $contactInfo = ContactInfo::find(486);
        $contact_info_data = [
            'name' => $contactInfo->fullname,
            'email' => $contactInfo->email,
            'city' => $contactInfo->city
        ];

        $data = $contact_info_data;
        return view('mail.emails.CorporateRequestSuccessfullyReceived', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTestRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Test $test)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Test $test)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTestRequest $request, Test $test)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Test $test)
    {
        //
    }
}
