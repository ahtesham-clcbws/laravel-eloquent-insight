<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\StudentCode;
use App\Models\StudentPaperExported;
use App\Services\StudentRankService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class StudentPapersImport implements ToCollection, WithHeadingRow
{
    protected $studentRankService;

    public function __construct()
    {
        $this->studentRankService = new StudentRankService;
    }

    public function collection(Collection $rows)
    {
        try {
            $rows = $rows->map(function ($row) {
                return collect($row)->mapWithKeys(function ($value, $key) {
                    return [strtolower($key) => $value];
                })->toArray();
            });

            foreach ($rows as $row) {
                $validator = Validator::make($row, [
                    'application_number' => 'required',
                    'wrong_answers' => 'required|integer',
                    'right_answers' => 'required|integer',
                ]);

                if ($validator->fails()) {
                    return back()->withErrors($validator);
                }

                $studentPapers = StudentPaperExported::where('app_code', $row['application_number'])->get();

                if ($studentPapers->isNotEmpty()) {
                    foreach ($studentPapers as $studPaper) {
                        $subjectName = str_replace(' ', '_', strtolower($studPaper->subject_name));

                        if (isset($row[$subjectName])) {
                            $subjPaper = $studPaper->subjectPaperDetail;
                            
                            // Calculate marks per question: max_marks / total_questions
                            $perQuestionMark = $subjPaper->total_questions > 0 ? ($subjPaper->max_marks / $subjPaper->total_questions) : 0;
                            
                            $subjectRightCount = intval($row[$subjectName])/$perQuestionMark;
                            
                            // Gross marks for this subject (without penalties)
                            $studPaper->obtained_marks = $subjectRightCount * $perQuestionMark;
                            $studPaper->wrong_answers = intval($row['wrong_answers']);
                            $studPaper->right_answers = intval($row['right_answers']);
                            $studPaper->attempted_questions = intval($row['wrong_answers']) + intval($row['right_answers']);
                            $studPaper->total_questions = $subjPaper->total_questions;
                            $studPaper->is_imported = true;
                            $studPaper->save();
                        }
                    }

                    $student = Student::find($studentPapers->first()?->student_id);
                    $appCode = $student?->latestStudentCode;

                    // Calculate total penalties using paper-wide totals from any row (they are all the same)
                    $firstPaper = $studentPapers->first();
                    $subjPaperDef = $firstPaper->subjectPaperDetail;
                    
                    $totalWrong = intval($row['wrong_answers']);
                    $totalRight = intval($row['right_answers']);
                    $totalQs = $studentPapers->sum('total_questions');
                    $totalAttempted = $totalWrong + $totalRight;
                    $totalSkipped = $totalQs - $totalAttempted;

                    $wrongPenalty = $subjPaperDef ? ($subjPaperDef->negative_marks_wrong ?? 0) : 0;
                    $skippedPenalty = $subjPaperDef ? ($subjPaperDef->negative_marks_skipped ?? 0) : 0;
                    
                    $totalPenalty = ($totalWrong * $wrongPenalty) + ($totalSkipped * $skippedPenalty);
                    
                    $grossObtainedMarks = $studentPapers->sum('obtained_marks');
                    $netObtainedMarks = $grossObtainedMarks - $totalPenalty;

                    $appCode->total_max_marks = $studentPapers->sum('max_marks');
                    $appCode->total_obtained_marks = $netObtainedMarks;
                    $appCode->percentage = ($appCode->total_max_marks > 0) ? round(($netObtainedMarks / $appCode->total_max_marks) * 100, 2) : 0;
                    $appCode->save();
                }
            }

            // Recalculate all ranks in bulk after import
            $this->studentRankService->recalculateAllRanks();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
