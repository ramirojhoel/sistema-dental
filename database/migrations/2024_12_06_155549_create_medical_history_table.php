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
        Schema::create('medical_history', function (Blueprint $table)
        {
        $table->id('id_history');
        $table->foreignId('id_patient')->constrained('patients', 'id_patient');
        $table->foreignId('id_user')->constrained('users', 'id_user'); 
        $table->date('opening_date');
        $table->text('reason_for_consultation')->nullable();
        $table->text('background')->nullable();
        $table->text('current_medications')->nullable();
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
