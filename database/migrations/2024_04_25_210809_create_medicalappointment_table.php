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
        Schema::create('medicalappointment', function (Blueprint $table) {
            $table->id();
            $table->text('revision_history')->nullable();
            $table->text('clinical_history')->nullable();
            $table->text('observations')->nullable();
            $table->dateTime('follow_up_appointment')->nullable();

            $table->boolean('follow_up')->nullable();
            $table->boolean('is_restorative_treatment')->nullable();
            $table->boolean('is_initial_evaluation')->nullable();
            $table->boolean('is_cleaning_and_checkup')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicalappointment');
    }
};
