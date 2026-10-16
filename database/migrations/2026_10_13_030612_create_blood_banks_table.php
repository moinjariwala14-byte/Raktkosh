<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_banks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('license_number')->unique();
            $table->enum('category', ['Govt', 'Red Cross', 'Charitable', 'Private'])->default('Red Cross');
            $table->string('parent_hospital_name')->nullable();
            
            // Geographic Hierarchy
            $table->string('state')->default('Gujarat');
            $table->string('district');
            $table->string('city_taluka');
            $table->string('pincode', 10);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            
            // Contact & Facility Details
            $table->string('contact_person');
            $table->string('helpline_phone');
            $table->string('operating_hours')->default('24/7');
            
            // Component Preparation Facility Capabilities
            $table->boolean('has_component_facility')->default(true);
            $table->boolean('has_prbc')->default(true);
            $table->boolean('has_ffp')->default(true);
            $table->boolean('has_platelets')->default(true);
            $table->boolean('has_cryoprecipitate')->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_banks');
    }
};
