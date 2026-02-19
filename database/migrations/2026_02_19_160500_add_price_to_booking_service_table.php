<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_service', function (Blueprint $table) {
            if (!Schema::hasColumn('booking_service', 'price')) {
                $table->decimal('price', 12, 2)->nullable()->after('quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking_service', function (Blueprint $table) {
            if (Schema::hasColumn('booking_service', 'price')) {
                $table->dropColumn('price');
            }
        });
    }
};
