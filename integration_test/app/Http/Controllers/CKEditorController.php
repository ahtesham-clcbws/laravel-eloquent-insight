<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fileName = time().'.'.$request->image->extension();  
        $request->image->move(public_path('uploads'), $fileName);

        $imageUrl = asset('uploads/'.$fileName);

        return back()->with('image_url', $imageUrl);
    }
}
