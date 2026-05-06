<?php

namespace App\Exports;

use App\Models\StudentCode;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Student;
use App\Models\StudentPaperExported;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StudentsSubjectMarkFillExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithStyles, ShouldAutoSize
{
    public $students;
    public $rowNumber = 1;
    public $subjects;
    public $subjectMapping;

    public function __construct($subjectMapping)
    {
        // $this->students = Student::where('scholarship_category', $subjectMapping->education_type_id)
        //     ->whereIn('scholarship_opted_for', json_decode($subjectMapping->name))
        //     ->whereHas('latestStudentCode', function ($query) {
        //         $query->whereNotNull('roll_no')
        //             ->whereNotNull('application_code');
        //     })
        //     ->with(['latestStudentCode', 'scholarShipCategory'])
        //     ->join('student_codes', 'students.id', '=', 'student_codes.stud_id') // Join with the related table
        //     ->orderBy('student_codes.roll_no', 'asc') // Order by roll_no ascending
        //     ->select('students.*') // Select the student columns
        //     ->get();

        $this->students = Student::where('scholarship_category', $subjectMapping->education_type_id)
            ->whereIn('scholarship_opted_for', json_decode($subjectMapping->name))
            ->whereHas('latestStudentCode', function ($query) {
                $query->whereNotNull('roll_no')
                    ->whereNotNull('application_code');
            })
            ->with([
                'latestStudentCode' => function ($query) {
                    $query->orderBy('roll_no', 'asc'); // Order within the relationship
                },
                'scholarShipCategory'
            ]) // Eager load the necessary relationships
            ->orderBy(
                StudentCode::select('roll_no')
                    ->whereColumn('student_codes.stud_id', 'students.id')
                    ->limit(1)
            ) // Order by roll_no without a join
            ->distinct() // Ensure no duplicate students are returned
            ->get();



        // $this->students = Student::where('scholarship_category', $subjectMapping->education_type_id)
        //     ->whereIn('scholarship_opted_for', json_decode($subjectMapping->name))
        //     ->whereHas('latestStudentCode', function ($query) {
        //         $query->whereNotNull('roll_no')
        //             ->whereNotNull('application_code');
        //     })
        //     ->with(['latestStudentCode', 'scholarShipCategory'])
        //     ->get();
        // $this->students = Student::where('scholarship_category', $subjectMapping->education_type_id)
        //     ->whereIn('scholarship_opted_for', json_decode($subjectMapping->name))
        //     ->with(['latestStudentCode', 'scholarShipCategory'])
        //     ->get();


        $this->subjects = $subjectMapping->subjects();

        $this->subjectMapping = $subjectMapping;
    }

    public function collection()
    {
        return $this->students;
    }

    public function map($row): array
    {
        $allSubjects = [];

        foreach ($this->subjects as $subject) {
            $allSubjects[] = $subject->name;
        }

        $studPaperExporteds = StudentPaperExported::where('student_id', $row->id)->where('app_code', $row->latestStudentCode?->application_code)->orderBy('subject_id')->get();

        if ($studPaperExporteds->isEmpty()) {
            foreach ($this->subjects as $subject) {

                if ($row->latestStudentCode?->application_code && $row->latestStudentCode?->roll_no) {
                    $subjectPaper = DB::table('subject_paper_details')
                        ->where('subject_mapping_id', $this->subjectMapping->id)
                        ->where('subject_id', $subject->id)->get();

                    $studPaperExporteds = new StudentPaperExported;
                    $studPaperExporteds->student_id = $row->id;
                    $studPaperExporteds->subject_mapping_id = $this->subjectMapping->id;
                    $studPaperExporteds->app_code = $row->latestStudentCode?->application_code;
                    $studPaperExporteds->subject_id = $subject->id;
                    $studPaperExporteds->subject_name = $subject->name;
                    $studPaperExporteds->max_marks = $subjectPaper->sum('max_marks');
                    $studPaperExporteds->save();
                }
            }
        }

        if ($row->latestStudentCode?->application_code && $row->latestStudentCode?->roll_no) {
            return [
                $this->rowNumber++,
                $studPaperExporteds->first()?->id,
                ucfirst($row->name),
                // $this->calculateAge($row->dob),
                $row->disability,
                $row->father_name,
                // $row->gender == 'Male' ? 'M' : ($row->gender == 'Female' ? 'F' : 'T'),
                $row->gender,
                date('d-m-Y', strtotime($row->dob)),
                $row->state?->name,
                $row->district?->name,
                $row->latestStudentCode?->application_code,
                $row->latestStudentCode?->roll_no,
                $row->scholarShipCategory?->name,
                '',
                ''
            ];
        }
        return null;
    }
    private function calculateAge($dob)
    {
        return \Carbon\Carbon::parse($dob)->age;
    }

    public function headings(): array
    {
        $headings = [
            'Sr.No',
            'student_id',
            'Name',
            // 'Age',
            'Disabled',
            'Father\'s Name',
            'Gender',
            'Date of Birth',
            'State',
            'City',
            'Application Number',
            'Roll No',
            'Scholarship Category',
        ];

        // Add exam subject names as headings
        foreach ($this->subjects as $subject) {
            $headings[] = $subject->name;
        }
        $headings[] = 'Wrong Answers';
        $headings[] = 'Right Answers';
        return $headings;
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FF0000']]],
        ];
    }
}
