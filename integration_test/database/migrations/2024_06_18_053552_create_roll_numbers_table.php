<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('roll_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->integer('last_roll_number');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('roll_numbers');
    }
};
