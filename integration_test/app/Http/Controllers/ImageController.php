<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $imageUrl = null;
        if ($request->isMethod('POST')) {
            $validated = $request->validate([
                'uploadfiles' => 'required|mimes:pdf,jpeg,png,jpg,gif|max:2048',
            ]);

            $hasUrl = hash_file('sha256', $request->file('uploadfiles'));
            $image = Image::where('has_url', $hasUrl)->first();

            if (is_null($image)) {
                $files =  $request->file('uploadfiles');
                
                $name = rand(10000, 99999) . '-' . date('His') . preg_replace('/[^\w\-\.]/', '', $files->getClientOriginalName());
                $files->move(public_path('uploads'), $name);

                $imageUrl = asset('uploads/' . $name);

                $image = new Image();
                $image->url =  $imageUrl;
                $image->has_url = $hasUrl;
                $image->image_name = $name;
                $image->save();

                $image->url =  $imageUrl . '?' . $image->id;
                $image->save();

            }
            
            $imageUrl = $image->url;
        }

        $images = Image::orderBy('created_at', 'DESC')->limit(25)->get();

        return view('administrator.uploads.images_upload', compact('images','imageUrl'));
    }
    public function uploadDelete($id)
    {
        $image = Image::find($id);
        if (!$image) {
            return redirect()->back()->with('error', 'Image not found.');
        }

        $image->delete();

        return redirect()->back()->with('error', 'Image deleted successfully.');
    }
}
