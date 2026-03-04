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
        Schema::create('treatment', function (Blueprint $table) 
        {
        $table->id('id_treatment');
        $table->foreignId('id_history')->constrained('medical_history', 'id_history');
        $table->foreignId('id_user')->constrained('users', 'id_user');
        $table->enum('categoria', ['Orthodontics', 'Endodoncia', 'Aesthetics', 'Surgery']);
        $table->text('description');
        $table->decimal('cost', 10, 2);
        $table->date('start_date');
        $table->date('end_date')->nullable();
        $table->enum('status', ['In progress', 'Completed', 'Suspended']);
        $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
