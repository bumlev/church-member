<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the main member table with all foreign key relationships.
     * Depends on: sex, marital_status, occupation, education, option, talent,
     *             district, sector, zone, cell, church_responsibility
     */
    public function up(): void
    {
        Schema::create('member', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('talent', 150);

            // Sex (required)
            $table->foreignId('sex_id')->constrained('sex');

            // Marital status (required)
            $table->foreignId('marital_status_id')->constrained('marital_status');

            $table->string('fathers_name', 150)->nullable();
            $table->string('mothers_name', 150)->nullable();

            // Optional lookup FK columns
            $table->foreignId('occupation_id')->nullable()->constrained('occupation');
            $table->boolean('employed')->default(false);
            $table->foreignId('education_id')->nullable()->constrained('education');
            $table->foreignId('department_id')->nullable()->constrained('department');
            $table->foreignId('church_responsibility_id')->nullable()->constrained('church_responsibility');

            $table->string('mobile_tel', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('fax_number', 20)->nullable();

            // Optional geographic FK columns
            $table->foreignId('province_id')->nullable()->constrained('province');
            $table->foreignId('district_id')->nullable()->constrained('district');
            $table->foreignId('sector_id')->nullable()->constrained('sector');
            $table->foreignId('cellule_id')->nullable()->constrained('cellule');
            $table->foreignId('village_id')->nullable()->constrained('village');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member');
    }
};

