<?php

use App\Models\Student;
use Illuminate\Support\Facades\DB;
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
echo "Triggering N+1 violation...\n";
// Ensure we have some data
if (Student::count() === 0) {
    echo "No students found, creating some...\n";
    $state = \App\Models\State::firstOrCreate(['name' => 'Test State']);
    for ($i = 0; $i < 5; $i++) {
        Student::create(['name' => "Student {$i}", 'mobile' => "123456789{$i}", 'email' => "student{$i}@example.com", 'state_id' => $state->id, 'password' => bcrypt('password')]);
    }
}
$students = Student::limit(10)->with(['state'])->get();
foreach ($students as $student) {
    // This triggers N+1 if not eager loaded
    echo "Student: {$student->name}, State: {$student->state->name}\n";
}
echo "\nViolation should be captured now.\n";