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
        Schema::table('student_links', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('student_lists')->onDelete('cascade');

            $table->unique(['student_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_links', function (Blueprint $table) {
            $table->dropForeign(['group_id']);

            $table->dropUnique(['student_id', 'group_id']);
        });
    }
};
