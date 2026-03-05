<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking', function (Blueprint $table) {
            $table->id('id_tracking');
            $table->unsignedBigInteger('id_treatment');
            $table->foreign('id_treatment')->references('id_treatment')->on('treatment')->cascadeOnDelete();
            $table->unsignedBigInteger('id_patient');
            $table->foreign('id_patient')->references('id_patient')->on('patients')->cascadeOnDelete();
            $table->date('date');
            $table->text('observations')->nullable();
            $table->dateTime('scheduled_appointment')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_plan', function (Blueprint $table) {
            $table->id('id_payment');
            $table->unsignedBigInteger('id_treatment');
            $table->foreign('id_treatment')->references('id_treatment')->on('treatment')->cascadeOnDelete();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('outstanding_balance', 10, 2);
            $table->date('payment_date')->nullable();
            $table->enum('payment_method', ['Cash', 'QR'])->nullable();
            $table->string('receipt', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_plan');
        Schema::dropIfExists('tracking');
    }
};