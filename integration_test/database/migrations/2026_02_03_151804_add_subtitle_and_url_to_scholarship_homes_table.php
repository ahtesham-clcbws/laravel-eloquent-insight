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
        Schema::table('scholarship_homes', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('education_type_id');
            $table->string('url')->nullable()->after('remark');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholarship_homes', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'url']);
        });
    }
};
