<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\ScholorshipFormModel;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsCsvExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            $examSubjects = $item->examSubjects->pluck('name')->toArray();

            $rowData = [
                $item->name,
                $item->fathersname,
                $item->dob,
                $item->gender,
                $item->city,
                $item->qualification,
                $item->participate_exam,
                $item->roll_no,
                $item->application_number,
                $item->new_rollno,
            ];

            // Dynamically add columns for each examSubject
            foreach ($examSubjects as $subject) {
                $rowData[] = ''; // Initial empty score for each subject
            }
            return $rowData;
        });
    }

    public function headings(): array
    {
        $examSubjects = $this->data->first()->examSubjects->pluck('name')->toArray();

        $headings = [
            'Name',
            'Father\'s Name',
            'Date of Birth',
            'Gender',
            'City',
            'Qualification',
            'Participate Exam',
            'Roll No',
            'Application Number',
            'New Roll No',
        ];

        // Add exam subject names as headings
        foreach ($examSubjects as $subject) {
            $headings[] = $subject;
        }


        return $headings;
    }
}
