<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_bank_id')->constrained('blood_banks')->onDelete('cascade');
            $table->string('unit_barcode')->unique(); // e.g., RCS-2026-A1092
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Bombay Phenotype']);
            $table->enum('component', ['Whole Blood', 'PRBC', 'FFP', 'Platelet Concentrate', 'Cryoprecipitate']);
            $table->enum('bag_volume', ['350ml', '450ml'])->default('350ml');
            $table->date('collection_date');
            $table->date('expiry_date');
            
            // Mandatory Testing Clearances (HIV, Hepatitis B/C, Syphilis, Malaria)
            $table->enum('testing_clearance_status', ['Pending', 'Cleared', 'Failed'])->default('Pending');
            $table->enum('status', ['Available', 'Reserved', 'Issued', 'Discarded'])->default('Available');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_batches');
    }
};
