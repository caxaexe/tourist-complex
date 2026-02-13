<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_service', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('service_id')->constrained('services')->restrictOnDelete()->cascadeOnUpdate();

            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price_snapshot', 10, 2)->default(0); // цена на момент добавления

            $table->timestamps();

            $table->index(['booking_id']);
            $table->index(['service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_service');
    }
};
