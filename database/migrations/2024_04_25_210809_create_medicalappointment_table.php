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

            $table->id('id_cita');
            $table->foreignId('id_pacient')->constrained('pacients', 'id_pacient');
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->date('date');
            $table->time('hour');
            $table->enum('appointment_type', ['Review', 'Treatment', 'Emergency']);
            $table->enum('state', ['Pending', 'Completed', 'Canceled']);
            $table->text('reason')->nullable();
            $table->integer('duracion_min')->nullable();
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
