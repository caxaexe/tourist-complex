<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('action', 50); // created | updated | deleted | login | logout ...
            $table->string('entity_type', 150)->nullable(); // App\Models\Booking
            $table->unsignedBigInteger('entity_id')->nullable();

            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('url')->nullable();
            $table->string('method', 10)->nullable();

            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
            $table->index(['action']);
            $table->index(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
