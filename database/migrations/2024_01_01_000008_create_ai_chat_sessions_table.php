<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->unique();
            $table->enum('chat_type', ['symptom_checker', 'appointment_booking', 'emergency_help', 'general_inquiry']);
            $table->json('conversation_history');
            $table->json('extracted_symptoms')->nullable();
            $table->json('ai_recommendations')->nullable();
            $table->boolean('appointment_created')->default(false);
            $table->foreignId('created_appointment_id')->nullable()->constrained('appointments')->onDelete('set null');
            $table->enum('status', ['active', 'completed', 'abandoned'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chat_sessions');
    }
};