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


        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });

        Schema::create('option_department', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });

        Schema::create('talent', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talent');
        Schema::dropIfExists('option_department');
        Schema::dropIfExists('education');
        Schema::dropIfExists('occupation');
        Schema::dropIfExists('marital_status');
        Schema::dropIfExists('sex');

    }
};

