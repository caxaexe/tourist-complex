<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('position')->nullable(); 
            $table->text('duties')->nullable(); 
            $table->boolean('is_active')->default(true);   
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['full_name','phone','salary','position','duties','is_active']);
        });
    }

};
