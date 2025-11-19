<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->decimal('consultation_fee', 8, 2)->default(0);
            $table->decimal('medicine_cost', 8, 2)->default(0);
            $table->decimal('additional_charges', 8, 2)->default(0);
            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('total_amount', 8, 2);
            $table->enum('payment_status', ['pending', 'paid', 'partially_paid', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['cash', 'card', 'online', 'insurance'])->nullable();
            $table->datetime('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing');
    }
};