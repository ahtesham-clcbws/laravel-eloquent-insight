<?php

namespace App\Http\Controllers;

use App\Models\BlognewsModel;
use App\Models\NotificationModel;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function blognews($id = null)
    {
        $blog = BlognewsModel::all();
        $editBlog = null;
        if ($id) {
            $editBlog = BlognewsModel::find($id);
        }
        return view('administrator.Home.blognews', ['blog' => $blog, 'editBlog' => $editBlog]);
    }

    public function notification()
    {
        $notification = NotificationModel::all();
        return view('administrator.Home.notification', ['notification' => $notification]);
    }

    public function notificationSave(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'details' => 'required',
        ]);
        try {
            $notification = new NotificationModel();
            $notification->forceFill($validatedData);
            $notification->save();
            return redirect()->back()->with('success', 'Notification added successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to save!');
        }
    }

    public function notificationDelete($id)
    {
        $notification = NotificationModel::find($id);
        if (!$notification) {
            return redirect()->back()->with('error', 'Notification not found');
        } else {
            $notification->delete();
            return redirect()->back()->with('error', 'Notification Deleted');
        }
    }

    public function blogSave(Request $request)
    {
        $request->validate([
            'image' => $request->id ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $blog = $request->id ? BlognewsModel::find($request->id) : new BlognewsModel();
        
        $imagePath = $blog->image;
        // Save the image file if uploaded
        if ($request->hasFile('image')) {
            $imagePath = moveFile('news', $request->file('image'));
        }

        $blog->title = $request->title;
        $blog->details = $request->details;
        $blog->image = $imagePath;
        $blog->save();

        $message = $request->id ? 'Blog updated successfully!' : 'Blog added successfully!';
        return redirect()->route('news.blognews')->with('success', $message);
    }

    public function blogDelete($id)
    {
        $blog = BlognewsModel::find($id);
        if (!$blog) {
            return redirect()->back()->with('error', 'Blog not found');
        } else {
            $blog->delete();
            return redirect()->back()->with('error', 'Blog Deleted');
        }
    }
}
