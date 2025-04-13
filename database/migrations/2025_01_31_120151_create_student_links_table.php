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
        Schema::create('student_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id'); // References student_lists table
            $table->unsignedBigInteger('group_id'); // Links students in the same group
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('student_id')->references('id')->on('student_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_links');
    }
};
