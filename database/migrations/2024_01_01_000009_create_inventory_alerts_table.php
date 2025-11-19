<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->enum('alert_type', ['low_stock', 'expiry_warning', 'out_of_stock']);
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_resolved')->default(false);
            $table->datetime('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_alerts');
    }
};