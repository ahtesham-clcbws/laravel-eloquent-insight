<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */


    //  $fullname=$request->full_name;
    //  $mobile=$request->mobile;
    //  $email=$request->email;
    //  $city=$request->city;
    //  $reason_contact=$request->reason_contact;
    //  $message=$request->message;

    public function up(): void
    {
        Schema::create('contact_infos', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('mobile');
            $table->string('email');
            $table->string('city');
            $table->longText('reason_contact');
            $table->longText('message');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_infos');
    }
};
