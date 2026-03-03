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
            $table->foreignId('id_pacient')->constrained('pacients', 'id_pacients');
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->date('date');
            $table->time('hour');
            $table->enum('tipo_cita', ['Revision', 'Tratamiento', 'Emergencia']);
            $table->enum('state', ['Pendiente', 'Completada', 'Cancelada']);
            $table->text('motivo')->nullable();
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
