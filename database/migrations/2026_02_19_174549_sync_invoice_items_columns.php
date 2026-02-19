<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_items', 'type')) {
                $table->string('type', 50)->nullable()->after('invoice_id');
            }
            if (!Schema::hasColumn('invoice_items', 'title')) {
                $table->string('title')->after('type');
            }
            if (!Schema::hasColumn('invoice_items', 'quantity')) {
                $table->unsignedInteger('quantity')->default(1)->after('title');
            }
            if (!Schema::hasColumn('invoice_items', 'unit_price')) {
                $table->decimal('unit_price', 10, 2)->default(0)->after('quantity');
            }
            if (!Schema::hasColumn('invoice_items', 'line_total')) {
                $table->decimal('line_total', 10, 2)->default(0)->after('unit_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
        });
    }
};
