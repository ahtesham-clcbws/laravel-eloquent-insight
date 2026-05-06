<?php

use App\Models\District;
use App\Models\EducationType;
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
        Schema::create('district_scholarship_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(District::class);
            $table->foreignIdFor(EducationType::class);
            $table->integer('max_registration_limit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('district_scholarship_limits');
    }
};
