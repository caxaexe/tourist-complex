<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('audit_logs')) {
            return;
        }

        Schema::table('audit_logs', function (Blueprint $table) {

            if (!Schema::hasColumn('audit_logs', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('audit_logs', 'action')) {
                $table->string('action', 50)->after('user_id');
            }

            if (!Schema::hasColumn('audit_logs', 'entity_type')) {
                $table->string('entity_type', 150)->nullable()->after('action');
            }

            if (!Schema::hasColumn('audit_logs', 'entity_id')) {
                $table->unsignedBigInteger('entity_id')->nullable()->after('entity_type');
            }

            if (!Schema::hasColumn('audit_logs', 'old_values')) {
                $table->json('old_values')->nullable()->after('entity_id');
            }

            if (!Schema::hasColumn('audit_logs', 'new_values')) {
                $table->json('new_values')->nullable()->after('old_values');
            }

            if (!Schema::hasColumn('audit_logs', 'ip')) {
                $table->string('ip', 45)->nullable()->after('new_values');
            }

            if (!Schema::hasColumn('audit_logs', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip');
            }

            if (!Schema::hasColumn('audit_logs', 'url')) {
                $table->text('url')->nullable()->after('user_agent');
            }

            if (!Schema::hasColumn('audit_logs', 'method')) {
                $table->string('method', 10)->nullable()->after('url');
            }

            if (!Schema::hasColumn('audit_logs', 'created_at') && !Schema::hasColumn('audit_logs', 'updated_at')) {
                $table->timestamps();
            }
        });

        try {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->index(['entity_type', 'entity_id']);
                $table->index(['action']);
                $table->index(['user_id']);
            });
        } catch (\Throwable $e) {}

        try {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            });
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {

    }
};
