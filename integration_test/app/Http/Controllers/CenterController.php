<?php

namespace App\Http\Controllers;

use App\Models\Center;
use Illuminate\Http\Request;

class CenterController extends Controller
{
    public function list()
    {
        $centers=Center::all();
        return view('admin.center.index',compact('centers'));
    }

    public function addCenter(Request $request)
    {
        $validatedData = $request->validate([
            'center_name' => 'required',
            'add_lat' => 'required',
            'add_long' => 'required',
            'add_contact' => 'required',
            'add_district' => 'required',
            'add_state' => 'required',
            'center_address' => 'required',
        ]);

        $center = new Center();
        $center->center_name = $request->center_name;
        $center->add_lat = $request->add_lat;
        $center->add_long = $request->add_long;
        $center->add_contact = $request->add_contact;
        $center->add_district = $request->add_district;
        $center->add_state = $request->add_state;
        $center->center_address = $request->center_address;

        // Save the Center instance
        $center->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Center created successfully!');
    }

    public function manage()
    {

    }
}
