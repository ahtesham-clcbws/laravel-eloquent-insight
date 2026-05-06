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
        Schema::create('scholorship_exam', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('instruction');
            $table->string('price');
            $table->string('image');
            $table->string('available_from');
            $table->string('available_to');
            $table->string('admit_Card_from');
            $table->string('result_on');
            $table->string('maximum_forms');
            $table->timestamps();
        });
        Schema::create('examsubjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scholorship_exam_id');
            $table->string('name');
            $table->integer('total_ques');
            $table->integer('marks');
            $table->timestamps();

            $table->foreign('scholorship_exam_id')->references('id')->on('scholorship_exam')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholorship_exam');
        Schema::dropIfExists('examsubjects');
    }
};
