<?php

use App\Models\Student;
use App\Models\State;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Running Audit Scenarios...\n";

// Scenario 1: GHOST RELATION
// We eager load 'district' but we NEVER access it in the code below.
echo "1. Triggering Ghost Relation (loading 'district' but not using it)...\n";
$student = Student::with('district')->first();
echo "   Accessed Student: {$student->name}\n";

// Scenario 2: DUPLICATE QUERIES
// Running the exact same query multiple times in a row.
echo "2. Triggering Duplicate Queries...\n";
for ($i = 0; $i < 3; $i++) {
    $count = Student::count();
}
echo "   Ran count query 3 times.\n";

// Scenario 3: COMPLEX TOPOLOGY
// Calling a query through a helper to show the call chain in the graph.
function firstLevel() { secondLevel(); }
function secondLevel() { 
    echo "3. Triggering Topology Path...\n";
    State::first(); 
}

firstLevel();

echo "\nAudit data should be captured now. Run 'php artisan insight:audit --graph' to see results!\n";
