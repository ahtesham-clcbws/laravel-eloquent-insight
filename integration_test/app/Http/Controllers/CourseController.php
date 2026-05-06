<?php

namespace App\Http\Controllers;

use App\Models\BenefitsModel;
use App\Models\Category;
use App\Models\CourseDetailsModel;
use App\Models\EducationType;
use App\Models\EProspectusModel;
use App\Models\OurContributor;
use App\Models\OurJourney;
use App\Models\SubCategoryModel;
use App\Models\SubcategorySubject;
use App\Models\SubjectModel;
use App\Models\SubjectSubcategory;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function subjectssubcategory()
    {

        return view('Admin.courses.subjectsubcategory');
    }

    public function savesubjectsubcategory(Request $request)
    {
        $request->validate([
            'subject_subcategory_name' => 'required',
            'subject_id' => 'required'
        ]);
        $subject_id = $request->subject_id;
        $fullname = $request->subject_subcategory_name;
        $subecject = new SubjectSubcategory();
        $subecject->subject_id = $subject_id;
        $subecject->subject_sub_name = $fullname;
        $subecject->save();
        return redirect()->back()->with('success', 'Subject Added successfully.');
    }

    public function savesubjects(Request $request)
    {
        $subcategoryId = $request->input('subcategoryId');
        $categoryId = $request->input('categoryId');
        $selectedSubjectIds = $request->input('subject_ids'); // Correct input name

        // Check if any subjects were selected
        if (!empty($selectedSubjectIds)) {
            $subcategory = SubCategoryModel::find($subcategoryId);
            if ($subcategory) {
                // Iterate through each selected subject and save it to the database
                foreach ($selectedSubjectIds as $subjectId) {
                    $subcategorySubject = new SubcategorySubject();
                    $subcategorySubject->subcategory_id = $subcategoryId;
                    $subcategorySubject->subject_id = $subjectId;
                    $subcategorySubject->category_id = $categoryId;
                    $subcategorySubject->save();
                }
                return redirect()->back()->with('success', 'Subject Added successfully.');
            } else {
                return response()->json(['error' => 'Subcategory not found'], 404);
            }
        } else {
            return response()->json(['error' => 'No subjects selected'], 400);
        }
    }
    //**************************** Course List */
    public function classList()
    {
        $categoriesWithSubcategories = Category::with('subcategories')->get();
        $subjectList = SubjectModel::all();

        // Fetch subcategories with subjects' names
        $subcategoriesWithSubjects = [];
        $subcategories = SubCategoryModel::with('subjects')->get();
        foreach ($subcategories as $subcategory) {
            $subjectIds = SubcategorySubject::where('subcategory_id', $subcategory->id)->pluck('subject_id')->toArray();
            $subjects = $subjectList->whereIn('id', $subjectIds)->pluck('subjectName')->toArray();
            $subcategoriesWithSubjects[$subcategory->id] = $subjects;
        }
        return view('Admin.courses.classlist', compact('categoriesWithSubcategories', 'subjectList', 'subcategoriesWithSubjects'));
    }


    //**************************** End of Course List */


    //******************************** Category Start *****************************
    public function deleteCategory($id)
    {
        // Find the category by its ID
        $category = Category::find($id);

        // Check if the category exists
        if (!$category) {
            return redirect()->back()->with('error', 'Category not found.');
        }

        // Delete the category
        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully.');
    }

    public function category($category = null)
    {
        $categories = Category::all();
        if ($category) {
            $category = $categories->where('id', $category)->first();
            // dd($category);
        }
        return view('administrator.courses.sholarship_category', ['categories' => $categories, 'category' => $category]);
    }

    public function savecategory(Request $request, $category = null)
    {
        // Validate the request data
        $id  = null;
        $remarks = null;
        $imgReq = 'required';
        $image = null;
        if ($category) {
            $category = Category::find($category);
            if ($category->image) {
                $imgReq = 'nullable';
            }
            $id = 'name,' . $category->id;
            $remarks = 'remark,' . $category->id;
        }

        // Validate the request data
        $request->validate([
            'name' => "required|unique:category,$id|string",
            'remarks' => "nullable|unique:category,$remarks|string",
            'image' => "$imgReq|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        if ($request->hasFile('image')) {
            $image = moveFile('upload/student', $request->image);
        }
        // Create a new Category instance

        $category = $category ?? new Category;

        $category->name = $request->name;
        $category->remark = $request->remark;
        $image ? $category->image = $image : ''; // Save the image path to the database
        $category->save();

        // Redirect back or return a response
        return redirect()->back()->with('success', 'Category added successfully!');
    }


    //******************************** Category End *****************************

    //******************************** Sub Category Start *****************************
    public function subcategorybyId($id)
    {
        $category = Category::find($id);
        $subCategories = SubCategoryModel::where('category_id', $id)->get();
        if (!$category) {
            return redirect()->back()->with('error', 'Category not found.');
        }

        return view('Admin.courses.subcategory_by_id', ['category' => $category, 'subCategory' => $subCategories]);
    }

    public function savesubcategory(Request $request)
    {
        // Validate the request data
        $request->validate([
            'subcategory' => 'required|string',
            'category_id' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust file types and size as needed
        ]);

        // Get the category name from the request
        $subcategoryName = $request->input('subcategory');
        $category_id = $request->input('category_id');

        // Save the image file
        $imagePath = $request->file('image')->store('category_images', 'public');

        // Create a new Category instance
        $subcategory = new SubCategoryModel();
        $subcategory->subcategory_name = $subcategoryName;
        $subcategory->category_id = $category_id;
        $subcategory->image = $imagePath; // Save the image path to the database
        $subcategory->save();

        // Redirect back or return a response
        return redirect()->back()->with('success', 'Category added successfully!');
    }


    public function subcategoryall()
    {
        $subcategories = SubCategoryModel::all();
        return view('Admin.courses.subcategoryall', ['subcategories' => $subcategories]);
    }

    public function subcategory(Request $req)
    {
        return view('Admin.courses.subcategory');
    }
    //******************************** Sub Category Start *****************************


    //***************************** Course Start ************************************** */
    public function courseList()
    {
        $courses = CourseDetailsModel::all();
        return view('administrator.Home.courseList', ['courses' => $courses]);
    }

    public function courseDelete(Request $request, CourseDetailsModel $courseDetailsModel)
    {
        try {
            $courseDetailsModel->delete();
            return back()->with('success', 'Deleted Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'Not Deleted');
        }
    }
    public function coursesubmit(Request $request, CourseDetailsModel $courseDetailsModel)
    {

        if ($request->isMethod('POST')) {
            // Validate the form data
            $request->validate([
                'overview' => 'required',
                'course_name' => 'required',
                'scholarship_category' => 'required',
            ]);

            // Handle file uploads
            $courseDetails = $request->id ? CourseDetailsModel::find($request->id) : new CourseDetailsModel;

            $notificationFilePath = $courseDetails->notification_file_path;
            if ($request->hasFile('notification_file')) {
                $notificationFilePath = moveFile('home/course', $request->file('notification_file'));
            }

            $examDetailsFilePath = $courseDetails->exam_details_file_path;
            if ($request->hasFile('exam_details_file')) {
                $examDetailsFilePath = moveFile('home/course', $request->file('exam_details_file'));
            }

            $prospectusFilePath = $courseDetails->prospectus;
            if ($request->hasFile('prospectus')) {
                $prospectusFilePath = moveFile('home/course', $request->file('prospectus'));
            }

            $courseLogo = $courseDetails->course_logo;
            if ($request->hasFile('course_logo')) {
                $courseLogo = moveFile('home/course', $request->file('course_logo'));
            }

            $featureImage = $courseDetails->featured_image;
            if ($request->hasFile('featured_image')) {
                $featureImage = moveFile('home/course', $request->file('featured_image'));
            }
            // Create a new CourseDetails instance
            // (Already fetched above to preserve files)
            $courseDetails->overview = $request->input('overview');
            $courseDetails->scholarship_category = $request->input('scholarship_category');
            $courseDetails->title = $request->input('course_name');
            $courseDetails->course_full_name = $request->input('course_full_name');
            $courseDetails->otherdetails = $request->input('otherdetails');
            $courseDetails->notification = $request->input('notification');
            $courseDetails->registration = $request->input('registration');
            $courseDetails->exam_date = $request->input('exam_Date');
            $courseDetails->exam_mode = $request->input('exam_mode');
            $courseDetails->exam_pattern = $request->input('exam_pattern');
            $courseDetails->vacancies = $request->input('vacancies');
            $courseDetails->pay_scale = $request->input('pay_scale');
            $courseDetails->age_criteria = $request->input('age_criteria');
            $courseDetails->eligibility = $request->input('eligibility');
            $courseDetails->official_site = $request->input('official_site');
            $courseDetails->notification_file_path = $notificationFilePath;
            $courseDetails->exam_details_file_path = $examDetailsFilePath;
            $courseDetails->prospectus = $prospectusFilePath;
            $courseDetails->course_logo = $courseLogo;
            $courseDetails->featured_image = $featureImage;

            // Save the CourseDetails instance to the database
            $courseDetails->save();

            // Redirect back with a success message
            return redirect()->route('admin.home.courseList')->with('success', 'Course details submitted successfully!');
        }

        return view('administrator.Home.courses', ['course' => $courseDetailsModel]);
    }

    public function toggleFeatured(Request $request)
    {
        if (isset($request->type) && $request->type == 'educationType') {
            $model = EducationType::findOrFail($request->id);
            $model->is_featured = $request->is_featured;
            $model->save();
            $message = 'Scholarship Category';
        }else if (isset($request->type) && $request->type == 'ourJourney') {
            $model = OurJourney::findOrFail($request->id);
            $model->is_featured = $request->is_featured;
            $model->save();
            $message = 'Our Jouney ';
        }else if (isset($request->type) && $request->type == 'ourContributor') {
            $model = OurContributor::findOrFail($request->id);
            $model->is_featured = $request->is_featured;
            $model->save();
            $message = 'Our Jouney ';
        } else if (isset($request->type) && $request->type == 'ourBenefit') {
            $model = BenefitsModel::findOrFail($request->id);
            $model->is_featured = $request->is_featured;
            $model->save();
            $message = 'Our Jouney ';
        } else {
            $course = CourseDetailsModel::findOrFail($request->id);
            $course->is_featured = $request->is_featured;
            $course->save();
            $message = 'course';
        }



        return response()->json(['status'=>$request->is_featured == 1 ? true :false ,'message' => " $message featured status updated successfully."]);
    }

    public function toggleStatus(Request $request)
    {
        $course = CourseDetailsModel::findOrFail($request->id);
        $course->status = $request->status;
        $course->save();

        return response()->json(['status'=>$request->status == 1 ? true :false ,'message' => 'Course Status updated successfully.']);
    }

    public function pospectToggleStatus(Request $request)
    {
        $course = EProspectusModel::findOrFail($request->id);
        $course->status = $request->status;
        $course->save();

        return response()->json(['status'=>$request->status == 1 ? true :false ,'message' => 'E-Prospectus Status updated successfully.']);
    }
    //***************************** End of Course *************************************** */


    //******************************* Subject Start ******************************************* */

    public function subjects()
    {
        $subjectList = SubjectModel::all();
        return view('Admin.courses.subjectList', ['subject' => $subjectList]);
    }

    public function deleteSubject($id)
    {
        $subjectId = SubjectModel::find($id);
        if (!$subjectId) {
            return redirect()->back()->with('error', 'Subject Not Found');
        } else {
            $subjectId->delete();
            return redirect()->back()->with('success', 'Subject Deleted');
        }
    }

    public function savesubject(Request $request)
    {
        // Validate the request data
        $request->validate([
            'subjectName' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust file types and size as needed
        ]);

        // Get the category name from the request
        $subjectName = $request->input('subjectName');
        // Save the image file
        $imagePath = $request->file('image')->store('category_images', 'public');

        // Create a new Category instance
        $subject = new SubjectModel();
        $subject->subjectName = $subjectName;
        $subject->image = $imagePath; // Save the image path to the database
        $subject->save();

        // Redirect back or return a response
        return redirect()->back()->with('success', 'Category added successfully!');
    }


    //********************************* End of Subject ********************************************* */




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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
