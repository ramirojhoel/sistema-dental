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
        Schema::create('_xrays', function (Blueprint $table) {
        $table->id('id_radiography');
        $table->foreignId('id_history')->constrained('medical_history', 'id_history');
        $table->enum('type', ['Panoramic', 'Periapical', 'Bitewing']);
        $table->date('date');
        $table->string('archive_url', 500);
        $table->text('observations')->nullable();
        $table->timestamps();
        });

        Schema::create('odontograms', function (Blueprint $table) {
        $table->id('id_odontogram');
        $table->foreignId('id_history')->constrained('medical_record', 'id_history');
        $table->enum('tipo', ['Start', 'Progress', 'Final']);
        $table->date('date');
        $table->text('description')->nullable();
        $table->string('imagen_url', 500)->nullable();
        $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_xrays');
    }
};
