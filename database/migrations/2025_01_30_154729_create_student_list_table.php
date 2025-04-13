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
        Schema::create('student_lists', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('studentLRN')->nullable();
            $table->string('studentSection')->nullable();
            $table->string('studentName_ext')->nullable(); // Name extension like Sr., Jr.
            $table->string('studentFirst_name')->nullable();
            $table->string('studentMiddle_name')->nullable();
            $table->string('studentLast_name')->nullable();
            $table->string('studentGender')->nullable();
            $table->date('studentBirthdate')->nullable();
            $table->string('studentBirthorder')->nullable(); // Birth order e.g., 1st, 2nd
            $table->string('studentAddress')->nullable();
            $table->string('studentHobby')->nullable(); // Hobbies and activities
            $table->string('studentFavorite')->nullable(); // Favorite food or treats
            $table->decimal('studentTuition_amount', 8, 2)->nullable(); // Tuition fee amount
            $table->string('studentTuition_discount')->nullable(); // Discount applied to the tuition fee
            $table->timestamps(); // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_lists');
    }
};
