<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_history', function (Blueprint $table) {
            $table->string('reason_for_visit')->nullable()->after('opening_date');
            $table->text('diagnosis')->nullable()->after('reason_for_visit');
            $table->text('allergies')->nullable()->after('diagnosis');
            $table->text('previous_diseases')->nullable()->after('allergies');
            $table->text('observations')->nullable()->after('previous_diseases');
            $table->text('treatment_plan')->nullable()->after('observations');
        });
    }

    public function down(): void
    {
        Schema::table('medical_history', function (Blueprint $table) {
            $table->dropColumn([
                'reason_for_visit', 'diagnosis', 'allergies',
                'previous_diseases', 'observations', 'treatment_plan'
            ]);
        });
    }
};