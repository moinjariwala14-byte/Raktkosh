<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_bank_id')->constrained('blood_banks')->onDelete('cascade');
            $table->string('camp_title');
            $table->string('organizing_institution'); // NGO, College, Club
            $table->text('venue_address');
            $table->string('state')->default('Gujarat');
            $table->string('district');
            $table->string('city_taluka');
            $table->date('camp_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('expected_registrations')->default(50);
            $table->string('nodal_officer_contact');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camps');
    }
};
