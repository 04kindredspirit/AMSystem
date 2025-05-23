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
        Schema::table('replenish_budget', function (Blueprint $table) {
            $table->string('type')->nullable()->change();
            $table->date('date')->nullable()->change();
            $table->decimal('amount')->nullable()->change();
            $table->decimal('balance')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('replenish_budget', function (Blueprint $table) {
            $table->string('type')->nullable(false)->change();
            $table->date('date')->nullable(false)->change();
            $table->decimal('amount')->nullable(false)->change();
            $table->decimal('balance')->nullable(false)->change();
        });
    }
};
