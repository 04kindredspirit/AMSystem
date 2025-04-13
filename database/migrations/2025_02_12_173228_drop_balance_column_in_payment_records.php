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
            // Drop the foreign key constraint using the actual constraint name
            $table->dropForeign(['student_id']); // Try using 'student_id' since it was renamed to 'balance'

            // Drop the balance column
            $table->dropColumn('balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_records', function (Blueprint $table) {
            // Recreate the column with correct type
            $table->unsignedBigInteger('student_id')->after('some_column');

            // Re-add the foreign key
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }
};
