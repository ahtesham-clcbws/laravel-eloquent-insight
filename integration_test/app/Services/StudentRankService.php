<?php
namespace App\Services;

use App\Models\Student;
use App\Models\StudentCode;
use Illuminate\Support\Facades\DB;

class StudentRankService
{
    public function recalculateAllRanks()
    {
        // Get all latest student codes with necessary student info
        $latestStudentCodes = StudentCode::select('student_codes.*', 'students.state_id', 'students.district_id', 'students.gender', 'students.id as student_id')
            ->join('students', 'students.id', '=', 'student_codes.stud_id')
            ->where('students.is_final_submitted', 1)
            ->whereNotNull('student_codes.percentage')
            ->orderByDesc('student_codes.percentage')
            ->get();

        if ($latestStudentCodes->isEmpty()) {
            return 0;
        }

        // 1. All India Rank
        $this->assignRanks($latestStudentCodes, 'rank');

        // 2. State Rank
        $latestStudentCodes->groupBy('state_id')->each(function ($group) {
            $this->assignRanks($group, 'state_rank');
        });

        // 3. District Rank
        $latestStudentCodes->groupBy('district_id')->each(function ($group) {
            $this->assignRanks($group, 'district_rank');
        });

        // 4. Gender Rank
        $latestStudentCodes->groupBy('gender')->each(function ($group) {
            $this->assignRanks($group, 'gender_rank');
        });

        // Bulk update
        foreach ($latestStudentCodes as $code) {
            StudentCode::where('id', $code->id)->update([
                'rank' => $code->rank,
                'state_rank' => $code->state_rank,
                'district_rank' => $code->district_rank,
                'gender_rank' => $code->gender_rank,
            ]);
        }

        return $latestStudentCodes->count();
    }

    private function assignRanks($collection, $field)
    {
        $rank = 1;
        
        // Sort by percentage descending, then by created_at (or id) ascending for stability
        // Using ID as tie-breaker ensures deterministic unique ranking
        $sorted = $collection->sort(function ($a, $b) {
            if ($a->percentage == $b->percentage) {
                return $a->id <=> $b->id; 
            }
            return $b->percentage <=> $a->percentage;
        });

        foreach ($sorted as $item) {
            $item->$field = $rank++;
        }
    }

    public function calculateOverallRank($studentId)
    {
        $student = Student::with('latestStudentCode')->findOrFail($studentId);
        $appCode = $student->latestStudentCode;
        if (!$appCode || $appCode->percentage === null) return null;

        return StudentCode::whereNotNull('percentage')
            ->where('percentage', '>', $appCode->percentage)
            ->count() + 1;
    }

    public function calculateStateRank($studentId)
    {
        $student = Student::with('latestStudentCode')->findOrFail($studentId);
        $appCode = $student->latestStudentCode;
        if (!$appCode || $appCode->percentage === null) return null;

        return StudentCode::join('students', 'students.id', '=', 'student_codes.stud_id')
            ->where('students.state_id', $student->state_id)
            ->whereNotNull('student_codes.percentage')
            ->where('student_codes.percentage', '>', $appCode->percentage)
            ->count() + 1;
    }

    public function calculateDistrictRank($studentId)
    {
        $student = Student::with('latestStudentCode')->findOrFail($studentId);
        $appCode = $student->latestStudentCode;
        if (!$appCode || $appCode->percentage === null) return null;

        return StudentCode::join('students', 'students.id', '=', 'student_codes.stud_id')
            ->where('students.district_id', $student->district_id)
            ->whereNotNull('student_codes.percentage')
            ->where('student_codes.percentage', '>', $appCode->percentage)
            ->count() + 1;
    }

    public function calculateGenderRank($studentId)
    {
        $student = Student::with('latestStudentCode')->findOrFail($studentId);
        $appCode = $student->latestStudentCode;
        if (!$appCode || $appCode->percentage === null) return null;

        return StudentCode::join('students', 'students.id', '=', 'student_codes.stud_id')
            ->where('students.gender', $student->gender)
            ->whereNotNull('student_codes.percentage')
            ->where('student_codes.percentage', '>', $appCode->percentage)
            ->count() + 1;
    }
}
