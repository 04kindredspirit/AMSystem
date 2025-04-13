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
            // Add student_id column first
            $table->unsignedBigInteger('student_id')->after('studentLrn')->nullable();

            // Define foreign key constraint
            $table->foreign('student_id')->references('id')->on('student_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_records', function (Blueprint $table) {
            // Drop the foreign key first
            $table->dropForeign(['student_id']);

            // Then drop the column
            $table->dropColumn('student_id');
        });
    }
};
