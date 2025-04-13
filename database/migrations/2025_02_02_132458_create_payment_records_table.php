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
        Schema::create('payment_records', function (Blueprint $table) {
            $table->id();
            $table->date('paymentDate')->nullable();
            $table->string('paymentOR')->nullable();
            $table->string('studentFullname')->nullable();
            $table->string('studentLrn')->nullable();
            $table->string('paymentAmount')->nullable();
            $table->string('paymentDiscountType')->nullable();
            $table->string('paymentDiscountAmount')->nullable();
            $table->string('paymentTuitionAmount')->nullable();
            $table->string('paymentPeriod')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_records');
    }
};
