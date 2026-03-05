<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_appointments', function (Blueprint $table) { 
            $table->id('id_appointment');
            $table->unsignedBigInteger('id_patient');                        
            $table->foreign('id_patient')->references('id_patient')->on('patients')->cascadeOnDelete();
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->restrictOnDelete();
            $table->date('date');
            $table->time('hour');
            $table->enum('appointment_type', ['Review', 'Treatment', 'Emergency']);
            $table->enum('state', ['Pending', 'Completed', 'Canceled']);
            $table->text('reason')->nullable();
            $table->integer('duration_min')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_appointments');
    }
};