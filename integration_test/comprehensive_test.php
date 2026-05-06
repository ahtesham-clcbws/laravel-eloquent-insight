<?php

use App\Models\Student;
use App\Models\State;
use Illuminate\Support\Facades\DB;
require __DIR__ . '/vendor/autoload.php';
// FORCE ENABLE VIA ENV BEFORE BOOTSTRAP
putenv('INSIGHT_ENABLED=true');
putenv('INSIGHT_AUDIT_ENABLED=true');
putenv('INSIGHT_TOPOLOGY_ENABLED=true');
putenv('INSIGHT_DUPLICATES_ENABLED=true');
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
// FORCE START THE INTERCEPTOR MANUALLY TO BE 100% SURE
app(\EloquentInsight\Collectors\PerformanceInterceptor::class)->register();
// CLEAR PREVIOUS DATA
app('insight')->clear();
echo "🔥 STARTING COMPREHENSIVE PERFORMANCE CHAOS TEST 🔥\n\n";
if (Student::count() === 0) {
    echo "Seed data missing. Please run migrations/seeders first.\n";
    exit;
}
// 1. SCENARIO: N+1 VIOLATION
echo "🚩 Triggering N+1 Violation...\n";
$students = Student::limit(5)->with(['state'])->with(['state'])->with(['state'])->with(['state'])->get();
foreach ($students as $student) {
    echo "   - Student: {$student->name}, State: {$student->state->name}\n";
}
// 2. SCENARIO: GHOST RELATION
echo "\n🚩 Triggering Ghost Relation (Loaded 'district' but unused)...\n";
$ghostlyStudent = Student::with('district')->with(['district'])->with(['district'])->with(['district'])->with(['district'])->with(['district'])->first();
echo "   - Loaded student {$ghostlyStudent->name} with district.\n";
// 3. SCENARIO: DUPLICATE QUERIES
echo "\n🚩 Triggering Duplicate Queries...\n";
for ($i = 0; $i < 5; $i++) {
    $count = DB::table('students')->count();
}
// 4. SCENARIO: HYDRATION INEFFICIENCY
echo "\n🚩 Triggering Hydration Inefficiency...\n";
$heavyStudent = Student::first();
echo "   - Student Name: {$heavyStudent->name}\n";
// 5. SCENARIO: COMPLEX TOPOLOGY
function adminDashboard()
{
    generateReport();
}
function generateReport()
{
    echo "\n🚩 Triggering Complex Topology Path...\n";
    State::limit(1)->get();
}
adminDashboard();
echo "\n✅ CHAOS TEST COMPLETE!\n";
echo "Run 'php artisan insight:list' for N+1 issues.\n";
echo "Run 'php artisan insight:audit' for the efficiency report.\n";