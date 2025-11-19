<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('brand');
            $table->string('category');
            $table->text('description')->nullable();
            $table->string('dosage_form'); // tablet, syrup, injection, etc.
            $table->string('strength'); // 500mg, 10ml, etc.
            $table->decimal('unit_price', 8, 2);
            $table->integer('stock_quantity');
            $table->integer('minimum_stock_level')->default(10);
            $table->date('expiry_date');
            $table->string('batch_number')->nullable();
            $table->string('manufacturer');
            $table->boolean('requires_prescription')->default(true);
            $table->json('side_effects')->nullable();
            $table->json('contraindications')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};