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
        Schema::create('student_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stud_id')->references('id')->on('students');
            $table->string('application_code');
            $table->string('coupan_code')->nullable();
            $table->string('coupan_value')->default(0);
            $table->boolean('is_coupan_code_applied')->default(0);
            $table->string('fee_amount')->default(0);
            $table->boolean('is_eligible_fee')->default(0);
            $table->boolean('is_paid')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_codes');
    }
};
