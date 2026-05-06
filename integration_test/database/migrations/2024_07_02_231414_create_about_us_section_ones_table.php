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
        Schema::create('aboutus_section_one', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('banner')->nullable();
            $table->text('description')->nullable();
            $table->string('service_a')->nullable();
            $table->string('service_a_image')->nullable();
            $table->text('service_a_description')->nullable();
            $table->string('service_b')->nullable();
            $table->string('service_b_image')->nullable();
            $table->text('service_b_description')->nullable();
            $table->string('service_c')->nullable();
            $table->string('service_c_image')->nullable();
            $table->text('service_c_description')->nullable();
            $table->string('service_d')->nullable();
            $table->string('service_d_image')->nullable();
            $table->text('service_d_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us_section_ones');
    }
};
