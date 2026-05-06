<?php

namespace App\Livewire\Administrator\Dashboard;

use App\Models\BoardAgencyStateModel;
use App\Models\District;
use App\Models\DistrictScholarshipLimit;
use App\Models\EducationType;
use App\Models\State;
use App\Models\Student;
use App\Models\StudentCode;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('administrator.layouts.master')]
class StudentRollList extends Component
{
    use WithPagination;

    #[Url('perPage')]
    public $perPage = 5;

    #[Url('search')]
    public $search = '';

    #[Url('district_id')]
    public $district_id = '';

    #[Url('genders')]
    public $genders = [];

    #[Url('scholarhips')]
    public $scholarhips = [];

    #[Url('classes')]
    public $classes = [];

    #[Url('roll_number_filter')]
    public $roll_number_filter = 'A';

    public function updated()
    {
        $this->resetPage();
    }

    public function render()
    {
        $cities = District::orderBy('name')->get();

        $query = Student::query()
            ->with([
                'choiceCenterA',
                'studentPayment',
                'district',
                'qualifications',
                'scholarShipCategory',
                'scholarShipOptedFor',
                'latestStudentCode',
            ]);

        if ($this->district_id) {
            $query->where('district_id', $this->district_id);
        }

        if (!empty($this->genders)) {
            $query->whereIn('gender', $this->genders);
        }

        if (!empty($this->scholarhips)) {
            $query->whereIn('scholarship_category', $this->scholarhips);
        }

        if (!empty($this->classes)) {
            $query->whereIn('qualification', $this->classes);
        }

        // if ($this->roll_number_filter) {
        if ($this->roll_number_filter == 'B') {
            $query
                // ->with('latestStudentCode:student_codes.id,student_codes.roll_no,student_codes.stud_id,student_codes.created_at')
                ->whereHas('latestStudentCode', function ($q) {
                    $q->whereNotNull('roll_no');
                });
        } elseif ($this->roll_number_filter == 'C') {
            $query
                // ->with('latestStudentCode:student_codes.id,student_codes.roll_no,student_codes.stud_id,student_codes.created_at')
                ->whereHas('latestStudentCode', function ($q) {
                    $q->whereNull('roll_no');
                });
        } else {
            // $query->with('latestStudentCode:student_codes.id,student_codes.roll_no,student_codes.stud_id,student_codes.created_at');
        }
        // } else {
        //     $query->with('latestStudentCode:student_codes.id,student_codes.roll_no,student_codes.stud_id,student_codes.created_at');
        // }

        $query->withAggregate('latestStudentCode', 'roll_no');

        $query->where('is_final_submitted', 1);

        $query->orderBy('latest_student_code_roll_no');

        $students = $query->paginate($this->perPage);

        return view('livewire.administrator.dashboard.student-roll-list', compact('students', 'cities'));
    }

    public function clearFilter(?string $filter = null)
    {
        if ($filter == 'district') {
            $this->district_id = '';
        } elseif ($filter == 'gender') {
            $this->genders = [];
        } elseif ($filter == 'scholarhip') {
            $this->scholarhips = [];
        } elseif ($filter == 'class') {
            $this->classes = [];
        } else {
            $this->district_id = '';
            $this->genders = [];
            $this->scholarhips = [];
            $this->classes = [];
            $this->roll_number_filter = 'A';
        }
    }

    public function resetRollNumbers()
    {
        $studentsQuery = Student::select('id', 'district_id', 'scholarship_category', 'qualification', 'gender', 'is_final_submitted');
        if ($this->district_id) {
            $studentsQuery->where('district_id', $this->district_id);
        }
        if (!empty($this->scholarhips)) {
            $studentsQuery->whereIn('scholarship_category', $this->scholarhips);
        }
        if (!empty($this->classes)) {
            $studentsQuery->whereIn('qualification', $this->classes);
        }
        if (!empty($this->genders)) {
            $studentsQuery->whereIn('gender', $this->genders);
        }
        $studentsQuery->where('is_final_submitted', 1);
        $studentsQuery
            ->with('latestStudentCode:student_codes.id,student_codes.roll_no,student_codes.stud_id,student_codes.created_at')
            ->whereHas('latestStudentCode', function ($q) {
                $q->whereNotNull('roll_no');
            });
        $studentsQuery->orderBy('id', 'desc');
        $allStudents = $studentsQuery->get();
        $studentCodeIds = $allStudents->pluck('latestStudentCode.id')->toArray();
        // logger('studentCodeIds: ', $studentCodeIds);
        StudentCode::whereIn('id', $studentCodeIds)->update(['roll_no' => null]);
        return $this->js('alert("Rest Selected roll numbers successfull.")');
    }

    public function generateRollNumbers()
    {
        /*
        |--------------------------------------------------------------------------
        | SIMPLIFIED ROLL NUMBER GENERATION (Added by Antigravity)
        |--------------------------------------------------------------------------
        | Logic:
        | 1. Filter students who are final submitted and have no roll number.
        | 2. Join with districts to sort by City Name, then Student Name.
        | 3. Assign roll numbers sequentially starting from 100001 or current max + 1.
        */

        $query = Student::query()
            ->with('latestStudentCode')
            ->where('is_final_submitted', 1)
            ->whereHas('latestStudentCode', function ($q) {
                $q->whereNull('roll_no');
            });

        // Apply filters
        if ($this->district_id) {
            $query->where('district_id', $this->district_id);
        }

        if (!empty($this->scholarhips)) {
            $query->whereIn('scholarship_category', $this->scholarhips);
        }

        if (!empty($this->classes)) {
            $query->whereIn('qualification', $this->classes);
        }

        if (!empty($this->genders)) {
            $query->whereIn('gender', $this->genders);
        }

        // Sort by City Name and Student Name
        $query->join('districts', 'students.district_id', '=', 'districts.id')
            ->select('students.*')
            ->orderBy('districts.name')
            ->orderBy('students.name');

        $studentsToAssign = $query->get();

        if ($studentsToAssign->isEmpty()) {
            return $this->js('alert("No students found matching the filters without a roll number.")');
        }

        try {
            DB::beginTransaction();

            // Get the current highest roll number
            $maxRollNo = StudentCode::max('roll_no');
            $nextRollNo = max(100000, (int)$maxRollNo) + 1;

            foreach ($studentsToAssign as $student) {
                if ($student->latestStudentCode) {
                    $student->latestStudentCode->roll_no = (string)$nextRollNo;
                    $student->latestStudentCode->save();
                    $nextRollNo++;
                }
            }

            DB::commit();
            return $this->js('alert("Roll numbers generated successfully for ' . $studentsToAssign->count() . ' students starting from ' . (max(100000, (int)$maxRollNo) + 1) . '.")');

        } catch (\Exception $e) {
            DB::rollBack();
            logger('Roll number generation error: ' . $e->getMessage());
            return $this->js('alert("Error generating roll numbers: ' . $e->getMessage() . '")');
        }

        /*
        |--------------------------------------------------------------------------
        | END OF SIMPLIFIED ROLL NUMBER GENERATION
        |--------------------------------------------------------------------------
        */
    }
}
