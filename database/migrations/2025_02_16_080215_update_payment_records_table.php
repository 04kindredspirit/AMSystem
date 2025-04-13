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
        Schema::table('payment_records', function (Blueprint $table) {
            $table->decimal('paymentAmount', 10, 2)->nullable()->change();
            $table->decimal('discountedTuition_amount', 10, 2)->nullable()->change();
            $table->decimal('paymentDiscountAmount', 10, 2)->nullable()->change();
            $table->decimal('paymentTuitionAmount', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_records', function (Blueprint $table) {
            $table->string('paymentAmount')->change();
            $table->string('discountedTuition_amount')->change();
            $table->string('paymentDiscountAmount')->change();
            $table->string('paymentTuitionAmount')->change();
        });
    }
};
