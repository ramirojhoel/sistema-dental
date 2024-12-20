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
        Schema::create('patient_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('appointment');
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('treatment_id')->constrained('treatments');
            $table->text('observation');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_history');
    }
};
