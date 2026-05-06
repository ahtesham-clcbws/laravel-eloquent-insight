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
            $table->string('new_mobile')->nullable()->after('mobile');
            $table->integer('otp')->nullable()->after('new_mobile');
            $table->boolean('is_otp_verified')->default(false)->after('otp');
            $table->string('new_email')->nullable()->after('email');
            $table->string('email_code')->nullable()->after('new_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
};
