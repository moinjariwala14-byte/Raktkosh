<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blood_stocks', function (Blueprint $table) {
            $table->id('stock_id');
            $table->foreignId('blood_bank_id')->constrained('users')->onDelete('cascade');
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->unsignedInteger('units_available')->default(0);
            $table->date('expiry_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_stocks');
    }
};
