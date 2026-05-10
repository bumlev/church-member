<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates all geographic tables:
     * district, sector, zone, cell
     */
    public function up(): void
    {
        Schema::create('province', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });


        Schema::create('district', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('province')->cascadeOnDelete();
            $table->string('name', 100);
        });

        Schema::create('sector', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained('district')->cascadeOnDelete();
            $table->string('name', 100);
        });

        Schema::create('cellule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained('sector')->cascadeOnDelete();
            $table->string('name', 100);
        });

        Schema::create('village', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cellule_id')->constrained('cellule')->cascadeOnDelete();
            $table->string('name', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('province');
        Schema::dropIfExists('district');
        Schema::dropIfExists('sector');
        Schema::dropIfExists('cellule');
        Schema::dropIfExists('village');
    }
};

