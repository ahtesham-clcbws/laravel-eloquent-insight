<?php

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
        Schema::table('subject_paper_details', function (Blueprint $table) {
            $table->decimal('negative_marks_wrong', 8, 2)->default(0)->after('total_questions');
            $table->decimal('negative_marks_skipped', 8, 2)->default(0)->after('negative_marks_wrong');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_paper_details', function (Blueprint $table) {
            $table->dropColumn(['negative_marks_wrong', 'negative_marks_skipped']);
        });
    }
};
