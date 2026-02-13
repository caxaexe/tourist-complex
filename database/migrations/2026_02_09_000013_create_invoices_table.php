<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete()->cascadeOnUpdate();

            $table->string('number')->unique(); // INV-2026-0001
            $table->string('status')->default('unpaid'); // unpaid/partial/paid

            $table->decimal('total', 12, 2)->default(0);

            $table->dateTime('issued_at')->nullable();
            $table->dateTime('paid_at')->nullable();

            $table->timestamps();

            $table->index(['booking_id']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
