<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the member_education_faculty pivot table, storing the faculties
     * a member selected for their education level (multi-select).
     * Depends on: member, education, faculty
     */
    public function up(): void
    {
        Schema::create('member_education_faculty', function (Blueprint $table) {
            $table->foreignId('member_id')->constrained('member')->onDelete('cascade');
            $table->foreignId('education_id')->constrained('education')->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained('faculty')->onDelete('cascade');
            $table->primary(['member_id', 'education_id', 'faculty_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_education_faculty');
    }
};
