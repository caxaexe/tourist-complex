<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // 101, 102...
            $table->foreignId('room_type_id')
                ->nullable()
                ->constrained('room_types')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->string('title')->nullable(); // "Номер с видом на озеро"
            $table->unsignedTinyInteger('capacity')->nullable(); // 1..255
            $table->decimal('price_per_night', 10, 2)->default(0);

            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
