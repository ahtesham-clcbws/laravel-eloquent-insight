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
            $table->boolean('isNew')->default(true);
        });
        Schema::table('contact_infos', function (Blueprint $table) {
            $table->boolean('isNew')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('isNew');
        });
        Schema::table('contact_infos', function (Blueprint $table) {
            $table->dropColumn('isNew');
        });
    }

};
