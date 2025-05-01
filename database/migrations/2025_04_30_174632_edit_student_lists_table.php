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
        Schema::table('student_lists', function (Blueprint $table) {
            $table->decimal('discountPercentage', 5, 2)->nullable()->after('studentTuition_discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_lists', function (Blueprint $table) {
            $table->dropColumn('discountPercentage');
        });
    }
};
