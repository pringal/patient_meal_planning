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
        Schema::create('patient_meal_plannings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->date('planned_date');
            $table->decimal('total_calories', 10, 2);
            $table->decimal('total_fats', 10, 2);
            $table->decimal('total_carbs', 10, 2);
            $table->decimal('total_proteins', 10, 2);
            $table->boolean('is_active');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->unique(['patient_id', 'planned_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_meal_plannings');
    }
};
