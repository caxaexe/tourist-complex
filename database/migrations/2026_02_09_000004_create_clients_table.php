<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // паспортные данные (для диплома красиво)
            $table->string('passport_series', 16)->nullable();
            $table->string('passport_number', 32)->nullable();

            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();

            $table->index(['full_name']);
            $table->index(['phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
