<?php

use App\Models\ScholarshipClaimGeneration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignIdFor(ScholarshipClaimGeneration::class)->after('is_final_submitted')->default(null)->nullable();
        });
    }
};
