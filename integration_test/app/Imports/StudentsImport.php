<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class StudentsImport implements ToArray
{
    public function array(array $rows): array
    {
        $data = [];

        // Skip the first row
        array_shift($rows);

        // Loop through each row of the Excel file
        foreach ($rows as $row) {
            // Extract the data from each row
            $rowData = [
                'name' => $row[0],
                'age' => $row[1],
                'dob' => $row[2],
                // Add more fields as needed
            ];

            // Push the extracted data to the $data array
            $data[] = $rowData;
        }

        return $data;
    }
}
