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
        Schema::create('tracking', function (Blueprint $table) {
        $table->id('id_tracking');
        $table->foreignId('id_treatment')->constrained('treatment', 'id_treatment');
        $table->foreignId('id_patient')->constrained('patients', 'id_patient');
        $table->date('date');
        $table->text('observations')->nullable();
        $table->dateTime('scheduled_appointment')->nullable();
        $table->timestamps();
        });
        
        Schema::create('payment_plan', function (Blueprint $table) {
        $table->id('id_cobro');
        $table->foreignId('id_treatment')->constrained('treatment', 'id_treatment');
        $table->decimal('total_amount', 10, 2);
        $table->decimal('amount_paid', 10, 2)->default(0);
        $table->decimal('outstanding_balance', 10, 2);
        $table->date('payment_date')->nullable();
        $table->enum('payment_method', ['Cash', 'QR'])->nullable();
        $table->string('receipt', 255)->nullable();
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
