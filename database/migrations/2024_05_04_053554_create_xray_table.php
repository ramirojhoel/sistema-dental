<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xrays', function (Blueprint $table) {  
            $table->id('id_xray');
            $table->unsignedBigInteger('id_history');
            $table->foreign('id_history')->references('id_history')->on('medical_history')->cascadeOnDelete();
            $table->enum('type', ['Panoramic', 'Periapical', 'Bitewing']);
            $table->date('date');
            $table->string('archive_url', 500);
            $table->text('observations')->nullable();
            $table->timestamps();
        });

        Schema::create('odontograms', function (Blueprint $table) {
            $table->id('id_odontogram');
            $table->unsignedBigInteger('id_history');
            $table->foreign('id_history')->references('id_history')->on('medical_history')->cascadeOnDelete();
            $table->enum('type', ['Start', 'Progress', 'Final']);
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odontograms'); // ✅ FIX: faltaba este
        Schema::dropIfExists('xrays');
    }
};