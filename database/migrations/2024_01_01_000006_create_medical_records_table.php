<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->text('chief_complaint');
            $table->text('history_of_present_illness')->nullable();
            $table->text('physical_examination')->nullable();
            $table->text('diagnosis');
            $table->text('treatment_plan');
            $table->json('vital_signs')->nullable(); // blood pressure, temperature, etc.
            $table->json('lab_results')->nullable();
            $table->text('notes')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};