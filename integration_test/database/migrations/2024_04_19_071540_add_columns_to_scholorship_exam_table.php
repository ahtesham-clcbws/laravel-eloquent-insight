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
        Schema::table('scholorship_exam', function (Blueprint $table) {
            $table->string('additional_column1')->nullable();
            $table->string('additional_column2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholorship_exam', function (Blueprint $table) {
            $table->string('additional_column1')->nullable();
            $table->string('additional_column2')->nullable();
        });
    }
};
