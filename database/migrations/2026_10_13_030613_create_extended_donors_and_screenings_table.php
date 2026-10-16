<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('full_name');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->date('dob');
            $table->string('phone');
            $table->string('email')->nullable();
            
            // National Identity Reference field (Safe hashed or masked storage)
            $table->string('national_id_hash')->nullable();
            
            // Address Details
            $table->text('address');
            $table->string('state')->default('Gujarat');
            $table->string('district');
            $table->string('city_taluka');
            $table->string('pincode', 10);
            
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Bombay Phenotype']);
            $table->date('last_donation_date')->nullable();
            $table->enum('donation_type', ['Voluntary', 'Replacement', 'Apheresis'])->default('Voluntary');
            $table->boolean('is_eligible')->default(true);
            $table->string('certificate_number')->nullable()->unique();
            $table->timestamps();
        });

        // Clinical Screening / Mini-Physical table
        Schema::create('donor_screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('donors')->onDelete('cascade');
            $table->foreignId('blood_bank_id')->constrained('blood_banks')->onDelete('cascade');
            $table->decimal('weight_kg', 5, 2); // Rule: >= 45.0 kg
            $table->decimal('hemoglobin_g_dl', 4, 1); // Rule: >= 12.5 g/dL
            $table->integer('pulse_rate'); // Rule: 60 - 100 bpm
            $table->string('blood_pressure'); // e.g. 120/80
            
            $table->enum('screening_result', ['Fit', 'Temporary Deferral', 'Permanent Deferral']);
            $table->text('deferral_reason')->nullable();
            $table->date('deferral_until')->nullable();
            $table->string('screened_by_officer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donor_screenings');
        Schema::dropIfExists('donors');
    }
};
