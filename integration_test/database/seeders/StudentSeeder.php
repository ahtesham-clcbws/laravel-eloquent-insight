<?php

namespace Database\Seeders;

use App\Models\ApplicationCodeList;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentCode;
use App\Models\StudentPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding students...');

        $fakeStudents = json_decode(file_get_contents(database_path('seeders/students.preview.json')), true);

        $getOldPayment = Payment::find(34);
        $getOldStudentPayment = StudentPayment::find(10);


        foreach ($fakeStudents as $key => $student) {
            if ($key > 67) {
                $student = new Student($student);
                $student->password = Hash::make(23988725);
                $student->login_password = 23988725;

                $student->is_gov_exam_participated = 'no';
                $student->is_apply_career_without_barrier = 'no';
                $student->family_income = 3;
                $student->mother_occupation = 'Housewife';
                $student->father_occupation = 'Private Job';
                $student->terms_conditions = 1;
                $student->photograph = '93948-1959402f2eb7fc3d79782fd3ae68763371297a-removebg-preview.png';
                $student->signature = '53540-224620images-removebg-preview4.png';

                $student->save();

                // create student application form
                $studentCode = new StudentCode();
                $studentCode->application_code = $this->generateAppCode($student);
                $studentCode->stud_id = $student->id;
                $studentCode->is_paid = 1;
                $studentCode->save();

                $transactionId = 'TRANS_' . time();

                $payment = new Payment();
                $payment->forceFill(collect($getOldPayment)->except('id')->all());
                $payment->r_payment_id = $transactionId;
                $payment->save();

                $studentPayment = new StudentPayment();
                $studentPayment->forceFill(collect($getOldStudentPayment)->except('id', 'student_id')->all());
                $studentPayment->student_id = $student->id;
                $studentPayment->payment_order_id = $transactionId;
                $studentPayment->save();

                $this->command->info('Students number: ' . $key + 1);
            }
        }
        $this->command->info('Students seeded!');
    }
    public function generateAppCode($student)
    {
        try {
            DB::beginTransaction();

            $city = $student->district->name;
            $cityPrefix = strtoupper(substr($city, 0, 3));

            // Check if the city already exists in the roll_numbers table
            $appCodeRecord = ApplicationCodeList::orderBy('last_app_code', 'desc')->first();

            if ($appCodeRecord) {
                $existCityAppCodeList = ApplicationCodeList::where('city', $city)->orderBy('last_app_code', 'desc')->first();

                if ($existCityAppCodeList) {
                    $defaultStartNumber = $appCodeRecord->last_app_code;
                    $defaultStartNumber =  $defaultStartNumber + 1;
                } else {
                    $defaultStartNumber = $appCodeRecord->last_app_code;
                    $additionalIncrement = 20;
                    $defaultStartNumber =  $defaultStartNumber + $additionalIncrement;
                }
                $appCodeRecord = new ApplicationCodeList();
                $appCodeRecord->city = $city;
                $appCodeRecord->last_app_code = $defaultStartNumber;
                $appCodeRecord->save();
            } else {

                $defaultStartNumber = 31010;

                $appCodeRecord = new ApplicationCodeList();

                $appCodeRecord->city = $city;
                $appCodeRecord->last_app_code = $defaultStartNumber;
                $appCodeRecord->save();
            }

            // Generate the full roll number
            $appCode = $appCodeRecord->last_app_code;
            $fullAppCodeList = $cityPrefix . $appCode;

            DB::commit();

            return $fullAppCodeList;
        } catch (\Throwable $th) {
            DB::rollBack();
            logger('Failed to generate App Code', [$th]);
        }
    }
}
