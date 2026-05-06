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
        Schema::create('courses_list', function (Blueprint $table) {
            $table->id();
            $table->longText('title')->nullable();
            $table->longText('overview');
            $table->string('otherdetails')->nullable();
            $table->string('notification')->nullable();
            $table->string('registration')->nullable();
            $table->string('exam_Date')->nullable();
            $table->string('exam_mode')->nullable();
            $table->string('exam_pattern')->nullable();
            $table->string('vacancies')->nullable();
            $table->string('pay_scale')->nullable();
            $table->string('age_criteria')->nullable();
            $table->string('eligibility')->nullable();
            $table->longText('official_site')->nullable();
            $table->string('notification_file_path')->nullable();
            $table->string('exam_details_file_path')->nullable();
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses_list');
    }
};
