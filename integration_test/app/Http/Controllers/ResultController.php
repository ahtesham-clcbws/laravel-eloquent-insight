<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubcategorySubject;
use App\Models\SubjectModel;
use App\Models\UserRegistration;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function newresult()
    {
        $student = UserRegistration::all();
        $category = Category::all();
        $subject = SubjectModel::all();
        $sub_Subject = SubcategorySubject::all();

        return view('Admin.result.newresult', compact('student', 'category','subject','sub_subject'));
    }


}
