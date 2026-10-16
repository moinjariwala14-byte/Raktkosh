<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_requisitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_bank_id')->nullable()->constrained('blood_banks')->onDelete('set null');
            $table->string('patient_name');
            $table->integer('age');
            $table->string('admitted_hospital');
            $table->string('treating_doctor');
            $table->string('diagnosis_indication'); // e.g., Trauma, Dialysis, Surgery
            $table->string('attendant_contact', 15);
            
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Bombay Phenotype']);
            $table->enum('component', ['Whole Blood', 'PRBC', 'FFP', 'Platelet Concentrate', 'Cryoprecipitate']);
            $table->integer('units_required')->default(1);
            $table->enum('urgency_level', ['Elective', 'Emergency', 'SOS'])->default('Emergency');
            
            // e-RaktKosh Standard Clinical State Pipeline
            $table->enum('workflow_state', [
                'Pending', 
                'Cross-Match Verified', 
                'Approved / Issued', 
                'Fulfilled', 
                'Rejected'
            ])->default('Pending');
            
            $table->text('clinical_remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_requisitions');
    }
};
