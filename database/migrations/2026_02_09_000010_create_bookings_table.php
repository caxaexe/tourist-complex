<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete()->cascadeOnUpdate();

            $table->date('date_from');
            $table->date('date_to');

            $table->string('status')->default('pending'); 
            // pending/confirmed/cancelled/checked_in/checked_out

            $table->decimal('total', 12, 2)->default(0);
            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['room_id', 'date_from', 'date_to']);
            $table->index(['client_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
