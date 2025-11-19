<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->string('specialization');
            $table->integer('experience_years');
            $table->text('qualifications');
            $table->decimal('consultation_fee', 8, 2);
            $table->json('available_days'); // ['monday', 'tuesday', etc.]
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('slot_duration')->default(30); // minutes
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_profiles');
    }
};