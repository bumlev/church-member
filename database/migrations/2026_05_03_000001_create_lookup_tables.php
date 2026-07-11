<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates all lookup / reference tables:
     * sex, marital_status, occupation, education, option, talent
     */
    public function up(): void
    {
        Schema::create('sex', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
        });

        Schema::create('marital_status', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });

        Schema::create('occupation', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });

        Schema::create('department', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });

        Schema::create('church_responsibility' , function (Blueprint $table){;
            $table->id();
            $table->string('name', 100);
            $table->foreignId('department_id')->constrained('department');
        });

        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });


        Schema::create('faculty', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });

        Schema::create('education_faculty', function (Blueprint $table) {
            $table->foreignId('education_id')->constrained('education')->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained('faculty')->onDelete('cascade');
            $table->primary(['education_id', 'faculty_id']);
        });

        Schema::create('cell', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });

        Schema::create('talent', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });

        Schema::create('spiritual_gift' , function (Blueprint $table){
            $table->id();
            $table->string('name', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department');
        Schema::dropIfExists('education');
        Schema::dropIfExists('occupation');
        Schema::dropIfExists('marital_status');
        Schema::dropIfExists('sex');
        Schema::dropIfExists('education_faculty');
        Schema::dropIfExists('faculty');
        Schema::dropIfExists('cell');
        Schema::dropIfExists('church_responsibility');
        Schema::dropIfExists('talent');
        Schema::dropIfExists('spiritual_gift');

    }
};

