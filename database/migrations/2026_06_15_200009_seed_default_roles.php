<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    \App\Models\Role::firstOrCreate(['name' => 'admin']);
    \App\Models\Role::firstOrCreate(['name' => 'staff']);
    \App\Models\Role::firstOrCreate(['name' => 'employee']);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
