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
        Schema::create('company_models', function (Blueprint $table) {
            $table->id();
            $table->string('softwareurl')->nullable();
            $table->string('companyname')->nullable();
            $table->string('shortname')->nullable();
            $table->string('cin')->nullable();
            $table->string('pan')->nullable();
            $table->string('tan')->nullable();
            $table->string('gst')->nullable();
            $table->string('logo')->nullable();
            $table->string('companycategory')->nullable();
            $table->string('companyclass')->nullable();
            $table->string('authorizedcapital')->nullable();
            $table->string('paidupcapital')->nullable();
            $table->string('sharenominalvalue')->nullable();
            $table->string('stateofregistration')->nullable();
            $table->string('incorporationdate')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('landlineno')->nullable();
            $table->string('whatsappno')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('razorpay_marchent_id')->nullable();
            $table->string('razorpay_marchent_key')->nullable();
            $table->string('sms_api_key')->nullable();
            $table->string('sms_api_link')->nullable();
            $table->string('address')->nullable();
            $table->string('about')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_models');
    }
};
