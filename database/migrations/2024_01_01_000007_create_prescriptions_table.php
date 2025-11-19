<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->string('dosage'); // 1 tablet, 5ml, etc.
            $table->string('frequency'); // twice daily, once daily, etc.
            $table->integer('duration_days');
            $table->text('instructions')->nullable();
            $table->integer('quantity_prescribed');
            $table->boolean('is_dispensed')->default(false);
            $table->datetime('dispensed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};