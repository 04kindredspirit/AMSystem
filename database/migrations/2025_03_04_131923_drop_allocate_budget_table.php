<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the foreign key constraint in the replenish_expense table
        Schema::table('replenish_expense', function (Blueprint $table) {
            $table->dropForeign('replenish_budget_allocate_budget_id_foreign');
        });

        // Now drop the allocate_budget table
        Schema::dropIfExists('allocate_budget');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};