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
        Schema::create('scholorship_form_models', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('name');
            $table->string('fathersname');
            $table->string('dob');
            $table->string('gender');
            $table->string('image');
            $table->string('sign');
            $table->string('disability');
            $table->string('address');
            $table->string('city');
            $table->string('qualification');
            $table->string('participate_exam');
            $table->string('center1');
            $table->string('center2');
            $table->string('center3');
            $table->string('exam1');
            $table->string('exam2')->nullable();
            $table->string('exam3')->nullable();
            $table->string('year')->nullable();
            $table->string('roll_no')->nullable();
            $table->string('family_income');
            $table->string('occupation');
            $table->tinyInteger('status')->default(1);
            $table->string('application_number')->nullable();
            $table->string('new_rollno')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholorship_form_models');
    }
};
