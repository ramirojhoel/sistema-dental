<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_history', function (Blueprint $table) {
            $table->id('id_history');
            $table->unsignedBigInteger('id_patient');
            $table->foreign('id_patient')->references('id_patient')->on('patients')->cascadeOnDelete();
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->restrictOnDelete();
            $table->date('opening_date');
            $table->text('reason_for_consultation')->nullable();
            $table->text('background')->nullable();
            $table->text('current_medications')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_history'); 
    }
};