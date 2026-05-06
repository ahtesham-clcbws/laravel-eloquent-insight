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
        Schema::create('result_models', function (Blueprint $table) {
            $table->id();
            $table->string('rollno');
            $table->string('subject');
            $table->string('marks');
            $table->string('no_of_question');
            $table->string('right_answer');
            $table->string('wrong_answer');
            $table->tinyInteger('status')->default(1);
            $table->longText('remark');
            $table->string('exam_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_models');
    }
};
