<?php

namespace App\Http\Controllers;

use App\Models\BoardAgencyStateModel;
use App\Models\Category;
use App\Models\ClassGoupExamModel;
use App\Models\CourseDetailsModel;
use App\Models\EducationType;
use App\Models\Gn_AssignClassGroupExamName;
use App\Models\Gn_ClassSubject;
use App\Models\Gn_DisplayClassGroupExamName;
use App\Models\Gn_DisplayClassSubject;
use App\Models\Gn_DisplayExamAgencyBoardUniversity;
use App\Models\Gn_DisplayOtherExamClassDetail;
use App\Models\Gn_DisplaySubjectPart;
use App\Models\Gn_DisplaySubjectPartChapter;
use App\Models\Gn_EducationClassExamAgencyBoardUniversity;
use App\Models\Gn_OtherExamClassDetailModel;
use App\Models\Gn_StudentTestAttempt;
use App\Models\Gn_SubjectPartLessionNew;
use App\Models\GnResultSubjectMapping;
use App\Models\OtherCategoryClass;
use App\Models\QuestionBankModel;
use App\Models\SubCategoryModel;
use App\Models\SubcategorySubject;
use App\Models\Subject;
use App\Models\SubjectModel;
use App\Models\SubjectPaperDetail;
use App\Models\SubjectPart;
use App\Models\SubjectPartLesson;
use App\Models\SubjectSubcategory;
use App\Models\TestModal;
use App\Models\TestQuestions;
use App\Models\TestSections;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamsController extends Controller
{
    protected $data;
    protected $insert_data;
    protected $diff_data;
    public function __construct()
    {
        $this->data = array();

        $this->data['educations']       = EducationType::get();

        $this->data['pagename'] = 'Add Questions';
        $this->data['test_sections'] = ['1', '2', '3', '4', '5'];
        $this->data['difficulty_level'] = ['25', '35', '40', '50', '60', '70', '75', '80', '90', '100'];
    }
    public function index(Request $req)
    {
        // $search_value = 'one';
        // $testTableData = TestModal::select(['id', 'title', 'test_type', 'sections', 'total_questions', 'questions_submitted','questions_approved','reviewed','reviewed_status','published_status','published'])
        // ->where("title", "like", "%".$search_value."%")->orderBy('id', 'desc')->skip(0)->take(10)->get();
        // $count = TestModal::count();
        // $json_data = array(
        //     // "draw" => intval($params['draw']),
        //     "recordsTotal" => $count,
        //     "recordsFiltered" => $count,
        //     "data" => $testTableData   // total data array
        // );
        // return print_r($json_data);
        $this->data['pagename'] = 'All Tests';
        if ($req->isMethod('post')) {
            $params['draw'] = $_REQUEST['draw'];
            $start = $_REQUEST['start'];
            $length = $_REQUEST['length'];
            /* If we pass any extra data in request from ajax */
            //$value1 = isset($_REQUEST['key1'])?$_REQUEST['key1']:"";

            /* Value we will get from typing in search */
            $search_value = $_REQUEST['search']['value'];

            // if (!empty($search_value)) {
            //     $testTableData = TestModal::select(['id', 'title', 'test_type', 'sections', 'total_questions', 'questions_submitted', 'questions_approved', 'reviewed', 'reviewed_status', 'published'])
            //         ->where("title", "like", "%" . $search_value . "%")
            //         ->orderBy('id', 'desc')->skip($start)->take($length)->get();
            //     $count = TestModal::where("title", "like", "%" . $search_value . "%")->count();
            // } else {
            //     $testTableData = TestModal::select(['id', 'title', 'test_type', 'sections', 'total_questions', 'questions_submitted', 'questions_approved', 'reviewed', 'reviewed_status', 'published'])->orderBy('id', 'desc')->skip($start)->take($length)->get();
            //     $count = TestModal::count();
            // }
            $test_cat = isset($req->test_cat) ? $req->test_cat : '';
            $published = isset($req->published) ? $req->published : '';

            if (!empty($search_value)) {
                // dd($search_value);
                $testTableData = TestModal::select([
                    'test.id as id',
                    'test.user_id',
                    'title',
                    'sections',
                    'total_questions',
                    'questions_submitted',
                    'education_type_id',
                    'test_cat',
                    'price',
                    'test_type',
                    'questions_approved',
                    'reviewed',
                    'reviewed_status',
                    'published',
                    'test.created_at as created_at',
                    'education_type_child_id',
                    'published_status',
                    'featured',
                    'users.name as username',
                    'franchise_details.institute_name as institute_name'
                ])
                    ->leftJoin('users', 'users.id', 'test.user_id')
                    ->leftJoin('franchise_details', 'franchise_details.user_id', 'users.id')
                    ->where("title", "like", "%" . $search_value . "%")
                    ->orderBy('id', 'desc');
                $count = $testTableData->count();
            } else {
                $testTableData = TestModal::select([
                    'test.id as id',
                    'test.user_id',
                    'title',
                    'sections',
                    'total_questions',
                    'questions_submitted',
                    'education_type_id',
                    'test_cat',
                    'price',
                    'test_type',
                    'questions_approved',
                    'reviewed',
                    'reviewed_status',
                    'published',
                    'test.created_at as created_at',
                    'education_type_child_id',
                    'published_status',
                    'featured',
                    'users.name as username',
                    'franchise_details.institute_name as institute_name'
                ])
                    ->leftJoin('users', 'users.id', 'test.user_id')
                    ->leftJoin('franchise_details', 'franchise_details.user_id', 'users.id')
                    ->orderBy('id', 'desc');
                $count = TestModal::count();
            }

            if (!empty($test_cat)) {
                $testTableData =  $testTableData->where('test_cat', $test_cat);
                $count = $testTableData->count();
            }

            if (!empty($published)) {
                $publish_id = 0;
                if ($published == 'true') {
                    $publish_id = 1;
                } else if ($published == 'false') {
                    $publish_id = 0;
                }
                $testTableData =  $testTableData->where('published', $publish_id);
                $count = $testTableData->count();
            }

            $testTableData = $testTableData->skip($start)->take($length)->get();

            foreach ($testTableData as $key => $testData) {
                // if ($testData['test_type'] == '1') {
                //     // $testTableData[$key]['type_text'] = 'Normal Test';
                // }
                // if ($testData['test_type'] == '2') {
                //     // $testTableData[$key]['type_text'] = 'Schedule Test';
                // }
                // if ($testData['test_type'] == '3') {
                //     // $testTableData[$key]['type_text'] = 'Practice Test';
                // }
                // dd();
                $total_questions = $testData['total_questions'];
                $question_added = $testData->getQuestions()->wherePivot('deleted_at', '=', NULL)->count();

                $testTableData[$key]['total_questions'] = $testData['total_questions'] . ' / ' . $question_added;
                $status = '';

                // dd($testData->getQuestions()->wherePivot('deleted_at','=',NULL)->count());
                // == 'true' ? $testData->getQuestions()->wherePivot('deleted_at','=',NULL)->count()  : '0'
                $featured = '';
                // $questionButton = '';
                if ($total_questions == 0) {
                    $status = '<span class="badge bg-warning text-dark">Awaiting Sections</span>';
                } else {
                    // $testData->getQuestions()->wherePivot('deleted_at','=',NULL)->count()
                    // if ($testData['total_questions'] !== $testData['questions_submitted'] || $testData['total_questions'] < $testData['questions_submitted']) {
                    if ($total_questions != $testData->getQuestions()->wherePivot('deleted_at', '=', NULL)->count() || $total_questions < $testData->getQuestions()->wherePivot('deleted_at', '=', NULL)->count()) {
                        $status = '<span class="badge bg-warning text-dark">Awaiting Questions</span>';
                        // $questionButton = '<a href="' . route('administrator.dashboard_test_section', [$testData['id']]) . '" title="Test Questions"><i class="bi bi-journal-text text-primary me-2"></i></a>';
                        // $questionButton = $sectionButtons;
                    } else {
                        if ($testData['reviewed']) {
                            if ($testData['reviewed_status'] == 'approved') {
                                $status = '<span class="badge bg-success">Approved</span>';
                            }
                            if ($testData['reviewed_status'] == 'rejected') {
                                $status = '<span class="badge bg-danger">Rejected</span>';
                            }
                            if ($testData['reviewed_status'] == 'onhold') {
                                $status = '<span class="badge bg-warning text-dark">Hold Review</span>';
                            }
                        } else {
                            $status = '<a href="' . route('administrator.dashboard_publish_test_exam', [$testData['id']]) . '"><span class="badge bg-primary">Publish Test</span></a>';
                        }
                        // if ($testData['published_status']) {
                        //     $status = '<span class="badge bg-primary">Published</span>';
                        // }
                        if ($testData['published'] == 1) {
                            $status = '<a href="' . route('administrator.dashboard_publish_test_exam', [$testData['id']]) . '"><span class="badge bg-success">Published</span></a>';
                            if ($testData['featured'] == 0) {
                                $featured = '<button data-id="' . $testData['id'] . '" class="btn btn-sm btn-warning" onclick="handleTestFeature(' . $testData['id'] . ')">UnFeatured</button>';
                            } else if ($testData['featured'] == 1) {
                                $featured = '<button data-id="' . $testData['id'] . '" class="btn btn-sm btn-success" onclick="handleTestFeature(' . $testData['id'] . ')">Featured</button>';
                            }
                        }
                        // if ($total_questions == $testData->getQuestions()->wherePivot('deleted_at','=',NULL)->count()) {
                        //     $status = '<a href="'.route('administrator.dashboard_publish_test_exam', [$testData['id']]).'"><span class="badge bg-primary">Published</span></a>';
                        // }
                    }
                }
                $sectionButtons = '';
                if ($testData['sections']) {
                    $sectionsX = TestSections::where('test_id', $testData['id'])->get();
                    $sectionUrl = '';
                    $sectionButtons = '';
                    // return $question_added;
                    foreach ($sectionsX as $keyX => $sectionX) {
                        if ($sectionX['subject'] != NULL && $sectionX['subject_part'] != NULL && $sectionX['subject_part_lesson'] != NULL && $sectionX['number_of_questions'] != NULL) {
                            $sectionUrl = route('administrator.dashboard_test_section_question', [$testData['id'], $sectionX['id']]);
                            $sectionButtons .= '<a href="' . $sectionUrl . '" title="Section ' . ($keyX + 1) . ' Questions"><i class="bi bi-journal-text text-primary me-2"></i></a>';
                        } else {
                            $sectionUrl = '';
                            $sectionButtons .= '<a href="javascript:void(0)" onclick="noSection()"><i class="bi bi-journal-text text-primary me-2"></i></a>';
                        }
                    }
                } else {
                    $sectionButtons = '0 Sections';
                }
                $testData['sections'] = $sectionButtons;

                // dd($testData->creater);
                $testTableData[$key]['status'] = $status;
                $testTableData[$key]['featured'] = $featured;
                $testTableData[$key]['created_by'] = $testData['institute_name'] != NULL ? $testData['institute_name'] : Auth::user()->name;
                $testTableData[$key]['created_date'] = date('d-m-Y', strtotime($testData->created_at));
                $testTableData[$key]['class_name'] = $testData->EducationClass != null ? $testData->EducationClass->name : '';
                $testTableData[$key]['class_type'] = $testData->EducationType != null ? $testData->EducationType->name : '';
                $testTableData[$key]['test_cat'] = $testData->getTestCat ? $testData->getTestCat->cat_name : '';
                $testTableData[$key]['test_type'] = $testData->test_type;
                $testTableData[$key]['test_fees'] = isset($testData->price) ? $testData->price : '';

                // <a href="' . route('administrator.dashboard_test_sections', [$testData['id']]) . '" title="Test Sections"><i class="bi bi-columns-gap text-primary me-2"></i></a>
                $actionsHtml = '<a href="' . route('administrator.dashboard_update_test_exam', [$testData['id']]) . '" title="Edit Test"><i class="bi bi-pencil-square text-success me-2"></i></a>
                <a href="javascript:void(0);" title="Delete Test"><i class="bi bi-trash2-fill text-danger me-2" onclick="deleteTest(' . $testData['id'] . ')"></i></a>';
                // $actionsHtml = $questionButton.'<a href="' . route('administrator.dashboard_test_sections', [$testData['id']]) . '" title="Test Sections"><i class="bi bi-columns-gap text-primary me-2"></i></a>
                // <a href="' . route('administrator.dashboard_update_test_exam', [$testData['id']]) . '" title="Edit Test"><i class="bi bi-pencil-square text-success me-2"></i></a>';
                $testTableData[$key]['actions'] = $actionsHtml;
            }

            $json_data = array(
                "draw"              => intval($params['draw']),
                "recordsTotal"      => $count,
                "recordsFiltered"   => $count,
                "data"              => $testTableData   // total data array
            );

            return json_encode($json_data);
        }
        // dd($this->data);
        return view('Dashboard/Admin/Exam/teststable')->with('data', $this->data);
    }

    public function gettestpackage(Request $request)
    {
        $id = $request->input('id');
        $result = DB::table('gn__package_plans')
            ->select('*')
            ->where('package_type', '=', '$id')
            ->get();

        $options = ''; // Initialize an empty string to store the HTML options

        foreach ($result as $row) {
            $options .= "<option value='{$row->id}'>{$row->plan_name}</option>"; // Generate an HTML option for each row in the result
        }

        $select = "<select class='form-select form-select-sm select2' multiple id='package' name='package[]' required>{$options}</select>";

        return response($select);
    }

    public function saveTest(Request $req, $test_id = 0)
    {

        $this->data['marks'] = ['1', '2', '3', '4'];
        $this->data['negative_marks'] = [
            ['id' => '0', 'name' => 'No Negative Marking'],
            ['id' => '0.25', 'name' => '-0.25%'],
            ['id' => '0.33', 'name' => '-0.33%'],
            ['id' => '0.5', 'name' => '-0.50%'],
        ];
        $this->data['durations'] = [
            ['id' => 10, 'name' => '10 Minute'],
            ['id' => 20, 'name' => '20 Minute'],
            ['id' => 30, 'name' => '30 Minute'],
            ['id' => 40, 'name' => '40 Minute'],
            ['id' => 45, 'name' => '45 Minute'],
            ['id' => 50, 'name' => '50 Minute'],
            ['id' => 60, 'name' => '1 Hour'],
            ['id' => 70, 'name' => '1 Hour / 10 Minute'],
            ['id' => 75, 'name' => '1 Hour / 15 Minute'],
            ['id' => 80, 'name' => '1 Hour / 20 Minute'],
            ['id' => 90, 'name' => '1 Hour / 30 Minute'],
            ['id' => 100, 'name' => '1 Hour / 40 Minute'],
            ['id' => 105, 'name' => '1 Hour / 45 Minute'],
            ['id' => 110, 'name' => '1 Hour / 50 Minute'],
            ['id' => 120, 'name' => '2 Hour'],
            ['id' => 130, 'name' => '2 Hour / 10 Minute'],
            ['id' => 135, 'name' => '2 Hour / 15 Minute'],
            ['id' => 140, 'name' => '2 Hour / 20 Minute'],
            ['id' => 150, 'name' => '2 Hour / 30 Minute'],
            ['id' => 160, 'name' => '2 Hour / 40 Minute'],
            ['id' => 165, 'name' => '2 Hour / 45 Minute'],
            ['id' => 170, 'name' => '2 Hour / 50 Minute'],
            ['id' => 180, 'name' => '3 Hour'],
        ];
        $this->data['pagename'] = 'Add / Update Test';

        if ($test_id !== 0) {
            $test = TestModal::find($test_id);
            $this->data['show_section'] = 1;
        } else {
            $test = new TestModal();
            $this->data['show_section'] = 0;
        }
        $this->data['test'] = $test;
        $this->data['test_cat']    = DB::table('test_cat')->get();
        $this->data['group_classes']    = ClassGoupExamModel::where('education_type_id', $this->data['test']->education_type_id)->get();
        $this->data['agency_boards']    = Gn_EducationClassExamAgencyBoardUniversity::where('education_type_id', $this->data['test']->education_type_id)->where('classes_group_exams_id', $this->data['test']->education_type_child_id)->get();
        $this->data['other_exams']      = Gn_OtherExamClassDetailModel::where('education_type_id', $this->data['test']->education_type_id)->where('classes_group_exams_id', $this->data['test']->education_type_child_id)->where('agency_board_university_id', $this->data['test']->board_state_agency)->get();

        if ($req->isMethod('post')) {
            $inputs = $req->all();

            if ($inputs['form_name'] == 'test_form') {
                $testMd = new TestModal();
                if (isset($inputs['id']) && $inputs['id'] > 0) {
                    $testMd = TestModal::find($inputs['id']);
                }
                $testMd->title                      = $inputs['title'];
                $testMd->sub_title                      = $inputs['sub_title'];
                $testMd->test_cat                      = isset($inputs['id']) && $inputs['id'] > 0 ? $testMd->test_cat : NULL;
                $testMd->gn_marks_per_questions     = $inputs['marks_per_questions'];
                $testMd->negative_marks             = $inputs['negative_marks'];
                $testMd->sections                   = $inputs['no_of_sections'];
                $testMd->total_questions            = $inputs['total_questions'];
                if (isset($req->test_image)) {
                    $testMd->test_image = (isset($req->test_image)) ? $req->file('test_image')->storeAs('test_image', preg_replace('/\s+/', '', $req->file('test_image')->getClientOriginalName())) : null;
                }
                $testMd->education_type_id          = $inputs['education_type_id'];
                $testMd->education_type_child_id    = $inputs['class_group_exam_id'];
                $testMd->board_state_agency         = $inputs['exam_agency_board_university_id'];
                $testMd->other_category_class_id    = $inputs['other_exam_class_detail_id'];
                $testMd->test_type                  = isset($inputs['test_type']) ? $inputs['test_type'] : 1;
                $testMd->package                  = isset($inputs['package']) ? implode(',', $inputs['package']) : NULL;
                $testMd->price                  =       isset($inputs['price']) ? $inputs['price'] : null;;
                $testMd->special_remark_1                  = $inputs['special_remark_1'];
                $testMd->special_remark_2                  = $inputs['special_remark_2'];
                $testMd->rating                  = $inputs['rating'];
                $query = $testMd->save();

                if (isset($inputs['id']) && $inputs['id'] > 0) {
                    $section_change = $testMd->getChanges();
                    if (isset($section_change['sections'])) {
                        $sections_delete = TestSections::where('test_id', $testMd->id);
                        $sections_delete->delete();
                        for ($i = 0; $i < $testMd->sections; $i++) {
                            $sectionMd = new TestSections();
                            $sectionMd->test_id = $testMd->id;
                            $sectionMd->save();
                        }
                    }
                } else {
                    for ($i = 0; $i < $testMd->sections; $i++) {
                        $sectionMd = new TestSections();
                        $sectionMd->test_id = $testMd->id;
                        $sectionMd->save();
                    }
                }

                if (isset($inputs['id']) && $inputs['id'] > 0) {
                    // dd($inputs['id']);
                    $this->data['auth_id']  = Auth::id();
                    $testId                 = $inputs['id'];

                    $test                       = TestModal::find($testId);
                    $this->data['subjects']     = Subject::get();
                    $this->data['test']         = $test;

                    $matchThis = ['in_franchise' => '0', 'isAdminAllowed' => '1'];
                    $creators = User::where($matchThis)->where('roles', 'like', '%"creator"%')->orWhere('roles', 'like', '%"manager"%')->orWhere('roles', 'superadmin')->get();

                    foreach ($creators as $key => $creator) {
                        if ($creator['id'] == $this->data['auth_id']) {
                            $creators[$key]['name'] = 'Admin';
                        }
                    }

                    $sections               = TestSections::where('test_id', $testId)->get();
                    $this->data['sections'] = $sections;
                    $this->data['creators'] = $creators;
                    $this->data['pagename'] = 'Test Sections';
                }
                if ($query) {
                    $testId = $testMd->id;
                    return redirect()->route('administrator.dashboard_update_test_exam', [$testId])->withErrors(['testSuccess' => 'Test succesfully added.']);
                    // return redirect()->route('administrator.dashboard_test_sections', [$testId])->withErrors(['testSuccess' => 'Test succesfully added.']);
                } else {
                    return back()->withErrors(['testError' => 'Server Error, please try again.']);
                }
                return print_r($inputs);
            }

            if ($req->input('form_name') && $req->input('form_name') == 'notify_creator') {
                return json_encode($req->all());
            }
            if ($inputs['form_name'] == 'sections_form') {

                // dd($req->all());
                $errors         = array();
                $sectionsSaved  = array();
                $inputs         = $req->all();
                $sections       = $inputs['section'];
                foreach ($sections as $key => $section) {
                    $sectionMd      = new TestSections();
                    if ($section['id'] > 0) {
                        $sectionMd  = TestSections::find($section['id']);
                    }
                    $sectionMd->test_id                 = $test_id;
                    $sectionMd->subject                 = $section['subject'];
                    $sectionMd->subject_part            = isset($section['subject_part']) ? $section['subject_part'] : 0;
                    $sectionMd->subject_part_lesson     = isset($section['subject_part_lesson']) ? $section['subject_part_lesson'] : 0;
                    $sectionMd->gn_subject_part_lesson  = isset($section['gn_subject_part_lesson']) ? $section['gn_subject_part_lesson'] : 0;
                    $sectionMd->number_of_questions     = $section['number_of_questions'];
                    $sectionMd->question_type           = $section['question_type'];
                    $sectionMd->mcq_options             = isset($section['mcq_options']) ? $section['mcq_options'] : 0;
                    $sectionMd->difficulty_level        = $section['difficulty_level'];
                    $sectionMd->creator_id              = $section['creator_id'];
                    $sectionMd->creator_id              = $section['creator_id'];
                    $sectionMd->date_of_completion      = $section['date_of_completion'];
                    $sectionMd->duration                = $section['duration'];
                    $sectionMd->publisher_id            = $section['publisher_id'];
                    $sectionMd->publishing_date         = $section['publishing_date'];
                    $sectionMd->section_instruction     = $section['section_instruction'];
                    $query                              = $sectionMd->save();
                    $sectionKey                         = $key + 1;
                    if ($query) {
                        array_push($sectionsSaved, $sectionKey);
                    } else {
                        array_push($errors, $sectionKey);
                    }
                }
                $alertClass     = 'success';
                $errorMessage   = '';
                $successMessage = '';
                if (count($errors)) {
                    $errorSections  = implode(',', $errors);
                    $errorMessage   = 'Sections <b>(' . $errorSections . ')</b> not saved.<br>';
                    $alertClass     = 'warning';
                }
                if (count($sectionsSaved)) {
                    $savedSections  = implode(',', $sectionsSaved);
                    $successMessage = 'Sections <b>(' . $savedSections . ')</b> successfully saved.';
                }
                TestModal::sectionsCount();
                // $totalSections = count($sections);

                // $test->sections = $totalSections;
                // $test->save();

                $alertMessage   = $errorMessage . $successMessage;
                $returnResponse = ['class' => $alertClass, 'message' => $alertMessage];
                // return redirect()->route('administrator.dashboard_tests_list', [$test_id])->withErrors(['sectionsError' => $returnResponse]);
                return redirect()->route('administrator.dashboard_tests_list')->withErrors(['sectionsError' => $returnResponse]);
                return print_r($sections);
            }

            // if ($inputs['form_name'] == 'publish_test_form') {
            //     dd('')
            // }
        }
        $this->data['auth_id'] = Auth::id();
        // $test = TestModal::find($this->data['test_id']);


        $this->data['education_types']  = EducationType::get();
        $this->data['subject']          = Subject::get();
        // $this->data['subjects']         = Subject::get();
        $this->data['subjects']         = Gn_ClassSubject::get();
        // dd($this->data['subjects']->first()->subject->name);
        // $this->data['test']     = $test;
        // $this->data['subjects'] = Subject::get();

        $matchThis = ['in_franchise' => '0', 'isAdminAllowed' => '1'];
        $creators = User::where($matchThis)->where('roles', 'like', '%"creator"%')->orWhere('roles', 'like', '%"manager"%')->orWhere('roles', 'superadmin')->get();

        foreach ($creators as $key => $creator) {
            if ($creator['id'] == $this->data['auth_id']) {
                $creators[$key]['name'] = 'Admin';
            }
        }
        // dd($this->data['test']);
        $sections = TestSections::where('test_id', $test_id)->get();
        $this->data['sections'] = $sections;
        $this->data['creators'] = $creators;
        // $this->data['pagename'] = 'Test Sections';
        return view('Dashboard/Admin/Exam/savetest')->with('data', $this->data);
    }

    public function publishTest(Request $req, $test_id)
    {
        $test = TestModal::find($test_id);
        $this->data['marks'] = ['1', '2', '3', '4'];
        $this->data['negative_marks'] = [
            ['id' => '0.25', 'name' => '-0.25%'],
            ['id' => '0.33', 'name' => '-0.33%'],
            ['id' => '0.5', 'name' => '-0.50%'],
        ];
        $this->data['durations'] = [
            ['id' => 10, 'name' => '10 Minute'],
            ['id' => 20, 'name' => '20 Minute'],
            ['id' => 30, 'name' => '30 Minute'],
            ['id' => 40, 'name' => '40 Minute'],
            ['id' => 45, 'name' => '45 Minute'],
            ['id' => 50, 'name' => '50 Minute'],
            ['id' => 60, 'name' => '1 Hour'],
            ['id' => 70, 'name' => '1 Hour / 10 Minute'],
            ['id' => 75, 'name' => '1 Hour / 15 Minute'],
            ['id' => 80, 'name' => '1 Hour / 20 Minute'],
            ['id' => 90, 'name' => '1 Hour / 30 Minute'],
            ['id' => 100, 'name' => '1 Hour / 40 Minute'],
            ['id' => 105, 'name' => '1 Hour / 45 Minute'],
            ['id' => 110, 'name' => '1 Hour / 50 Minute'],
            ['id' => 120, 'name' => '2 Hour'],
            ['id' => 130, 'name' => '2 Hour / 10 Minute'],
            ['id' => 135, 'name' => '2 Hour / 15 Minute'],
            ['id' => 140, 'name' => '2 Hour / 20 Minute'],
            ['id' => 150, 'name' => '2 Hour / 30 Minute'],
            ['id' => 160, 'name' => '2 Hour / 40 Minute'],
            ['id' => 165, 'name' => '2 Hour / 45 Minute'],
            ['id' => 170, 'name' => '2 Hour / 50 Minute'],
            ['id' => 180, 'name' => '3 Hour'],
        ];
        $this->data['pagename'] = 'Add / Update Test';

        if ($req->isMethod('post')) {
            $inputs = $req->all();
            if ($inputs['form_name'] == 'publish_test') {
                // dd($req->all());
                $publish_test = TestModal::find($test_id);
                $publish_test->show_result      = isset($inputs['show_result']) ? $inputs['show_result'] == 'on' ? 1 : 0 : 0;
                $publish_test->show_rank        = isset($inputs['show_rank']) ? $inputs['show_rank'] == 'on' ? 1 : 0 : 0;
                $publish_test->show_answer      = isset($inputs['show_answer']) ? $inputs['show_answer'] == 'on' ? 1 : 0 : 0;
                $publish_test->show_solution    = isset($inputs['show_solution']) ? $inputs['show_solution'] == 'on' ? 1 : 0 : 0;
                $publish_test->test_type    = isset($inputs['test_type']) ? $inputs['test_type'] : NULL;
                $publish_test->package                  = isset($inputs['package']) ? implode(',', $inputs['package']) : NULL;
                $publish_test->test_cat    = isset($inputs['test_cat']) ? $inputs['test_cat'] : NULL;
                $publish_test->price    = isset($inputs['price']) ? $inputs['price'] : NULL;



                $publish_test->published        = 1;
                $publish_test->save();

                return redirect()->route('administrator.dashboard_tests_list')->withErrors(['testSuccess' => 'Test succesfully added.']);
            }
        }
        $this->data['test'] = $test;
        $this->data['test_category'] = DB::table('test_cat')->get();
        $this->data['group_classes']    = ClassGoupExamModel::where('education_type_id', $this->data['test']->education_type_id)->get();
        $this->data['agency_boards']    = Gn_EducationClassExamAgencyBoardUniversity::where('education_type_id', $this->data['test']->education_type_id)->where('classes_group_exams_id', $this->data['test']->education_type_child_id)->get();
        $this->data['other_exams']      = Gn_OtherExamClassDetailModel::where('education_type_id', $this->data['test']->education_type_id)->where('classes_group_exams_id', $this->data['test']->education_type_child_id)->where('agency_board_university_id', $this->data['test']->board_state_agency)->get();

        return view('Dashboard/Admin/Exam/publish_test')->with('data', $this->data);
    }
    public function testSections(Request $req, $test_id)
    {
        $this->data['auth_id'] = Auth::id();
        $test = TestModal::find($test_id);
        if ($req->isMethod('post')) {
            if ($req->input('form_name') && $req->input('form_name') == 'notify_creator') {
                return json_encode($req->all());
                // send email here to the creator, also check changes if new mail send by the manager
            }
            dd($req->all());
            $errors         = array();
            $sectionsSaved  = array();
            $inputs         = $req->all();
            $sections       = $inputs['section'];
            foreach ($sections as $key => $section) {
                $sectionMd      = new TestSections();
                if ($section['id'] > 0) {
                    $sectionMd  = TestSections::find($section['id']);
                }
                $sectionMd->test_id                 = $test_id;
                $sectionMd->subject                 = $section['subject'];
                $sectionMd->subject_part            = isset($section['subject_part']) ? $section['subject_part'] : 0;
                $sectionMd->subject_part_lesson     = isset($section['subject_part_lesson']) ? $section['subject_part_lesson'] : 0;
                $sectionMd->gn_subject_part_lesson  = isset($section['gn_subject_part_lesson']) ? $section['gn_subject_part_lesson'] : 0;
                $sectionMd->number_of_questions     = $section['number_of_questions'];
                $sectionMd->question_type           = $section['question_type'];
                $sectionMd->mcq_options             = isset($section['mcq_options']) ? $section['mcq_options'] : 0;
                $sectionMd->difficulty_level        = $section['difficulty_level'];
                $sectionMd->creator_id              = $section['creator_id'];
                $sectionMd->date_of_completion      = $section['date_of_completion'];
                $sectionMd->publisher_id            = $section['publisher_id'];
                $sectionMd->publishing_date         = $section['publishing_date'];
                $query                              = $sectionMd->save();
                $sectionKey                         = $key + 1;
                if ($query) {
                    array_push($sectionsSaved, $sectionKey);
                } else {
                    array_push($errors, $sectionKey);
                }
            }
            $alertClass     = 'success';
            $errorMessage   = '';
            $successMessage = '';
            if (count($errors)) {
                $errorSections  = implode(',', $errors);
                $errorMessage   = 'Sections <b>(' . $errorSections . ')</b> not saved.<br>';
                $alertClass     = 'warning';
            }
            if (count($sectionsSaved)) {
                $savedSections  = implode(',', $sectionsSaved);
                $successMessage = 'Sections <b>(' . $savedSections . ')</b> successfully saved.';
            }
            TestModal::sectionsCount();
            // $totalSections = count($sections);

            // $test->sections = $totalSections;
            // $test->save();

            $alertMessage   = $errorMessage . $successMessage;
            $returnResponse = ['class' => $alertClass, 'message' => $alertMessage];
            return redirect()->route('administrator.dashboard_test_sections', [$test_id])->withErrors(['sectionsError' => $returnResponse]);
            return print_r($sections);
        }
        $this->data['test']     = $test;
        $this->data['subjects'] = Subject::get();

        $matchThis = ['in_franchise' => '0', 'isAdminAllowed' => '1'];
        $creators = User::where($matchThis)->where('roles', 'like', '%"creator"%')->orWhere('roles', 'like', '%"manager"%')->orWhere('roles', 'superadmin')->get();

        foreach ($creators as $key => $creator) {
            if ($creator['id'] == $this->data['auth_id']) {
                $creators[$key]['name'] = 'Admin';
            }
        }

        $sections = TestSections::where('test_id', $test_id)->get();
        $this->data['sections'] = $sections;
        $this->data['creators'] = $creators;
        $this->data['pagename'] = 'Test Sections';
        return view('Dashboard/Admin/Exam/sections')->with('data', $this->data);
    }
    public function eductaion_type(Request $req)
    {
        if ($req->isMethod('post')) {
            $inputs = $req->all();

            $query          = false;
            $requestName    = '';
            $requestType    = '';
            $queryMd        = false;

            if ($inputs['form_name'] == 'class_form') {
                $requestName = 'Class / Group / Exam';
                $requestType = 'class';

                if (!empty($inputs['education_type_id']) && !empty($inputs['class_group_exam'])) {
                    $classMd = ClassGoupExamModel::where('education_type_id', $inputs['education_type_id'])->get()->pluck('id')->toArray();
                    $class_group_exam_data = array_diff($inputs['class_group_exam'], $classMd);
                    if (!empty($class_group_exam_data)) {
                        foreach ($class_group_exam_data as $key => $data) {
                            $examMd                    = new ClassGoupExamModel();
                            $examMd->education_type_id = $inputs['education_type_id'];
                            $examMd->name              = $data;
                            $queryMd = $examMd;
                            $query = $queryMd->save();
                            $this->insert_data['class_group_exam'][$key] =  $examMd->id;
                        }
                    }
                }
                if (!empty($inputs['education_type_id']) && !empty($inputs['class_group_exam']) && !empty($inputs['boards'])) {
                    if (!empty($this->insert_data['class_group_exam'])) {
                        $board_ids = BoardAgencyStateModel::get()->pluck('id')->toArray();
                        $get_diff_board_name = array_diff($inputs['boards'], $board_ids);
                        $get_diff_board_ids  = array_diff($inputs['boards'], $get_diff_board_name);

                        if (!empty($get_diff_board_ids)) {
                            foreach ($this->insert_data['class_group_exam'] as $class_group_exam) {
                                foreach ($get_diff_board_ids as $value) {
                                    $insert_board = new Gn_EducationClassExamAgencyBoardUniversity();
                                    $insert_board->education_type_id        = $inputs['education_type_id'];
                                    $insert_board->classes_group_exams_id   = $class_group_exam;
                                    $insert_board->board_agency_exam_id     = $value;
                                    $queryMd  = $insert_board;
                                    $query    = $queryMd->save();
                                }
                            }
                        }

                        if (!empty($get_diff_board_name)) {
                            foreach ($get_diff_board_name as $value) {
                                $boardMd         = new BoardAgencyStateModel();
                                $boardMd->name   = $value;
                                $boardMd->save();
                                foreach ($this->insert_data['class_group_exam'] as $class_group_exam) {
                                    $insert_board = new Gn_EducationClassExamAgencyBoardUniversity();
                                    $insert_board->education_type_id        = $inputs['education_type_id'];
                                    $insert_board->classes_group_exams_id   = $class_group_exam;
                                    $insert_board->board_agency_exam_id     = $boardMd->id;
                                    $queryMd  = $insert_board;
                                    $query    = $queryMd->save();
                                }
                            }
                        }
                    }
                    $class_ids = ClassGoupExamModel::get()->pluck('id')->toArray();
                    $get_diff_class_name = array_diff($inputs['class_group_exam'], $class_ids);
                    $get_diff_class_ids  = array_diff($inputs['class_group_exam'], $get_diff_class_name);

                    $board_ids = BoardAgencyStateModel::get()->pluck('id')->toArray();
                    $get_diff_board_name = array_diff($inputs['boards'], $board_ids);
                    $get_diff_board_ids  = array_diff($inputs['boards'], $get_diff_board_name);
                    if (!empty($get_diff_board_ids)) {
                        foreach ($get_diff_class_ids as $value) {
                            $get_boards_id = Gn_EducationClassExamAgencyBoardUniversity::where('education_type_id', $inputs['education_type_id'])->where('classes_group_exams_id', '=', $value)->get()->pluck('board_agency_exam_id')->toArray();
                            $insert_ids = array_diff($get_diff_board_ids, $get_boards_id);
                            if (!empty($insert_ids)) {
                                foreach ($insert_ids as $insert_id) {
                                    $insert_board = new Gn_EducationClassExamAgencyBoardUniversity();
                                    $insert_board->education_type_id        = $inputs['education_type_id'];
                                    $insert_board->classes_group_exams_id   = $value;
                                    $insert_board->board_agency_exam_id     = $insert_id;
                                    $queryMd  = $insert_board;
                                    $query    = $queryMd->save();
                                }
                            }
                        }
                    }
                    if (!empty($get_diff_board_name)) {
                        foreach ($get_diff_board_name as $value) {
                            $boardMd         = new BoardAgencyStateModel();
                            $boardMd->name   = $value;
                            $boardMd->save();
                            foreach ($get_diff_class_ids as $class_group_exam) {
                                $insert_board = new Gn_EducationClassExamAgencyBoardUniversity();
                                $insert_board->education_type_id        = $inputs['education_type_id'];
                                $insert_board->classes_group_exams_id   = $class_group_exam;
                                $insert_board->board_agency_exam_id     = $boardMd->id;
                                $queryMd  = $insert_board;
                                $query    = $queryMd->save();
                            }
                        }
                    }
                }

                if (!empty($inputs['education_type_id']) && !empty($inputs['class_group_exam']) && !empty($inputs['boards']) && !empty($inputs['class_other_exam_detail'])) {
                    # code...
                    print_r('class_other_exam_detail not empty');
                }
                // dd($req->all());

                // $classMd = new ClassGoupExamModel();
                // if ($inputs['id'] > 0) {
                //     $classMd = ClassGoupExamModel::find($inputs['id']);
                // }
                // $classMd->name = $inputs['name'];
                // $classMd->education_type_id = $inputs['education_type_id'];
                // $classMd->boards = $inputs['boards'];
                // $classMd->subjects = $inputs['subjects'];
                // $queryMd = $classMd;
                // $query = $queryMd->save();
                // if ($query) {
                //     $boards = (array) $inputs['boards'];
                //     $subjects = (array) $inputs['subjects'];
                //     if ($inputs['id'] > 0) {
                //         AssignClassBoardModel::where('class_id', $queryMd->id)->delete();
                //         AssignClassSubjectsModel::where('class_id', $queryMd->id)->delete();
                //     }
                //     foreach ($boards as $key => $board) {
                //         $assigningBoard = new AssignClassBoardModel();
                //         $assigningBoard->class_id = $queryMd->id;
                //         $assigningBoard->board_id = $board;
                //         $assigningBoard->save();
                //     }
                //     foreach ($subjects as $key => $subject) {
                //         $assignineSubject = new AssignClassSubjectsModel();
                //         $assignineSubject->class_id = $queryMd->id;
                //         $assignineSubject->subject_id = $subject;
                //         $assignineSubject->save();
                //     }
                // }
            }
            if ($inputs['form_name'] == 'education_form') {
                $requestName = 'Education Type';
                $requestType = 'education';

                if ($inputs['id'] > 0) {
                    $education_type = EducationType::find($inputs['id']);
                    if (!empty($education_type)) {
                        $education_type->name   = is_array($inputs['name']) ? $inputs['name'][0] : $inputs['name'];
                        $queryMd                = $education_type;
                        $query                  = $queryMd->save();
                    }
                } else {
                    $names = is_array($inputs['name']) ? $inputs['name'] : [$inputs['name']];
                    foreach ($names as $data) {
                        $educationMd        = new EducationType();
                        $educationMd->name  = $data;
                        $queryMd            = $educationMd;
                        $query              = $queryMd->save();
                    }
                }
            }
            if ($inputs['form_name'] == 'exam_form') {
                $requestName = "Class / Group / Exam Name";
                $requestType = 'exam';

                // Convert comma-separated string names into an array
                $inputs['name'] = is_array($inputs['name']) ? $inputs['name'] : array_map('trim', explode(',', $inputs['name']));

                if ($inputs['id'] > 0) {
                    $all_class_id = ClassGoupExamModel::get()->pluck('id', 'name')->toArray();
                    $class_id     = Gn_AssignClassGroupExamName::where('education_type_id', $inputs['exam_education_type_id'])->get()->pluck('classes_group_exams_id')->toArray();

                    // Resolve names to IDs for the update logic
                    $resolvedIds = [];
                    foreach ($inputs['name'] as $val) {
                        if (empty($val)) continue;
                        if (is_numeric($val)) {
                            $resolvedIds[] = (int)$val;
                        } else {
                            $examItem = ClassGoupExamModel::firstOrCreate(['name' => $val, 'education_type_id' => $inputs['exam_education_type_id']]);
                            $resolvedIds[] = $examItem->id;
                        }
                    }
                    $inputs['name'] = $resolvedIds;

                    $new_insert_data             = array_diff($inputs['name'], array_values($all_class_id));
                    $new_insert_data_diff_id     = array_diff($new_insert_data, $class_id);
                    $new_insert_data_diff_name   = array_diff($new_insert_data, $new_insert_data_diff_id);

                    $delete_data_diff_id        = array_diff($class_id, $inputs['name']);

                    if (!empty($new_insert_data_diff_id)) {

                        foreach ($new_insert_data_diff_id as $key => $value) {
                            $examMd                    = new ClassGoupExamModel();
                            $examMd->education_type_id = $inputs['exam_education_type_id'];
                            $examMd->name              = $value;
                            $examMd->save();

                            $assign_class_group                         = new Gn_AssignClassGroupExamName();
                            $assign_class_group->education_type_id      = $inputs['exam_education_type_id'];
                            $assign_class_group->classes_group_exams_id = $examMd->id;
                            $assign_class_group->save();

                            $this->data['classes_group_exams_id'][$key] = $examMd->id;
                        }
                        $verify_education_type = Gn_DisplayClassGroupExamName::where('education_type_id', '=', $inputs['exam_education_type_id'])->first();
                        if (!empty($verify_education_type)) {
                            $class_id = json_decode($verify_education_type->class_group_exam_name);
                            $class_id = array_merge($class_id, $this->data['classes_group_exams_id']);
                            $verify_education_type->class_group_exam_name = json_encode($class_id);
                            $queryMd    = $verify_education_type;
                            $query      = $queryMd->save();
                        } else {
                            $gn_DisplayClassGroupExamName = new Gn_DisplayClassGroupExamName();
                            $gn_DisplayClassGroupExamName->education_type_id     = $inputs['exam_education_type_id'];
                            $gn_DisplayClassGroupExamName->class_group_exam_name = json_encode($this->data['classes_group_exams_id']);
                            $queryMd    = $gn_DisplayClassGroupExamName;
                            $query      = $queryMd->save();
                        }
                    }

                    if (!empty($new_insert_data_diff_name)) {
                        foreach ($new_insert_data_diff_name as $value) {
                            $assign_class_group                         = new Gn_AssignClassGroupExamName();
                            $assign_class_group->education_type_id      = $inputs['exam_education_type_id'];
                            $assign_class_group->classes_group_exams_id = $examMd->id;
                            $assign_class_group->save();
                        }

                        $verify_education_type = Gn_DisplayClassGroupExamName::where('education_type_id', '=', $inputs['exam_education_type_id'])->first();
                        if (!empty($verify_education_type)) {
                            $class_id = json_decode($verify_education_type->class_group_exam_name);
                            $class_id = array_merge($class_id, $new_insert_data_diff_name);
                            $verify_education_type->class_group_exam_name = json_encode($class_id);
                            $queryMd    = $verify_education_type;
                            $query      = $queryMd->save();
                        } else {
                            $gn_DisplayClassGroupExamName = new Gn_DisplayClassGroupExamName();
                            $gn_DisplayClassGroupExamName->education_type_id     = $inputs['exam_education_type_id'];
                            $gn_DisplayClassGroupExamName->class_group_exam_name = json_encode($new_insert_data_diff_name);
                            $queryMd    = $gn_DisplayClassGroupExamName;
                            $query      = $queryMd->save();
                        }
                    }

                    if (!empty($delete_data_diff_id)) {
                        foreach ($delete_data_diff_id as $key => $value) {
                            $delete_data = Gn_AssignClassGroupExamName::where('education_type_id', '=', $inputs['exam_education_type_id'])->where('classes_group_exams_id', $value);
                            $delete_data->delete();
                        }

                        $class_education                        = ClassGoupExamModel::whereIn('id', $delete_data_diff_id);
                        $board_education                        = Gn_EducationClassExamAgencyBoardUniversity::where('education_type_id', '=', $inputs['exam_education_type_id'])->whereIn('classes_group_exams_id', $delete_data_diff_id);
                        $other_exam_education                   = Gn_OtherExamClassDetailModel::where('education_type_id', '=', $inputs['exam_education_type_id'])->whereIn('classes_group_exams_id', $delete_data_diff_id);
                        $gn_DisplayExamAgencyBoardUniversity    = Gn_DisplayExamAgencyBoardUniversity::where('education_type_id', '=', $inputs['exam_education_type_id'])->whereIn('classes_group_exams_id', $delete_data_diff_id);
                        $gn_ohter_exam_display                  = Gn_DisplayOtherExamClassDetail::where('education_type_id', '=', $inputs['exam_education_type_id'])->whereIn('classes_group_exams_id', $delete_data_diff_id);

                        $class_education->delete();
                        $board_education->delete();
                        $other_exam_education->delete();
                        $gn_DisplayExamAgencyBoardUniversity->delete();
                        $gn_ohter_exam_display->delete();

                        $update_class = Gn_DisplayClassGroupExamName::where('education_type_id', '=', $inputs['exam_education_type_id'])->first();
                        $class_id = json_decode($update_class->class_group_exam_name);
                        $class_id = array_merge($class_id, $delete_data_diff_id);
                        $update_class->education_type_id = $inputs['exam_education_type_id'];
                        $update_class->class_group_exam_name = $class_id;
                        $update_class->save();
                    }
                    // dd('hello1',$new_insert_data_diff_id,$new_insert_data_diff_name,$delete_data_diff_id);

                    // $examMd                    = ClassGoupExamModel::find($inputs['id']);
                    // $examMd->education_type_id = $inputs['exam_education_type_id'];
                    // $examMd->name              = $inputs['name'];
                    // $queryMd                   = $examMd;
                    // $query                     = $queryMd->save();

                    // foreach ($inputs['name'] as $key => $data) {
                    //     $examMd                     = new ClassGoupExamModel();
                    //     $examMd->education_type_id  = $inputs['exam_education_type_id'];
                    //     $examMd->name               = $data;
                    //     $examMd->save();

                    //     $assign_class_group                         = new Gn_AssignClassGroupExamName();
                    //     $assign_class_group->education_type_id      = $inputs['exam_education_type_id'];
                    //     $assign_class_group->classes_group_exams_id = $examMd->id;
                    //     $assign_class_group->save();

                    //     $this->data['classes_group_exams_id'][$key] = $examMd->id;
                    // }

                    // $verify_education_type = Gn_DisplayClassGroupExamName::where('education_type_id','=',$inputs['exam_education_type_id'])->first();
                    // if (!empty($verify_education_type)) {
                    //     $class_id = json_decode($verify_education_type->class_group_exam_name);
                    //     $class_id = array_merge($class_id,$this->data['classes_group_exams_id']);
                    //     $verify_education_type->class_group_exam_name = json_encode($class_id);
                    //     $queryMd    = $verify_education_type;
                    //     $query      = $queryMd->save();
                    // }
                    // else{
                    //     $gn_DisplayClassGroupExamName = new Gn_DisplayClassGroupExamName();
                    //     $gn_DisplayClassGroupExamName->education_type_id     = $inputs['exam_education_type_id'];
                    //     $gn_DisplayClassGroupExamName->class_group_exam_name = json_encode($this->data['classes_group_exams_id']);
                    //     $queryMd    = $gn_DisplayClassGroupExamName;
                    //     $query      = $queryMd->save();
                    // }

                } else {
                    foreach ($inputs['name'] as $key => $data) {
                        $examMd                     = new ClassGoupExamModel();
                        $examMd->education_type_id  = $inputs['exam_education_type_id'];
                        $examMd->name               = $data;
                        $examMd->save();

                        $assign_class_group                         = new Gn_AssignClassGroupExamName();
                        $assign_class_group->education_type_id      = $inputs['exam_education_type_id'];
                        $assign_class_group->classes_group_exams_id = $examMd->id;
                        $assign_class_group->save();

                        $this->data['classes_group_exams_id'][$key] = $examMd->id;
                    }

                    $verify_education_type = Gn_DisplayClassGroupExamName::where('education_type_id', '=', $inputs['exam_education_type_id'])->first();
                    if (!empty($verify_education_type)) {
                        $class_id = json_decode($verify_education_type->class_group_exam_name);
                        $class_id = array_merge($class_id, $this->data['classes_group_exams_id']);
                        $verify_education_type->class_group_exam_name = json_encode($class_id);
                        $queryMd    = $verify_education_type;
                        $query      = $queryMd->save();
                    } else {
                        $gn_DisplayClassGroupExamName = new Gn_DisplayClassGroupExamName();
                        $gn_DisplayClassGroupExamName->education_type_id     = $inputs['exam_education_type_id'];
                        $gn_DisplayClassGroupExamName->class_group_exam_name = json_encode($this->data['classes_group_exams_id']);
                        $queryMd    = $gn_DisplayClassGroupExamName;
                        $query      = $queryMd->save();
                    }
                }

                // $examMd      = new ClassGoupExamModel();
                // if ($inputs['id'] > 0) {
                //     $examMd  = ClassGoupExamModel::find($inputs['id']);
                // }
                // $examMd->education_type_id = $inputs['exam_education_type_id'];
                // $examMd->name              = $inputs['name'];
                // $queryMd                   = $examMd;
                // $query                     = $queryMd->save();
            }

            if ($inputs['form_name'] == 'board_form') {
                $requestName = 'Board / State / Exam Agency';
                $requestType = 'board';

                // Ensure name is an array
                $inputs['name'] = is_array($inputs['name']) ? $inputs['name'] : array_map('trim', explode(',', $inputs['name']));

                if ($inputs['id'] > 0) {
                    // Resolve names to IDs for update logic
                    $resolvedIds = [];
                    foreach ($inputs['name'] as $val) {
                        if (empty($val)) continue;
                        if (is_numeric($val)) {
                            $resolvedIds[] = (int)$val;
                        } else {
                            $boardItem = BoardAgencyStateModel::firstOrCreate(['name' => $val]);
                            $resolvedIds[] = $boardItem->id;
                        }
                    }
                    $inputs['name'] = $resolvedIds;

                    // dd('inside Edit');
                    $boardMd        = BoardAgencyStateModel::find($inputs['id']);
                    $data1          = BoardAgencyStateModel::whereNotIn('id', $inputs['name'])->get();
                    $empty_data     = BoardAgencyStateModel::get();
                    // dd($data1);
                    if (!$data1->isEmpty()) {
                        $board_data = BoardAgencyStateModel::get()->pluck('id')->toArray();
                        $data_board = Gn_EducationClassExamAgencyBoardUniversity::where('education_type_id', $inputs['education_type_id'])->where('classes_group_exams_id', $inputs['classes_group_exams_id'])->get()->pluck('board_agency_exam_id')->toArray();

                        $new_insert_data = array_diff($inputs['name'], $data_board);

                        $new_insert_data_diff_id     = array_diff($new_insert_data, $board_data);
                        $new_insert_data_diff_name   = array_diff($new_insert_data, $new_insert_data_diff_id);
                        $delete_data_diff_id        = array_diff($data_board, $inputs['name']);

                        if (!empty($delete_data_diff_id)) {
                            foreach ($delete_data_diff_id as $value) {
                                $delete_board = Gn_EducationClassExamAgencyBoardUniversity::where('education_type_id', $inputs['education_type_id'])->where('classes_group_exams_id', $inputs['classes_group_exams_id'])->where('board_agency_exam_id', $value);
                                $delete_board->delete();
                            }
                            $other_exam_education                   = Gn_OtherExamClassDetailModel::where('education_type_id', $inputs['education_type_id'])->where('classes_group_exams_id', $inputs['classes_group_exams_id'])->whereIn('agency_board_university_id', $delete_data_diff_id);
                            $gn_ohter_exam_display                  = Gn_DisplayOtherExamClassDetail::where('education_type_id', $inputs['education_type_id'])->where('classes_group_exams_id', $inputs['classes_group_exams_id'])->whereIn('agency_board_university_id', $delete_data_diff_id);

                            $other_exam_education->delete();
                            $gn_ohter_exam_display->delete();

                            $gn_display_exam = Gn_DisplayExamAgencyBoardUniversity::where('education_type_id', '=', $inputs['education_type_id'])->where('classes_group_exams_id', '=', $inputs['classes_group_exams_id'])->first();
                            $board_id = json_decode($gn_display_exam->board_id);
                            $board_id = array_diff($board_id, $delete_data_diff_id);
                            $gn_display_exam->board_id = str_replace('"', '', $board_id);
                            $query = $gn_display_exam->save();
                        }

                        if (!empty($new_insert_data_diff_name)) {
                            foreach ($new_insert_data_diff_name as $value) {
                                $board_multipleMd                         = new Gn_EducationClassExamAgencyBoardUniversity();
                                $board_multipleMd->education_type_id      = $inputs['education_type_id'];
                                $board_multipleMd->classes_group_exams_id = $inputs['classes_group_exams_id'];
                                $board_multipleMd->board_agency_exam_id   = $value;
                                $queryMd                                  = $board_multipleMd;
                                $query                                    = $queryMd->save();
                            }
                            $gn_display_exam = Gn_DisplayExamAgencyBoardUniversity::where('education_type_id', '=', $inputs['education_type_id'])->where('classes_group_exams_id', '=', $inputs['classes_group_exams_id'])->first();
                            if (!empty($gn_display_exam)) {
                                $board_ids = json_decode($gn_display_exam->board_id);
                                $board_ids = array_merge($board_ids, $new_insert_data_diff_name);
                                $gn_display_exam->board_id = str_replace('"', '', json_encode($board_ids));
                                $query = $gn_display_exam->save();
                            } else {
                                $gn_diaplay_exam =  new Gn_DisplayExamAgencyBoardUniversity();
                                $gn_diaplay_exam->education_type_id      = $inputs['education_type_id'];
                                $gn_diaplay_exam->classes_group_exams_id = $inputs['classes_group_exams_id'];
                                $gn_diaplay_exam->board_id               = str_replace('"', '', json_encode($new_insert_data_diff_name));
                                $query = $gn_diaplay_exam->save();
                            }
                        }

                        if (!empty($new_insert_data_diff_id)) {
                            foreach ($new_insert_data_diff_id as $key => $value) {
                                $boardMd         = new BoardAgencyStateModel();
                                $boardMd->name   = $value;
                                $boardMd->save();

                                $board_multipleMd                          = new Gn_EducationClassExamAgencyBoardUniversity();
                                $board_multipleMd->education_type_id       = $inputs['education_type_id'];
                                $board_multipleMd->classes_group_exams_id  = $inputs['classes_group_exams_id'];
                                $board_multipleMd->board_agency_exam_id    = $boardMd->id;
                                $board_multipleMd->save();
                                $this->data['board_multipleMd_name'][$key] = $boardMd->id;
                            }
                            $gn_display_exam = Gn_DisplayExamAgencyBoardUniversity::where('education_type_id', '=', $inputs['education_type_id'])->where('classes_group_exams_id', '=', $inputs['classes_group_exams_id'])->first();
                            if (!empty($gn_display_exam)) {
                                $board_ids = json_decode($gn_display_exam->board_id);
                                $board_ids = array_merge($board_ids, $this->data['board_multipleMd_name']);
                                $gn_display_exam->board_id = str_replace('"', '', json_encode($board_ids));
                                $query = $gn_display_exam->save();
                            } else {
                                $gn_diaplay_exam =  new Gn_DisplayExamAgencyBoardUniversity();
                                $gn_diaplay_exam->education_type_id      = $inputs['education_type_id'];
                                $gn_diaplay_exam->classes_group_exams_id = $inputs['classes_group_exams_id'];
                                $gn_diaplay_exam->board_id               = str_replace('"', '', json_encode($this->data['board_multipleMd_name']));
                                $queryMd                                 = $gn_diaplay_exam;
                                $query                                   = $queryMd->save();
                            }
                        }
                    } else {

                        foreach ($inputs['name'] as $key => $value) {
                            $board_multipleMd                         = new Gn_EducationClassExamAgencyBoardUniversity();
                            $board_multipleMd->education_type_id      = $inputs['education_type_id'];
                            $board_multipleMd->classes_group_exams_id = $inputs['classes_group_exams_id'];
                            $board_multipleMd->board_agency_exam_id   = $value;
                            $board_multipleMd->save();
                            $this->data['board_multipleMd_id'][$key]  = $value;
                        }
                        $gn_display_exam = Gn_DisplayExamAgencyBoardUniversity::where('education_type_id', '=', $inputs['education_type_id'])->where('classes_group_exams_id', '=', $inputs['classes_group_exams_id'])->first();
                        if (!empty($gn_display_exam)) {
                            $board_ids = json_decode($gn_display_exam->board_id);
                            $board_ids = array_merge($board_ids, $this->data['board_multipleMd_id']);
                            $gn_display_exam->board_id = str_replace('"', '', json_encode($board_ids));
                            $gn_display_exam->save();
                        } else {
                            $gn_diaplay_exam =  new Gn_DisplayExamAgencyBoardUniversity();
                            $gn_diaplay_exam->education_type_id      = $inputs['education_type_id'];
                            $gn_diaplay_exam->classes_group_exams_id = $inputs['classes_group_exams_id'];
                            $gn_diaplay_exam->board_id               = str_replace('"', '', json_encode($this->data['board_multipleMd_id']));
                            $query = $gn_diaplay_exam->save();
                        }
                    }
                } else {
                    $data = BoardAgencyStateModel::whereNotIn('id', $inputs['name'])->get();
                    $empty_data = BoardAgencyStateModel::get();
                    if ($empty_data->isEmpty()) {
                        foreach ($inputs['name'] as $key => $value) {
                            $boardMd         = new BoardAgencyStateModel();
                            $boardMd->name   = $value;
                            $boardMd->save();

                            $board_multipleMd                         = new Gn_EducationClassExamAgencyBoardUniversity();
                            $board_multipleMd->education_type_id      = $inputs['education_type_id'];
                            $board_multipleMd->classes_group_exams_id = $inputs['classes_group_exams_id'];
                            $board_multipleMd->board_agency_exam_id   = $boardMd->id;
                            $board_multipleMd->save();
                            $this->data['new_insert_data_diff_name'][$key] =  $boardMd->id;
                        }

                        $gn_display_exam = Gn_DisplayExamAgencyBoardUniversity::where('education_type_id', '=', $inputs['education_type_id'])->where('classes_group_exams_id', '=', $inputs['classes_group_exams_id'])->first();
                        if (!empty($gn_display_exam)) {
                            $board_ids = json_decode($gn_display_exam->board_id);
                            $board_ids = array_merge($board_ids, $this->data['new_insert_data_diff_name']);
                            $gn_display_exam->board_id = str_replace('"', '', json_encode($board_ids));
                            $gn_display_exam->education_type_id      = $inputs['education_type_id'];
                            $gn_display_exam->classes_group_exams_id = $inputs['classes_group_exams_id'];
                            $gn_display_exam->save();
                            $queryMd                                  = $gn_display_exam;
                            $query                                    = $queryMd->save();
                        } else {
                            $gn_diaplay_exam =  new Gn_DisplayExamAgencyBoardUniversity();
                            $gn_diaplay_exam->education_type_id      = $inputs['education_type_id'];
                            $gn_diaplay_exam->classes_group_exams_id = $inputs['classes_group_exams_id'];
                            $gn_diaplay_exam->board_id               = str_replace('"', '', json_encode($this->data['new_insert_data_diff_name']));
                            $gn_diaplay_exam->save();
                            $queryMd                                  = $gn_diaplay_exam;
                            $query                                    = $queryMd->save();
                        }
                    } else {

                        if (!$data->isEmpty()) {
                            $board_data = BoardAgencyStateModel::get()->pluck('id')->toArray();
                            $data_board = Gn_EducationClassExamAgencyBoardUniversity::where('education_type_id', $inputs['education_type_id'])->where('classes_group_exams_id', $inputs['classes_group_exams_id'])->get()->pluck('board_agency_exam_id')->toArray();
                            $new_insert_data = array_diff($inputs['name'], $data_board);

                            $new_insert_data_diff_id     = array_diff($new_insert_data, $board_data);
                            $new_insert_data_diff_name   = array_diff($new_insert_data, $new_insert_data_diff_id);

                            if (!empty($new_insert_data_diff_name)) {
                                foreach ($new_insert_data_diff_name as $value) {
                                    $board_multipleMd                         = new Gn_EducationClassExamAgencyBoardUniversity();
                                    $board_multipleMd->education_type_id      = $inputs['education_type_id'];
                                    $board_multipleMd->classes_group_exams_id = $inputs['classes_group_exams_id'];
                                    $board_multipleMd->board_agency_exam_id   = $value;
                                    $queryMd                                  = $board_multipleMd;
                                    $query                                    = $queryMd->save();
                                }
                                $gn_display_exam = Gn_DisplayExamAgencyBoardUniversity::where('education_type_id', '=', $inputs['education_type_id'])->where('classes_group_exams_id', '=', $inputs['classes_group_exams_id'])->first();
                                if (!empty($gn_display_exam)) {
                                    $board_ids = json_decode($gn_display_exam->board_id);
                                    $board_ids = array_merge($board_ids, $new_insert_data_diff_name);
                                    $gn_display_exam->board_id = str_replace('"', '', json_encode($board_ids));
                                    $gn_display_exam->save();
                                } else {
                                    $gn_diaplay_exam =  new Gn_DisplayExamAgencyBoardUniversity();
                                    $gn_diaplay_exam->education_type_id      = $inputs['education_type_id'];
                                    $gn_diaplay_exam->classes_group_exams_id = $inputs['classes_group_exams_id'];
                                    $gn_diaplay_exam->board_id               = str_replace('"', '', json_encode($new_insert_data_diff_name));
                                    $gn_diaplay_exam->save();
                                }
                            }

                            if (!empty($new_insert_data_diff_id)) {
                                foreach ($new_insert_data_diff_id as $key => $value) {
                                    $boardMd         = new BoardAgencyStateModel();
                                    $boardMd->name   = $value;
                                    $boardMd->save();

                                    $board_multipleMd                          = new Gn_EducationClassExamAgencyBoardUniversity();
                                    $board_multipleMd->education_type_id       = $inputs['education_type_id'];
                                    $board_multipleMd->classes_group_exams_id  = $inputs['classes_group_exams_id'];
                                    $board_multipleMd->board_agency_exam_id    = $boardMd->id;
                                    $board_multipleMd->save();
                                    $this->data['board_multipleMd_name'][$key] = $boardMd->id;
                                }
                                $gn_display_exam = Gn_DisplayExamAgencyBoardUniversity::where('education_type_id', '=', $inputs['education_type_id'])->where('classes_group_exams_id', '=', $inputs['classes_group_exams_id'])->first();
                                if (!empty($gn_display_exam)) {
                                    $board_ids = json_decode($gn_display_exam->board_id);
                                    $board_ids = array_merge($board_ids, $this->data['board_multipleMd_name']);
                                    $gn_display_exam->education_type_id      = $inputs['education_type_id'];
                                    $gn_display_exam->classes_group_exams_id = $inputs['classes_group_exams_id'];
                                    $gn_display_exam->board_id = str_replace('"', '', json_encode($board_ids));
                                    $gn_display_exam->save();
                                } else {
                                    $gn_diaplay_exam =  new Gn_DisplayExamAgencyBoardUniversity();
                                    $gn_diaplay_exam->education_type_id      = $inputs['education_type_id'];
                                    $gn_diaplay_exam->classes_group_exams_id = $inputs['classes_group_exams_id'];
                                    $gn_diaplay_exam->board_id               = str_replace('"', '', json_encode($this->data['board_multipleMd_name']));
                                    $queryMd                                 = $gn_diaplay_exam;
                                    $query                                   = $queryMd->save();
                                }
                            }
                        } else {
                            foreach ($inputs['name'] as $key => $value) {
                                $board_multipleMd                         = new Gn_EducationClassExamAgencyBoardUniversity();
                                $board_multipleMd->education_type_id      = $inputs['education_type_id'];
                                $board_multipleMd->classes_group_exams_id = $inputs['classes_group_exams_id'];
                                $board_multipleMd->board_agency_exam_id   = $value;
                                $board_multipleMd->save();
                                $this->data['board_multipleMd_id'][$key]  = $value;
                            }
                            $gn_display_exam = Gn_DisplayExamAgencyBoardUniversity::where('education_type_id', '=', $inputs['education_type_id'])->where('classes_group_exams_id', '=', $inputs['classes_group_exams_id'])->first();
                            if (!empty($gn_display_exam)) {
                                $board_ids = json_decode($gn_display_exam->board_id);
                                $board_ids = array_merge($board_ids, $this->data['board_multipleMd_id']);
                                $gn_display_exam->education_type_id      = $inputs['education_type_id'];
                                $gn_display_exam->classes_group_exams_id = $inputs['classes_group_exams_id'];
                                $gn_display_exam->board_id = str_replace('"', '', json_encode($board_ids));
                                $gn_display_exam->save();
                            } else {
                                $gn_diaplay_exam =  new Gn_DisplayExamAgencyBoardUniversity();
                                $gn_diaplay_exam->education_type_id      = $inputs['education_type_id'];
                                $gn_diaplay_exam->classes_group_exams_id = $inputs['classes_group_exams_id'];
                                $gn_diaplay_exam->board_id               = str_replace('"', '', json_encode($this->data['board_multipleMd_id']));
                                $queryMd                                 = $gn_diaplay_exam;
                                $query                                   = $queryMd->save();
                            }
                        }
                    }
                }
            }
            if ($inputs['form_name'] == 'otherExam_form') {
                $requestName = 'Other Exam / Class Detail';
                $requestType = 'otherExam';

                // Ensure name is an array or normalized from string
                $inputs['name'] = is_array($inputs['name']) ? $inputs['name'] : array_map('trim', explode(',', $inputs['name']));

                if ($inputs['id'] > 0) {
                    // Resolve names to IDs for update logic (or reuse existing if same name)
                    $resolvedIds = [];
                    foreach ($inputs['name'] as $val) {
                        if (empty($val)) continue;
                        if (is_numeric($val)) {
                            $resolvedIds[] = (int)$val;
                        } else {
                            $item = Gn_OtherExamClassDetailModel::firstOrCreate([
                                'name' => $val,
                                'education_type_id' => $inputs['education_type_id'],
                                'classes_group_exams_id' => $inputs['classes_group_exams_id'],
                                'agency_board_university_id' => $inputs['agency_board_university_id']
                            ]);
                            $resolvedIds[] = $item->id;
                        }
                    }
                    $inputs['name'] = $resolvedIds;

                    $all_other_exam_id = Gn_OtherExamClassDetailModel::get()->pluck('id')->toArray();
                    $other_exam_id     = Gn_OtherExamClassDetailModel::where('education_type_id', '=', $inputs['education_type_id'])->where('classes_group_exams_id', '=', $inputs['classes_group_exams_id'])->where('agency_board_university_id', '=', $inputs['agency_board_university_id'])->get()->pluck('id')->toArray();

                    $new_insert_data             = array_diff($inputs['name'], $all_other_exam_id);
                    $new_insert_data_diff_name   = array_diff($new_insert_data, $all_other_exam_id);
                    $new_insert_data_diff_id     = array_diff($inputs['name'], $other_exam_id);
                    $new_insert_data_diff_id     = array_diff($new_insert_data_diff_id, $new_insert_data_diff_name);
                    $delete_data_diff_id         = array_diff($other_exam_id, $inputs['name']);

                    // dd($new_insert_data_diff_id, $new_insert_data_diff_name,$delete_data_diff_id);

                    if (!empty($new_insert_data_diff_name)) {
                        foreach ($new_insert_data_diff_name as $key => $data) {
                            $otherExamMd = new Gn_OtherExamClassDetailModel();
                            $otherExamMd->education_type_id             = $inputs['education_type_id'];
                            $otherExamMd->classes_group_exams_id        = $inputs['classes_group_exams_id'];
                            $otherExamMd->agency_board_university_id    = $inputs['agency_board_university_id'];
                            $otherExamMd->name                          = $data;
                            $otherExamMd->save();
                            $this->data['other_exams_id1'][$key] = $otherExamMd->id;
                        }
                        $gn_display_exam = Gn_DisplayOtherExamClassDetail::where('education_type_id', '=', $inputs['education_type_id'])->where('classes_group_exams_id', '=', $inputs['classes_group_exams_id'])->where('agency_board_university_id', '=', $inputs['agency_board_university_id'])->first();

                        if (!empty($gn_display_exam)) {
                            $other_exam_id = json_decode($gn_display_exam->other_exam_id);
                            $other_exam_id = array_merge($other_exam_id, $this->data['other_exams_id1']);
                            $gn_display_exam->other_exam_id = json_encode($other_exam_id);
                            $queryMd                                    = $gn_display_exam;
                            $query                                      = $queryMd->save();
                        } else {
                            $gn_DisplayOtherExamClassDetail = new Gn_DisplayOtherExamClassDetail();
                            $gn_DisplayOtherExamClassDetail->education_type_id             = $inputs['education_type_id'];
                            $gn_DisplayOtherExamClassDetail->classes_group_exams_id        = $inputs['classes_group_exams_id'];
                            $gn_DisplayOtherExamClassDetail->agency_board_university_id    = $inputs['agency_board_university_id'];
                            $gn_DisplayOtherExamClassDetail->other_exam_id                 = json_encode($this->data['other_exams_id1']);
                            $queryMd                                    = $gn_DisplayOtherExamClassDetail;
                            $query                                      = $queryMd->save();
                        }
                    }

                    if (!empty($new_insert_data_diff_id)) {
                        $insert_other_exam_id = Gn_OtherExamClassDetailModel::whereIn('id', $new_insert_data_diff_id)->get()->pluck('name')->toArray();
                        foreach ($insert_other_exam_id as $key => $data) {
                            $otherExamMd = new Gn_OtherExamClassDetailModel();
                            $otherExamMd->education_type_id             = $inputs['education_type_id'];
                            $otherExamMd->classes_group_exams_id        = $inputs['classes_group_exams_id'];
                            $otherExamMd->agency_board_university_id    = $inputs['agency_board_university_id'];
                            $otherExamMd->name                          = $data;
                            $otherExamMd->save();
                            $this->data['other_exams_id2'][$key] = $otherExamMd->id;
                        }
                        $gn_display_exam = Gn_DisplayOtherExamClassDetail::where('education_type_id', '=', $inputs['education_type_id'])->where('classes_group_exams_id', '=', $inputs['classes_group_exams_id'])->where('agency_board_university_id', '=', $inputs['agency_board_university_id'])->first();

                        if (!empty($gn_display_exam)) {
                            $other_exam_id = json_decode($gn_display_exam->other_exam_id);
                            $other_exam_id = array_merge($other_exam_id, $this->data['other_exams_id2']);
                            $gn_display_exam->other_exam_id = json_encode($other_exam_id);
                            $queryMd                                    = $gn_display_exam;
                            $query                                      = $queryMd->save();
                        } else {
                            $gn_DisplayOtherExamClassDetail = new Gn_DisplayOtherExamClassDetail();
                            $gn_DisplayOtherExamClassDetail->education_type_id             = $inputs['education_type_id'];
                            $gn_DisplayOtherExamClassDetail->classes_group_exams_id        = $inputs['classes_group_exams_id'];
                            $gn_DisplayOtherExamClassDetail->agency_board_university_id    = $inputs['agency_board_university_id'];
                            $gn_DisplayOtherExamClassDetail->other_exam_id                 = json_encode($this->data['other_exams_id2']);
                            $queryMd                                    = $gn_DisplayOtherExamClassDetail;
                            $query                                      = $queryMd->save();
                        }
                    }

                    if (!empty($delete_data_diff_id)) {
                        $delete_other_exam = Gn_OtherExamClassDetailModel::whereIn('id', $delete_data_diff_id);
                        $delete_other_exam->delete();

                        $gn_display_exam1  = Gn_DisplayOtherExamClassDetail::where('education_type_id', '=', $inputs['education_type_id'])->where('classes_group_exams_id', '=', $inputs['classes_group_exams_id'])->where('agency_board_university_id', '=', $inputs['agency_board_university_id'])->first();
                        if (!empty($gn_display_exam1)) {
                            $other_exam_id = json_decode($gn_display_exam1->other_exam_id);
                            $other_exam_id = array_diff($other_exam_id, $delete_data_diff_id);
                            $gn_display_exam1->other_exam_id = json_encode($other_exam_id);
                            $queryMd                                    = $gn_display_exam1;
                            $query                                      = $queryMd->save();
                        }
                    }
                } else {

                    foreach ($inputs['name'] as $key => $data) {
                        $otherExamMd = new Gn_OtherExamClassDetailModel();
                        $otherExamMd->education_type_id             = $inputs['education_type_id'];
                        $otherExamMd->classes_group_exams_id        = $inputs['classes_group_exams_id'];
                        $otherExamMd->agency_board_university_id    = $inputs['agency_board_university_id'];
                        $otherExamMd->name                          = $data;
                        $otherExamMd->save();
                        $this->data['other_exams_id'][$key] = $otherExamMd->id;
                    }
                    $gn_display_exam = Gn_DisplayOtherExamClassDetail::where('education_type_id', '=', $inputs['education_type_id'])->where('classes_group_exams_id', '=', $inputs['classes_group_exams_id'])->where('agency_board_university_id', '=', $inputs['agency_board_university_id'])->first();

                    if (!empty($gn_display_exam)) {
                        $other_exam_id = json_decode($gn_display_exam->other_exam_id);
                        $other_exam_id = array_merge($other_exam_id, $this->data['other_exams_id']);
                        $gn_display_exam->other_exam_id = json_encode($other_exam_id);
                        $queryMd                                    = $gn_display_exam;
                        $query                                      = $queryMd->save();
                    } else {
                        $gn_DisplayOtherExamClassDetail = new Gn_DisplayOtherExamClassDetail();

                        $gn_DisplayOtherExamClassDetail->education_type_id             = $inputs['education_type_id'];
                        $gn_DisplayOtherExamClassDetail->classes_group_exams_id        = $inputs['classes_group_exams_id'];
                        $gn_DisplayOtherExamClassDetail->agency_board_university_id    = $inputs['agency_board_university_id'];
                        $gn_DisplayOtherExamClassDetail->other_exam_id                 = json_encode($this->data['other_exams_id']);
                        $queryMd                                    = $gn_DisplayOtherExamClassDetail;
                        $query                                      = $queryMd->save();
                    }
                }
            }

            if ($inputs['form_name'] == 'resultSubjectMapForm') {

                // Retrieve data from the request
                $educationTypeId = $req->input('education_type_id');
                $classesGroupExamsId = $req->input('classes_group_exams_id');
                $agencyBoardUniversityId = $req->input('agency_board_university_id');
                $name = $req->input('name');
                $subjectIds = $req->input('subject_id');

                // Encode the subject_id array to JSON
                $subjectIdJson = str_replace('"', '', json_encode($subjectIds));
                $nameJson = str_replace('"', '', json_encode($name));

                if ($req->input('id') > 0) {
                    $mapping = GnResultSubjectMapping::find($req->input('id'));
                    $mapping->update([
                        'education_type_id' => $educationTypeId,
                        'classes_group_exams_id' => $classesGroupExamsId,
                        'agency_board_university_id' => $agencyBoardUniversityId,
                        'name' => $nameJson,
                        'subject_id' => $subjectIdJson,
                    ]);
                    $mappId = $mapping->id;
                    // Delete old paper details and re-create to sync
                    SubjectPaperDetail::where('subject_mapping_id', $mappId)->delete();
                } else {
                    // Create a new mapping entry and save it to the database
                    $mappId = GnResultSubjectMapping::create([
                        'education_type_id' => $educationTypeId,
                        'classes_group_exams_id' => $classesGroupExamsId,
                        'agency_board_university_id' => $agencyBoardUniversityId,
                        'name' => $nameJson,
                        'subject_id' => $subjectIdJson,
                    ])->id;
                }

                foreach ($subjectIds as $sub) {
                    $subject = Subject::find($sub);
                    if ($subject) {
                        $subPaper = new SubjectPaperDetail();
                        $subPaper->subject_id = $subject->id;
                        $subPaper->subject_mapping_id = $mappId;
                        $subPaper->subject_name = $subject->name;
                        $subPaper->save();
                    }
                }

                return redirect()->back()->with('success', 'Data saved successfully!');
            }


            if ($inputs['form_name'] == 'other_form') {
                $requestName = 'Other Category / Class';
                $requestType = 'other';

                $otherMd = new OtherCategoryClass();
                if ($inputs['id'] > 0) {
                    $otherMd = OtherCategoryClass::find($inputs['id']);
                }
                $otherMd->name = $inputs['name'];
                $queryMd = $otherMd;
                $query = $queryMd->save();
            }


            if ($query) {
                return redirect()->route('dashboard_eductaion_type')->withErrors([$requestType . 'Success' => $requestName . ' succesfully added.']);
            } else {
                return back()->withErrors([$requestType . 'Error' => 'Server Error, please try again.']);
            }
        }

        $this->data['classes'] = ClassGoupExamModel::get();
        $this->data['subjects'] = Subject::get();

        $this->data['exams']    =   Gn_AssignClassGroupExamName::all()->groupBy('education_type_id');

        $educations = EducationType::get();
        foreach ($educations as $key => $education) {
            $educations[$key]['classes'] = ClassGoupExamModel::where('education_type_id', $education['id'])->count();
        }
        $this->data['educations'] = $educations;

        $boards = BoardAgencyStateModel::get();

        $this->data['boards'] = $boards;
        $others = OtherCategoryClass::get();
        $this->data['others'] = $others;
        $this->data['pagename'] = 'Education Type';

        $this->data['class_data'] = ClassGoupExamModel::get();

        $this->data['exam'] = Gn_DisplayClassGroupExamName::get();

        $this->data['gn_exam_agency_board'] = Gn_DisplayExamAgencyBoardUniversity::get();
        $this->data['board_data'] = Gn_EducationClassExamAgencyBoardUniversity::get();
        $this->data['gn_other_exam_classes'] = Gn_OtherExamClassDetailModel::get();
        $this->data['subjects'] = Subject::get();
        $this->data['other_exam_classes'] = Gn_DisplayOtherExamClassDetail::get();

        $this->data['resultSubjectMappings'] = GnResultSubjectMapping::with('subjectPaperDetails')->orderBy('created_at', 'asc')->get();


        return view('administrator/courses/sholarship_category')->with('data', $this->data);
    }
    public function subjects(Request $req)
    {
        $this->data['pagename'] = 'Subjects';
        if ($req->isMethod('post')) {
            $inputs = $req->all();
            // return print_r($inputs);
            $query          = false;
            $requestName    = '';
            $requestType    = '';
            $queryMd        = false;

            if ($inputs['form_name'] == 'subject_form') {
                $requestName = 'Subject';
                $requestType = 'subject';

                if ($inputs['id'] > 0) {
                    // dd($req->all());
                    $all_class_id = Subject::get()->pluck('id')->toArray();
                    $class_id     = Gn_ClassSubject::where('classes_group_exams_id', $inputs['class_id'])->get()->pluck('subject_id')->toArray();
                    $new_insert_data                = array_diff($inputs['name'], $class_id);
                    $new_insert_data_diff_id        = array_diff($new_insert_data, $all_class_id);
                    $new_insert_data_diff_name      = array_diff($new_insert_data, $new_insert_data_diff_id);

                    $delete_data_diff_id            = array_diff($class_id, $inputs['name']);
                    // dd($new_insert_data,$new_insert_data_diff_id,$new_insert_data_diff_name,$delete_data_diff_id);
                    if (!empty($delete_data_diff_id)) {
                        foreach ($delete_data_diff_id as $value) {
                            $delete_board = Gn_ClassSubject::where('classes_group_exams_id', $inputs['class_id'])->where('subject_id', $value);
                            $delete_board->delete();
                        }

                        $gn_display_exam                            = Gn_DisplayClassSubject::where('classes_group_exams_id', '=', $inputs['class_id'])->first();
                        $subject_id                                 = json_decode($gn_display_exam->subject_id);
                        $subject_id                                 = array_diff($subject_id, $delete_data_diff_id);
                        $gn_display_exam->classes_group_exams_id    = $inputs['class_id'];
                        $gn_display_exam->subject_id                = $subject_id;
                        $gn_display_exam->save();

                        SubjectPart::where('classes_group_exams_id', '=', $inputs['class_id'])->whereIn('subject_id', $delete_data_diff_id)->delete();
                        Gn_DisplaySubjectPart::where('classes_group_exams_id', '=', $inputs['class_id'])->whereIn('subject_id', $delete_data_diff_id)->delete();
                        SubjectPartLesson::where('classes_group_exams_id', '=', $inputs['class_id'])->whereIn('subject_id', $delete_data_diff_id)->delete();
                        Gn_DisplaySubjectPartChapter::where('classes_group_exams_id', '=', $inputs['class_id'])->whereIn('subject_id', $delete_data_diff_id)->delete();
                    }

                    if (!empty($new_insert_data_diff_name)) {
                        foreach ($new_insert_data_diff_name as $value) {
                            $gn_ClassSubject                         = new Gn_ClassSubject();
                            $gn_ClassSubject->classes_group_exams_id = $inputs['class_id'];
                            $gn_ClassSubject->subject_id             = $value;
                            $queryMd                                 = $gn_ClassSubject;
                            $query                                   = $queryMd->save();
                        }

                        $gn_display_class_subject = Gn_DisplayClassSubject::where('classes_group_exams_id', '=', $inputs['class_id'])->first();
                        if (!empty($gn_display_class_subject)) {
                            $board_ids = json_decode($gn_display_class_subject->subject_id);
                            $board_ids = array_merge($board_ids, $new_insert_data_diff_name);
                            $gn_display_class_subject->subject_id = json_encode($board_ids);
                            $gn_display_class_subject->save();
                        } else {
                            $gn_display_class =  new Gn_DisplayClassSubject();
                            $gn_display_class->classes_group_exams_id = $inputs['class_id'];
                            $gn_display_class->subject_id             = json_encode($new_insert_data_diff_name);
                            $gn_display_class->save();
                        }
                    }

                    if (!empty($new_insert_data_diff_id)) {
                        foreach ($new_insert_data_diff_id as $key => $value) {
                            $classSubjectMd         = new Subject();
                            $classSubjectMd->name   = $value;
                            $classSubjectMd->save();

                            $classSubjectMd_multipleMd                          = new Gn_ClassSubject();
                            $classSubjectMd_multipleMd->classes_group_exams_id  = $inputs['class_id'];
                            $classSubjectMd_multipleMd->subject_id              = $classSubjectMd->id;
                            $classSubjectMd_multipleMd->save();
                            $this->data['classSubjectMd_multipleMd_name'][$key] = $classSubjectMd->id;
                        }

                        $gn_display_class_subject = Gn_DisplayClassSubject::where('classes_group_exams_id', '=', $inputs['class_id'])->first();
                        if (!empty($gn_display_class_subject)) {
                            $board_ids = json_decode($gn_display_class_subject->subject_id);
                            $board_ids = array_merge($board_ids, $this->data['classSubjectMd_multipleMd_name']);
                            $gn_display_class_subject->subject_id = json_encode($board_ids);
                            $gn_display_class_subject->save();
                        } else {
                            $gn_display_class =  new Gn_DisplayClassSubject();
                            $gn_display_class->classes_group_exams_id = $inputs['class_id'];
                            $gn_display_class->subject_id             = json_encode($this->data['classSubjectMd_multipleMd_name']);
                            $queryMd                                  = $gn_display_class;
                            $query                                    = $queryMd->save();
                        }
                    }
                } else {
                    foreach ($inputs['name'] as $key => $value) {

                        $cleanedValue = preg_replace('/[^a-zA-Z\s]/', '', $value);
                        $subjectMd       = new Subject();
                        $subjectMd->name = $cleanedValue;
                        $subjectMd->save();

                        $this->data['temp_subject_id'][$key] = $subjectMd->id;

                        $gn_ClassSubject                         = new Gn_ClassSubject();
                        $gn_ClassSubject->classes_group_exams_id = $inputs['class_id'];
                        $gn_ClassSubject->subject_id             = $subjectMd->id;
                        $queryMd                                 = $gn_ClassSubject;
                        $query                                   = $queryMd->save();
                    }

                    $gn_display_class_subject = Gn_DisplayClassSubject::where('classes_group_exams_id', '=', $inputs['class_id'])->first();
                    if (!empty($gn_display_class_subject)) {
                        $board_ids = json_decode($gn_display_class_subject->subject_id);
                        $board_ids = array_merge($board_ids, $this->data['temp_subject_id']);
                        $gn_display_class_subject->subject_id = $board_ids;
                        $gn_display_class_subject->save();
                    } else {
                        $gn_DisplayClassSubject                         = new Gn_DisplayClassSubject();
                        $gn_DisplayClassSubject->classes_group_exams_id = $inputs['class_id'];
                        $gn_DisplayClassSubject->subject_id             = json_encode($this->data['temp_subject_id']);
                        $gn_DisplayClassSubject->save();
                    }
                }
            }
            if ($inputs['form_name'] == 'part_form') {
                $requestName = 'Subject Part';
                $requestType = 'part';

                if ($inputs['id'] > 0) {
                    // dd($req->all());
                    $all_subject_part_id    = SubjectPart::get()->pluck('id')->toArray();
                    $subject_part_id_data   = SubjectPart::where("classes_group_exams_id", $inputs['class_id'])->where('subject_id', $inputs['subject_id'])->get()->pluck('id')->toArray();

                    $get_subject_part_name      = array_diff($inputs['name'], $all_subject_part_id);
                    $get_subject_part_id        = array_diff($inputs['name'], $subject_part_id_data);
                    $get_subject_part_id        = array_diff($get_subject_part_id, $get_subject_part_name);
                    $delete_subject_part_id     = array_diff($subject_part_id_data, $inputs['name']);

                    // dd($get_subject_part_name,$get_subject_part_id,$delete_subject_part_id);
                    if (!empty($get_subject_part_name)) {
                        foreach ($get_subject_part_name as $key => $value) {
                            $subjectPartMd                          = new SubjectPart();
                            $subjectPartMd->name                    = $value;
                            $subjectPartMd->subject_id              = $inputs['subject_id'];
                            $subjectPartMd->save();
                            $this->data['subjectPartMd_ids'][$key]  = $subjectPartMd->id;
                        }
                        $gn_subject_part = Gn_DisplaySubjectPart::where("classes_group_exams_id", $inputs['class_id'])->where('subject_id', $inputs['subject_id'])->first();
                        if (!empty($gn_subject_part)) {
                            $subject_part_id                    = json_decode($gn_subject_part->subject_part_id);
                            $subject_part_id                    = array_merge($subject_part_id, $this->data['subjectPartMd_ids']);
                            $gn_subject_part->subject_id        = $inputs['subject_id'];
                            $gn_subject_part->subject_part_id   = json_encode($subject_part_id);
                            $queryMd                            = $gn_subject_part;
                            $query                              = $queryMd->save();
                        } else {
                            $gn_DisplaySubjectPart                      = new Gn_DisplaySubjectPart();
                            $gn_DisplaySubjectPart->subject_id          = $inputs['subject_id'];
                            $gn_DisplaySubjectPart->subject_part_id     = json_encode($this->data['subjectPartMd_ids']);
                            $queryMd                                    = $gn_DisplaySubjectPart;
                            $query                                      = $queryMd->save();
                        }
                    }

                    if (!empty($get_subject_part_id)) {
                        $subject_part_name = SubjectPart::where("classes_group_exams_id", $inputs['class_id'])->whereIn('id', $get_subject_part_id)->get()->pluck('name')->toArray();
                        foreach ($subject_part_name as $key => $value) {
                            $subjectPartMd                          = new SubjectPart();
                            $subjectPartMd->name                    = $value;
                            $subjectPartMd->subject_id              = $inputs['subject_id'];
                            $subjectPartMd->save();
                            $this->data['subjectPartMd_ids1'][$key] = $subjectPartMd->id;
                        }
                        $gn_subject_part = Gn_DisplaySubjectPart::where("classes_group_exams_id", $inputs['class_id'])->where('subject_id', $inputs['subject_id'])->first();
                        if (!empty($gn_subject_part)) {
                            $subject_part_id                    = json_decode($gn_subject_part->subject_part_id);
                            $subject_part_id                    = array_merge($subject_part_id, $this->data['subjectPartMd_ids1']);
                            $gn_subject_part->subject_id        = $inputs['subject_id'];
                            $gn_subject_part->subject_part_id   = json_encode($subject_part_id);
                            $queryMd                            = $gn_subject_part;
                            $query                              = $queryMd->save();
                        } else {
                            $gn_DisplaySubjectPart                      = new Gn_DisplaySubjectPart();
                            $gn_DisplaySubjectPart->subject_id          = $inputs['subject_id'];
                            $gn_DisplaySubjectPart->subject_part_id     = json_encode($this->data['subjectPartMd_ids1']);
                            $queryMd                                    = $gn_DisplaySubjectPart;
                            $query                                      = $queryMd->save();
                        }
                    }
                    if (!empty($delete_subject_part_id)) {
                        $gn_delete_display_subject_part                     = Gn_DisplaySubjectPart::where("classes_group_exams_id", $inputs['class_id'])->where('subject_id', $inputs['subject_id'])->first();
                        $update_subject_id                                  = json_decode($gn_delete_display_subject_part->subject_part_id);
                        $update_subject_id                                  = array_diff($update_subject_id, $delete_subject_part_id);
                        $gn_delete_display_subject_part->subject_part_id    = json_encode($update_subject_id);
                        $gn_delete_display_subject_part->save();

                        $gn_delete_subject_part     = SubjectPart::where("classes_group_exams_id", $inputs['class_id'])->whereIn('id', $delete_subject_part_id);
                        $gn_delete_subject_chapter  = SubjectPartLesson::where("classes_group_exams_id", $inputs['class_id'])->where('subject_id', $inputs['subject_id'])->whereIn('subject_part_id', $delete_subject_part_id);
                        $gn_delete_display_chapter  = Gn_DisplaySubjectPartChapter::where("classes_group_exams_id", $inputs['class_id'])->where('subject_id', $inputs['subject_id'])->whereIn('subject_part_id', $delete_subject_part_id);

                        $gn_delete_subject_part->delete();
                        $gn_delete_subject_chapter->delete();
                        $gn_delete_display_chapter->delete();
                    }
                    // dd($subject_id_data,$subject_id);
                    // $subjectPartMd              = SubjectPart::find($inputs['id']);
                    // $subjectPartMd->name        = $inputs['name'];
                    // $subjectPartMd->subject_id  = $inputs['subject_id'];
                    // $queryMd                    = $subjectPartMd;
                    // $query                      = $queryMd->save();
                } else {
                    foreach ($inputs['name'] as $key => $value) {
                        $subjectPartMd                          = new SubjectPart();
                        $subjectPartMd->classes_group_exams_id  = $inputs['class_id'];
                        $subjectPartMd->name                    = $value;
                        $subjectPartMd->subject_id              = $inputs['subject_id'];
                        $subjectPartMd->save();
                        $this->data['subjectPartMd_ids'][$key]  = $subjectPartMd->id;
                    }
                    $gn_subject_part = Gn_DisplaySubjectPart::where('classes_group_exams_id', $inputs['class_id'])->where('subject_id', $inputs['subject_id'])->first();
                    if (!empty($gn_subject_part)) {
                        $subject_part_id                    = json_decode($gn_subject_part->subject_part_id);
                        $subject_part_id                    = array_merge($subject_part_id, $this->data['subjectPartMd_ids']);
                        $gn_subject_part->subject_id        = $inputs['subject_id'];
                        $gn_subject_part->subject_part_id   = json_encode($subject_part_id);
                        $queryMd                            = $gn_subject_part;
                        $query                              = $queryMd->save();
                    } else {
                        $gn_DisplaySubjectPart                          = new Gn_DisplaySubjectPart();
                        $gn_DisplaySubjectPart->classes_group_exams_id  = $inputs['class_id'];
                        $gn_DisplaySubjectPart->subject_id              = $inputs['subject_id'];
                        $gn_DisplaySubjectPart->subject_part_id         = json_encode($this->data['subjectPartMd_ids']);
                        $queryMd                                        = $gn_DisplaySubjectPart;
                        $query                                          = $queryMd->save();
                    }
                }
            }
            if ($inputs['form_name'] == 'lesson_form') {
                $requestName = 'Subject Part Lesson';
                $requestType = 'lesson';

                if ($inputs['id'] > 0) {
                    $subject_part_chapter_id_all = SubjectPartLesson::get()->pluck('id')->toArray();
                    $subject_part_chapter_id     = SubjectPartLesson::where('classes_group_exams_id', $inputs['class_id'])->where('subject_id', $inputs['subject_id'])->where('subject_part_id', $inputs['subject_part_id'])->get()->pluck('id')->toArray();

                    $get_subject_part_chapter_name      = array_diff($inputs['name'], $subject_part_chapter_id_all);
                    $get_subject_part_chapter_id        = array_diff($inputs['name'], $subject_part_chapter_id);
                    $get_subject_part_chapter_id        = array_diff($get_subject_part_chapter_id, $get_subject_part_chapter_name);
                    $delete_subject_part_chapter_id     = array_diff($subject_part_chapter_id, $inputs['name']);

                    if (!empty($get_subject_part_chapter_name)) {
                        foreach ($get_subject_part_chapter_name as $key => $value) {
                            $subjectPartLessonMd                            = new SubjectPartLesson();
                            $subjectPartLessonMd->name                      = $value;
                            $subjectPartLessonMd->subject_id                = $inputs['subject_id'];
                            $subjectPartLessonMd->subject_part_id           = $inputs['subject_part_id'];
                            $subjectPartLessonMd->save();
                            $this->data['subjectPartLessonMd_ids'][$key]    =  $subjectPartLessonMd->id;
                        }
                        $update_chapter_display = Gn_DisplaySubjectPartChapter::where('classes_group_exams_id', $inputs['class_id'])->where('subject_id', $inputs['subject_id'])->where('subject_part_id', $inputs['subject_part_id'])->first();
                        if (!empty($update_chapter_display)) {
                            $chapter_id                                 = json_decode($update_chapter_display->chapter_id);
                            $chapter_id                                 = array_merge($chapter_id, $this->data['subjectPartLessonMd_ids']);
                            $update_chapter_display->subject_id         = $inputs['subject_id'];
                            $update_chapter_display->subject_part_id    = $inputs['subject_part_id'];
                            $update_chapter_display->chapter_id         = json_encode($chapter_id);
                            $queryMd                                    = $update_chapter_display;
                            $query                                      = $queryMd->save();
                        } else {
                            $gn_DisplaySubjectPartChapter       = new Gn_DisplaySubjectPartChapter();
                            $gn_DisplaySubjectPartChapter->subject_id       = $inputs['subject_id'];
                            $gn_DisplaySubjectPartChapter->subject_part_id  = $inputs['subject_part_id'];
                            $gn_DisplaySubjectPartChapter->chapter_id       = json_encode($this->data['subjectPartLessonMd_ids']);
                            $queryMd    = $gn_DisplaySubjectPartChapter;
                            $query      = $queryMd->save();
                        }
                    }

                    if (!empty($get_subject_part_chapter_id)) {
                        $subject_chapter_name     = SubjectPartLesson::where('classes_group_exams_id', $inputs['class_id'])->where('subject_id', $inputs['subject_id'])->where('subject_part_id', $inputs['subject_part_id'])->get()->pluck('name')->toArray();
                        foreach ($subject_chapter_name as $key => $value) {
                            $subjectPartLessonMd                    = new SubjectPartLesson();
                            $subjectPartLessonMd->name              = $value;
                            $subjectPartLessonMd->subject_id        = $inputs['subject_id'];
                            $subjectPartLessonMd->subject_part_id   = $inputs['subject_part_id'];
                            $subjectPartLessonMd->save();
                            $this->data['subjectPartLessonMd_ids1'][$key]  =  $subjectPartLessonMd->id;
                        }
                        $update_chapter_display = Gn_DisplaySubjectPartChapter::where("classes_group_exams_id", $inputs['class_id'])->where('subject_id', $inputs['subject_id'])->where('subject_part_id', $inputs['subject_part_id'])->first();
                        if (!empty($update_chapter_display)) {
                            $chapter_id                                 = json_decode($update_chapter_display->chapter_id);
                            $chapter_id                                 = array_merge($chapter_id, $this->data['subjectPartLessonMd_ids1']);
                            $update_chapter_display->subject_id         = $inputs['subject_id'];
                            $update_chapter_display->subject_part_id    = $inputs['subject_part_id'];
                            $update_chapter_display->chapter_id         = json_encode($chapter_id);
                            $queryMd                                    = $update_chapter_display;
                            $query                                      = $queryMd->save();
                        } else {
                            $gn_DisplaySubjectPartChapter                   = new Gn_DisplaySubjectPartChapter();
                            $gn_DisplaySubjectPartChapter->subject_id       = $inputs['subject_id'];
                            $gn_DisplaySubjectPartChapter->subject_part_id  = $inputs['subject_part_id'];
                            $gn_DisplaySubjectPartChapter->chapter_id       = json_encode($this->data['subjectPartLessonMd_ids1']);
                            $queryMd                                        = $gn_DisplaySubjectPartChapter;
                            $query                                          = $queryMd->save();
                        }
                    }

                    if (!empty($delete_subject_part_chapter_id)) {
                        $delete_chapter = SubjectPartLesson::whereIn('id', $delete_subject_part_chapter_id);
                        $delete_chapter->delete();

                        $delete_display_chapter             = Gn_DisplaySubjectPartChapter::where('classes_group_exams_id', $inputs['class_id'])->where('subject_id', $inputs['subject_id'])->where('subject_part_id', $inputs['subject_part_id'])->first();
                        $update_chapter_id                  = json_decode($delete_display_chapter->chapter_id);
                        $update_chapter_id                  = array_diff($update_chapter_id, $delete_subject_part_chapter_id);
                        $delete_display_chapter->chapter_id = json_encode($update_chapter_id);
                        $queryMd                            = $delete_display_chapter;
                        $query                              = $queryMd->save();
                    }
                } else {
                    foreach ($inputs['name'] as $key => $value) {
                        $subjectPartLessonMd                            = new SubjectPartLesson();
                        $subjectPartLessonMd->name                      = $value;

                        $subjectPartLessonMd->classes_group_exams_id    = $inputs['class_id'];
                        $subjectPartLessonMd->subject_id                = $inputs['subject_id'];
                        $subjectPartLessonMd->subject_part_id           = $inputs['subject_part_id'];
                        $subjectPartLessonMd->save();
                        $this->data['subjectPartLessonMd_ids'][$key]    =  $subjectPartLessonMd->id;
                    }
                    $update_chapter_display = Gn_DisplaySubjectPartChapter::where('classes_group_exams_id', $inputs['class_id'])->where('subject_id', $inputs['subject_id'])->where('subject_part_id', $inputs['subject_part_id'])->first();
                    if (!empty($update_chapter_display)) {
                        $chapter_id                                         = json_decode($update_chapter_display->chapter_id);
                        $chapter_id                                         = array_merge($chapter_id, $this->data['subjectPartLessonMd_ids']);
                        $update_chapter_display->classes_group_exams_id     = $inputs['class_id'];
                        $update_chapter_display->subject_id                 = $inputs['subject_id'];
                        $update_chapter_display->subject_part_id            = $inputs['subject_part_id'];
                        $update_chapter_display->chapter_id                 = json_encode($chapter_id);
                        $queryMd                                            = $update_chapter_display;
                        $query                                              = $queryMd->save();
                    } else {
                        $gn_DisplaySubjectPartChapter                           = new Gn_DisplaySubjectPartChapter();
                        $gn_DisplaySubjectPartChapter->subject_id               = $inputs['subject_id'];
                        $gn_DisplaySubjectPartChapter->classes_group_exams_id   = $inputs['class_id'];
                        $gn_DisplaySubjectPartChapter->subject_part_id          = $inputs['subject_part_id'];
                        $gn_DisplaySubjectPartChapter->chapter_id               = json_encode($this->data['subjectPartLessonMd_ids']);
                        $queryMd                                                = $gn_DisplaySubjectPartChapter;
                        $query                                                  = $queryMd->save();
                    }
                }
            }
            if ($inputs['form_name'] == 'gn_lesson_form') {
                $requestName = 'Add / Update - Lesson';
                $requestType = 'gn_lesson';

                if ($inputs['id'] > 0) {
                    $subject_lession_new                        = SubjectPartLesson::find($inputs['id']);
                    $subject_lession_new->name                  = $inputs['name'];
                    $subject_lession_new->subject_id            = $inputs['subject_id'];
                    $subject_lession_new->subject_part_id       = $inputs['subject_part_id'];
                    $subject_lession_new->subject_chapter_id    = $inputs['lesson_chapter_id'];
                    $queryMd = $subject_lession_new;
                    $query   = $queryMd->save();
                } else {
                    foreach ($inputs['name'] as $value) {
                        $subject_lession_new                        = new Gn_SubjectPartLessionNew();
                        $subject_lession_new->name                  = $value;
                        $subject_lession_new->subject_id            = $inputs['subject_id'];
                        $subject_lession_new->subject_part_id       = $inputs['subject_part_id'];
                        $subject_lession_new->subject_chapter_id    = $inputs['lesson_chapter_id'];
                        $queryMd = $subject_lession_new;
                        $query   = $queryMd->save();
                    }
                }
            }
            if ($query) {
                return redirect()->route('dashboard_subjects')->withErrors([$requestType . 'Success' => $requestName . ' succesfully added.']);
            } else {
                return back()->withErrors([$requestType . 'Error' => 'Server Error, please try again.']);
            }
        }
        $this->data['subjects']                 = Subject::get();
        $this->data['subject_data_display']     = Gn_DisplayClassSubject::get();

        $this->data['gn_subject_parts']         = SubjectPart::get();
        $this->data['subject_parts']            = Gn_DisplaySubjectPart::get();

        $this->data['gn_subject_part_lessons']  = SubjectPartLesson::get();
        $this->data['subject_part_lessons']     = Gn_DisplaySubjectPartChapter::get();

        $this->data['class_data']               = ClassGoupExamModel::get();
        $this->data['subject_part_lessons_new'] = Gn_SubjectPartLessionNew::get();

        return view('administrator.courses.subjects')->with('data', $this->data);
    }
    public function section(Request $req, $test_id = 0)
    {
        // $this->data['test'] = TestModal::find($test_id);
        $sections = TestSections::where('test_id', $test_id)->get();

        print_r($sections);
        return;
    }
    public function section_questions(Request $req, $test_id, $section_id)
    {
        $this->data['auth_id']          = Auth::id();
        $this->data['test']             = TestModal::find($test_id);
        $thisSection                    = TestSections::find($section_id);
        $this->data['section']          = $thisSection;
        $questions                      = TestQuestions::where(['test_id' => $test_id, 'section_id' => $section_id])->get();
        $this->data['questions']        = $questions;
        $this->data['total_questions']  = count($questions);

        $used_questions                 = $questions->pluck('question_id')->toArray();
        $unused_questions               = QuestionBankModel::where('subject', $thisSection->subject)->whereOr('subject_part', $thisSection->subject_part)->whereOr('subject_lesson_chapter', $thisSection->subject_part_lesson)->get()->pluck('id')->toArray();
        $unused_questions               = array_diff($unused_questions, $used_questions);

        $this->data['unused_questions']   = QuestionBankModel::findOrFail($unused_questions);
        // dd($this->data['unused_questions']);

        // print_r($questions);
        // return;

        return view('Dashboard/Admin/Exam/section_questions')->with('data', $this->data);
    }
    public function section_question_add(Request $req, $test_id, $section_id)
    {
        $this->data['test'] = TestModal::find($test_id);
        $thisSection = TestSections::find($section_id);
        $this->data['section'] = $thisSection;
        // print_r($thisSection);
        // return;

        return view('Dashboard/Admin/Exam/section_questions')->with('data', $this->data);
    }

    public function attemptTest(Request $req)
    {
        // $search_value = 'one';
        // $testTableData = TestModal::select(['id', 'title', 'test_type', 'sections', 'total_questions', 'questions_submitted','questions_approved','reviewed','reviewed_status','published_status','published'])
        // ->where("title", "like", "%".$search_value."%")->orderBy('id', 'desc')->skip(0)->take(10)->get();
        // $count = TestModal::count();
        // $json_data = array(
        //     // "draw" => intval($params['draw']),
        //     "recordsTotal" => $count,
        //     "recordsFiltered" => $count,
        //     "data" => $testTableData   // total data array
        // );
        // return print_r($json_data);
        $this->data['pagename'] = 'Test Attempts';
        if ($req->isMethod('post')) {
            $params['draw'] = $_REQUEST['draw'];
            $start = $_REQUEST['start'];
            $length = $_REQUEST['length'];
            /* If we pass any extra data in request from ajax */
            //$value1 = isset($_REQUEST['key1'])?$_REQUEST['key1']:"";

            /* Value we will get from typing in search */
            $search_value = $_REQUEST['search']['value'];

            // if (!empty($search_value)) {
            //     $testTableData = TestModal::select(['id', 'title', 'test_type', 'sections', 'total_questions', 'questions_submitted', 'questions_approved', 'reviewed', 'reviewed_status', 'published'])
            //         ->where("title", "like", "%" . $search_value . "%")
            //         ->orderBy('id', 'desc')->skip($start)->take($length)->get();
            //     $count = TestModal::where("title", "like", "%" . $search_value . "%")->count();
            // } else {
            //     $testTableData = TestModal::select(['id', 'title', 'test_type', 'sections', 'total_questions', 'questions_submitted', 'questions_approved', 'reviewed', 'reviewed_status', 'published'])->orderBy('id', 'desc')->skip($start)->take($length)->get();
            //     $count = TestModal::count();
            // }

            if (!empty($search_value)) {
                $testTableData = TestModal::selectselect([
                    'test.id as id',
                    'test.user_id',
                    'title',
                    'sections',
                    'total_questions',
                    'questions_submitted',
                    'questions_approved',
                    'reviewed',
                    'reviewed_status',
                    'published',
                    'test.created_at as created_at',
                    'education_type_child_id',
                    'published_status',
                    'users.name as username',
                    'franchise_details.institute_name as institute_name'
                ])
                    ->leftJoin('users', 'users.id', 'test.user_id')
                    ->leftJoin('franchise_details', 'franchise_details.user_id', 'users.id')
                    ->where("title", "like", "%" . $search_value . "%")
                    ->orderBy('id', 'desc')->skip($start)->take($length)->get();
                $count = TestModal::where("title", "like", "%" . $search_value . "%")->count();
            } else {
                $testTableData = TestModal::select([
                    'test.id as id',
                    'test.user_id',
                    'title',
                    'sections',
                    'total_questions',
                    'questions_submitted',
                    'questions_approved',
                    'reviewed',
                    'reviewed_status',
                    'published',
                    'test.created_at as created_at',
                    'education_type_child_id',
                    'published_status',
                    'users.name as username',
                    'franchise_details.institute_name as institute_name'
                ])
                    ->leftJoin('users', 'users.id', 'test.user_id')
                    ->leftJoin('franchise_details', 'franchise_details.user_id', 'users.id')
                    ->orderBy('id', 'desc')->skip($start)->take($length)->get();
                $count = TestModal::count();
            }

            foreach ($testTableData as $key => $testData) {
                // if ($testData['test_type'] == '1') {
                //     // $testTableData[$key]['type_text'] = 'Normal Test';
                // }
                // if ($testData['test_type'] == '2') {
                //     // $testTableData[$key]['type_text'] = 'Schedule Test';
                // }
                // if ($testData['test_type'] == '3') {
                //     // $testTableData[$key]['type_text'] = 'Practice Test';
                // }
                // dd();
                $total_questions = $testData['total_questions'];

                $testTableData[$key]['total_questions'] = $testData['total_questions'] . ' / ' . $testData->getQuestions()->wherePivot('deleted_at', '=', NULL)->count();
                $status = '';

                // dd($testData->getQuestions()->wherePivot('deleted_at','=',NULL)->count());
                // == 'true' ? $testData->getQuestions()->wherePivot('deleted_at','=',NULL)->count()  : '0'

                // $questionButton = '';
                if ($total_questions == 0) {
                    $status = '<span class="badge bg-warning text-dark">Awaiting Sections</span>';
                } else {
                    // $testData->getQuestions()->wherePivot('deleted_at','=',NULL)->count()
                    // if ($testData['total_questions'] !== $testData['questions_submitted'] || $testData['total_questions'] < $testData['questions_submitted']) {
                    if ($total_questions != $testData->getQuestions()->wherePivot('deleted_at', '=', NULL)->count() || $total_questions < $testData->getQuestions()->wherePivot('deleted_at', '=', NULL)->count()) {
                        $status = '<span class="badge bg-warning text-dark">Awaiting Questions</span>';
                        // $questionButton = '<a href="' . route('administrator.dashboard_test_section', [$testData['id']]) . '" title="Test Questions"><i class="bi bi-journal-text text-primary me-2"></i></a>';
                        // $questionButton = $sectionButtons;
                    } else {
                        if ($testData['reviewed']) {
                            if ($testData['reviewed_status'] == 'approved') {
                                $status = '<span class="badge bg-success">Approved</span>';
                            }
                            if ($testData['reviewed_status'] == 'rejected') {
                                $status = '<span class="badge bg-danger">Rejected</span>';
                            }
                            if ($testData['reviewed_status'] == 'onhold') {
                                $status = '<span class="badge bg-warning text-dark">Hold Review</span>';
                            }
                        } else {
                            $status = '<a href="' . route('administrator.dashboard_publish_test_exam', [$testData['id']]) . '"><span class="badge bg-primary">Publish Test</span></a>';
                        }
                        // if ($testData['published_status']) {
                        //     $status = '<span class="badge bg-primary">Published</span>';
                        // }
                        if ($testData['published'] == 1) {
                            $status = '<a href="' . route('administrator.dashboard_publish_test_exam', [$testData['id']]) . '"><span class="badge bg-success">Published</span></a>';
                        }
                        // if ($total_questions == $testData->getQuestions()->wherePivot('deleted_at','=',NULL)->count()) {
                        //     $status = '<a href="'.route('administrator.dashboard_publish_test_exam', [$testData['id']]).'"><span class="badge bg-primary">Published</span></a>';
                        // }
                    }
                }
                $sectionButtons = '';
                if ($testData['sections']) {
                    $sectionsX = TestSections::select('id')->where('test_id', $testData['id'])->get();
                    $sectionUrl = '';
                    $sectionButtons = '';
                    foreach ($sectionsX as $keyX => $sectionX) {
                        $sectionUrl = route('administrator.dashboard_test_section_question', [$testData['id'], $sectionX['id']]);
                        $sectionButtons .= '<a href="' . $sectionUrl . '" title="Section ' . ($keyX + 1) . ' Questions"><i class="bi bi-journal-text text-primary me-2"></i></a>';
                    }
                } else {
                    $sectionButtons = '0 Sections';
                }
                $testData['sections'] = $sectionButtons;

                // dd($testData);
                $testTableData[$key]['status']          = $status;
                $testTableData[$key]['created_by']      = $testData['institute_name'] != NULL ? $testData['institute_name'] : Auth::user()->name;
                $testTableData[$key]['created_date']    = date('d-m-Y', strtotime($testData->created_at));
                $testTableData[$key]['class_name']      = $testData->EducationClass->name;

                // <a href="' . route('administrator.dashboard_test_sections', [$testData['id']]) . '" title="Test Sections"><i class="bi bi-columns-gap text-primary me-2"></i></a>
                $actionsHtml = '<a href="' . route('administrator.dashboard_student_list', [$testData['id']]) . '" title="Student List"><span class="badge bg-warning text-dark">Student List</span></a>';

                // <a href="' . route('administrator.dashboard_update_test_exam', [$testData['id']]) . '" title="Edit Test"><i class="bi bi-pencil-square text-success me-2"></i></a>
                // <a href="javascript:void(0);" title="Delete Test"><i class="bi bi-trash2-fill text-danger me-2" onclick="deleteTest('.$testData['id'].')"></i></a>

                // $actionsHtml = $questionButton.'<a href="' . route('administrator.dashboard_test_sections', [$testData['id']]) . '" title="Test Sections"><i class="bi bi-columns-gap text-primary me-2"></i></a>
                // <a href="' . route('administrator.dashboard_update_test_exam', [$testData['id']]) . '" title="Edit Test"><i class="bi bi-pencil-square text-success me-2"></i></a>';
                $testTableData[$key]['actions'] = $actionsHtml;
            }

            $json_data = array(
                "draw"              => intval($params['draw']),
                "recordsTotal"      => $count,
                "recordsFiltered"   => $count,
                "data"              => $testTableData   // total data array
            );

            return json_encode($json_data);
        }
        return view('Dashboard/Admin/Exam/teststable')->with('data', $this->data);
    }

    public function studentList(Request $req, $test_id)
    {
        $this->data['pagename'] = 'Student List';
        // $studentListData = Gn_StudentTestAttempt::select(['gn__student_test_attempts.id','users.name as username','test.title'])
        //         ->leftJoin('users','users.id','gn__student_test_attempts.student_id')
        //         ->leftJoin('test','test.id','gn__student_test_attempts.test_id')
        //         ->orderBy('gn__student_test_attempts.id', 'desc')->get();
        //         dd($studentListData);
        if ($req->isMethod('post')) {
            $params['draw'] = $_REQUEST['draw'];
            $start          = $_REQUEST['start'];
            $length         = $_REQUEST['length'];
            /* If we pass any extra data in request from ajax */
            //$value1 = isset($_REQUEST['key1'])?$_REQUEST['key1']:"";

            /* Value we will get from typing in search */
            $search_value = $_REQUEST['search']['value'];

            if (!empty($search_value)) {

                $studentListData = Gn_StudentTestAttempt::select(['gn__student_test_attempts.id', 'users.name as username', 'test.title as title', 'gn__student_test_attempts.created_at as created_at', 'test.created_at as test_create'])
                    ->leftJoin('users', 'users.id', 'gn__student_test_attempts.student_id')
                    ->leftJoin('test', 'test.id', 'gn__student_test_attempts.test_id')
                    ->where('test.id', '=', $test_id)
                    ->orderBy('gn__student_test_attempts.id', 'desc')->skip($start)->take($length)->get();
                // $testTableData = TestModal::select(['id', 'title', 'sections', 'total_questions', 'questions_submitted', 'questions_approved', 'reviewed', 'reviewed_status', 'published','created_at','education_type_child_id','published_status'])
                //     ->where('user_id',Auth::user()->id)->orderBy('id', 'desc')
                //     ->where("title", "like", "%" . $search_value . "%")
                //     ->orderBy('id', 'desc')->skip($start)->take($length)->get();
                $count = Gn_StudentTestAttempt::get()->count();
            } else {
                $studentListData = Gn_StudentTestAttempt::select(['gn__student_test_attempts.id', 'users.name as username', 'test.title as title', 'gn__student_test_attempts.created_at as test_attempt', 'test.created_at as test_create'])
                    ->leftJoin('users', 'users.id', 'gn__student_test_attempts.student_id')
                    ->leftJoin('test', 'test.id', 'gn__student_test_attempts.test_id')
                    ->where('test.id', '=', $test_id)
                    ->orderBy('gn__student_test_attempts.id', 'desc')->skip($start)->take($length)->get();
                $count = Gn_StudentTestAttempt::count();
            }

            foreach ($studentListData as $key => $testData) {

                $studentListData[$key]['username']                = $testData->username;
                $studentListData[$key]['title']                   = $testData->title;
                $studentListData[$key]['test_attempt_date']       = date('d-m-Y', strtotime($testData->test_attempt));
                $studentListData[$key]['class_name']              = date('d-m-Y', strtotime($testData->test_create));
            }

            $json_data = array(
                "draw"              => intval($params['draw']),
                "recordsTotal"      => $count,
                "recordsFiltered"   => $count,
                "data"              => $studentListData   // total data array
            );

            return json_encode($json_data);
        }
        return view('/Dashboard/Admin/Exam/student_list')->with('data', $this->data);
    }

    public function getpackage(Request $req, $education_type_id, $class_group_exam_id, $value)
    {

        $arr = DB::table('gn__package_plans')->where('education_type', $education_type_id)->where('class', $class_group_exam_id)->where('package_category', $value)->get();

        foreach ($arr as $list) {
            echo "<option value=" . $list->id . ">$list->plan_name</option>";
        }
    }

    public function test_category(Request $req)
    {

        $result['test_cat'] = DB::table('test_cat')->get();

        return view('/Dashboard/Admin/Exam/test_category_list', $result);
    }

    public function manage_test_category(Request $req, $id = "")
    {
        // return $id;
        if ($id > 0) {
            $test_cat = DB::table('test_cat')->where('id', $id)->first();
            $result['cat_name'] = $test_cat->cat_name;
            $result['cat_image'] = $test_cat->cat_image;
            $result['id'] = $test_cat->id;
        } else {
            $result['cat_name'] = '';
            $result['cat_image'] = '';
            $result['id'] = '';
        }

        return view('/Dashboard/Admin/Exam/test_category_add', $result);
    }

    public function manage_test_cat_process(Request $req)
    {

        if ($req->post('id') > 0) {
            // Update existing record
            if (isset($req->cat_image)) {
                $imageUrl = $req->file('cat_image')->storeAs('cat_image', preg_replace('/\s+/', '', $req->file('cat_image')->getClientOriginalName()));
                DB::table('test_cat')->where('id', $req->post('id'))->update([
                    'cat_name' => $req->post('cat_name'),
                    'cat_image' => $imageUrl
                ]);
            } else {
                DB::table('test_cat')->where('id', $req->post('id'))->update([
                    'cat_name' => $req->post('cat_name'),
                ]);
            }
        } else {
            // Insert new record
            if (isset($req->cat_image)) {
                $imageUrl = $req->file('cat_image')->storeAs('cat_image', preg_replace('/\s+/', '', $req->file('cat_image')->getClientOriginalName()));
                DB::table('test_cat')->insert([
                    'cat_name' => $req->post('cat_name'),
                    'cat_image' => $imageUrl
                ]);
            } else {
                DB::table('test_cat')->insert([
                    'cat_name' => $req->post('cat_name'),
                ]);
            }
        }


        return redirect()->route('administrator.dashboard_add_test_category');
    }

    public function delete_category(Request $req, $id)
    {

        DB::table('test_cat')->where('id', $id)->delete();

        return redirect()->back();
    }

    public function test_feature_update(Request $request, $id)
    {

        $test = TestModal::where('id', $id)->first();
        if ($test) {
            if ($test->featured == 0) {
                $test->featured = 1;
                $test->save();
            } else {
                $test->featured = 0;
                $test->save();
            }

            $response = [
                'status' => 'success',
                'message' => 'Record updated',
            ];
            return response($response);
        }

        $response = [
            'status' => 'failed',
            'message' => 'No record found',
        ];
        return response($response);
    }
}
