<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipClaimGeneration;
use App\Models\Student;
use Illuminate\Http\Request;

class AdminExamController extends Controller
{
    public function generateScholarshipEligibleStudents(Request $request)
    {
        // Step 1: Get all required parameters for eligibility
        $minEligibilityPercentage = $request->input('min_eligibility_percentage', 60);
        $maxStudents = $request->input('max_students', 50);

        // Step 2: Get the scholarship claim generation record
        $scholarshipClaim = ScholarshipClaimGeneration::create([
            'min_eligibility_percentage' => $minEligibilityPercentage,
            'max_students' => $maxStudents
        ]);

        // Step 3: Fetch all students
        $students = Student::with(['studentPaperExported', 'latestStudentCode'])->whereHas('studentCode', function ($query) use ($minEligibilityPercentage) {
            // Get students with percentage >= minimum eligibility percentage
            $query->where('percentage', '>=', $minEligibilityPercentage);
        })->get();

        // Step 4: Calculate total marks, right/wrong answers and disability
        $studentEligibilityData = $this->calculateStudentEligibility($students);

        // Step 5: Rank students based on conditions progressively until we get 50
        $eligibleStudents = $this->filterAndRankStudents($studentEligibilityData, $maxStudents);

        // Step 6: Assign the eligible students to the scholarship claim generation
        $this->assignScholarshipToStudents($eligibleStudents, $scholarshipClaim->id);

        // Get the count of eligible students
        $eligibleCount = count($eligibleStudents);

        return redirect()->back()->with('success', 'Scholarship eligibility generated successfully for ' . $eligibleCount . ' students.');
    }

    // Calculate each student's eligibility details
    private function calculateStudentEligibility($students)
    {
        $studentEligibilityData = [];

        foreach ($students as $student) {
            $latestCode = $student->latestStudentCode;
            
            if (!$latestCode) continue;

            $totalMarks = $latestCode->total_max_marks;
            $obtainedMarks = $latestCode->total_obtained_marks;
            $percentage = $latestCode->percentage;
            
            $rightAnswers = 0;
            $wrongAnswers = 0;

            // Get right/wrong answers summary from papers
            foreach ($student->studentPaperExported as $result) {
                // Since per-paper right/wrong answers are the paper-wide total,
                // we just take them from the first one if available, or sum if they were split (but they aren't).
                // Actually, our import saves the same total in every row, so we just take the first.
            }
            
            $firstPaper = $student->studentPaperExported->first();
            if ($firstPaper) {
                $rightAnswers = $firstPaper->right_answers;
                $wrongAnswers = $firstPaper->wrong_answers;
            }

            $age = $this->calculateAge($student->dob);

            $studentEligibilityData[] = [
                'student' => $student,
                'percentage' => $percentage,
                'obtained_marks' => $obtainedMarks,
                'right_answers' => $rightAnswers,
                'wrong_answers' => $wrongAnswers,
                'is_disabled' => $student->disability === 'Yes',
                'age' => $age,
            ];
        }

        return $studentEligibilityData;
    }

    // Filter and rank students progressively by conditions
    private function filterAndRankStudents($students, $maxStudents)
    {
        // Step 1: Filter by percentage (already above 70%) - this is pre-filtered from the calling method
        $eligibleStudents = $students;

        // Rank progressively until we have enough students
        $this->rankByObtainedMarks($eligibleStudents);
        $this->rankByRightAnswers($eligibleStudents);
        $this->rankByWrongAnswers($eligibleStudents);
        $this->rankByDisability($eligibleStudents);
        $this->rankByAge($eligibleStudents);

        // Finally, return the top students, limited by $maxStudents
        return array_slice($eligibleStudents, 0, $maxStudents);
    }

    // Rank by obtained marks (descending)
    private function rankByObtainedMarks(&$students)
    {
        usort($students, function ($a, $b) {
            return $b['obtained_marks'] <=> $a['obtained_marks'];
        });
    }

    // Rank by right answers (descending)
    private function rankByRightAnswers(&$students)
    {
        usort($students, function ($a, $b) {
            return $b['right_answers'] <=> $a['right_answers'];
        });
    }

    // Rank by wrong answers (ascending)
    private function rankByWrongAnswers(&$students)
    {
        usort($students, function ($a, $b) {
            return $a['wrong_answers'] <=> $b['wrong_answers'];
        });
    }

    // Rank by disability (disabled students first)
    private function rankByDisability(&$students)
    {
        usort($students, function ($a, $b) {
            return $b['is_disabled'] <=> $a['is_disabled'];
        });
    }

    // Rank by age (descending - older students first)
    private function rankByAge(&$students)
    {
        usort($students, function ($a, $b) {
            return $b['age'] <=> $a['age'];
        });
    }


    // Calculate the age from the date of birth
    private function calculateAge($dob)
    {
        return \Carbon\Carbon::parse($dob)->age;
    }

    // Assign eligible students to the scholarship
    private function assignScholarshipToStudents($eligibleStudents, $scholarshipClaimId)
    {
        // Extract the student IDs
        $studentIds = array_map(function ($student) {
            return $student['student']->id;
        }, $eligibleStudents);

        // Set scholarship_claim_generation_id for eligible students
        Student::whereIn('id', $studentIds)->update(['scholarship_claim_generation_id' => $scholarshipClaimId]);

        // Set scholarship_claim_generation_id to null for other students
        Student::whereNotIn('id', $studentIds)->update(['scholarship_claim_generation_id' => null]);
    }
}
