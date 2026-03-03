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
        Schema::create('patients', function (Blueprint $table) {
            $table->id('id_patients');
            $table->string('CI', 20)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->date('date_of_birth')->nullable();
            $table->string('sex', ['M','F','Other'])->nullable();
            $table->string('address', 250)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('blood_type')->nullable();
            $table->string('allergies')->nullable();
            $table->timestamp('registration_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
