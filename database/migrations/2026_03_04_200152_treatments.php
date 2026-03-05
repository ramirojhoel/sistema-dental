<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatment', function (Blueprint $table) {
            $table->id('id_treatment');
            $table->unsignedBigInteger('id_history');
            $table->foreign('id_history')->references('id_history')->on('medical_history')->cascadeOnDelete();
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->restrictOnDelete();
            $table->enum('category', ['Orthodontics', 'Endodontics', 'Aesthetics', 'Surgery']); 
            $table->text('description');
            $table->decimal('cost', 10, 2);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['In progress', 'Completed', 'Suspended']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment');
    }
};