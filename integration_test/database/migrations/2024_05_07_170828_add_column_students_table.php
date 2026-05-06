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

        Schema::table('students', function (Blueprint $table) {
            $table->string('father_name')->nullable();
            $table->string('dob')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->string('address')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('subject')->nullable();
            $table->string('pincode', 6)->nullable();
            $table->string('landmark')->nullable();
            $table->integer('form_step')->default(0);
            $table->string('qualification')->nullable();
            $table->string('scholarship_category')->nullable();
            $table->string('scholarship_opted_for')->nullable();
            $table->string('test_center_a')->nullable();
            $table->string('test_center_b')->nullable();
            $table->string('is_gov_exam_participated')->nullable();
            $table->string('is_apply_career_without_barrier')->nullable();
            $table->string('govt_exams_1')->nullable();
            $table->string('govt_exams_2')->nullable();
            $table->string('govt_exams_3')->nullable();
            $table->string('year', 4)->nullable();
            $table->string('roll_no')->nullable();
            $table->decimal('family_income', 10)->nullable();
            $table->string('parents_occupation')->nullable();
            $table->string('photograph')->nullable();
            $table->string('signature')->nullable();
            $table->boolean('terms_conditions')->default(false);

            // Define foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
};
