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
        Schema::table('corporate_coupon_requests', function (Blueprint $table) {
            $table->string('prefix')->nullable();
            $table->integer('numbers')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('corporate_coupon_requests', function (Blueprint $table) {
            $table->dropColumn('prefix');
            $table->dropColumn('numbers');
        });
    }
};
