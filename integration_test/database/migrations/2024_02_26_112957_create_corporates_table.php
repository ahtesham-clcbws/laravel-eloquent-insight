<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // In the up() method
        Schema::create('corporates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('institute_name');
            $table->string('type_institution');
            $table->string('interested_for');
            $table->string('established_year');
            $table->string('email');
            $table->string('phone');
            $table->string('otp');
            $table->string('address');
            $table->string('city');
            $table->string('pincode');
            $table->string('attachment')->nullable();
            $table->tinyInteger('is_otp_verified')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corporates');
    }
};
