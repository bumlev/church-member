<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the church_responsibility table.
     */
    public function up(): void
    {
        Schema::create('church_responsibility', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('church_responsibility');
    }
};

