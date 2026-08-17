<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the member_occupation pivot table, storing the occupations a
     * member selected (multi-select). Depends on: member, occupation
     */
    public function up(): void
    {
        Schema::create('member_occupation', function (Blueprint $table) {
            $table->foreignId('member_id')->constrained('member')->onDelete('cascade');
            $table->foreignId('occupation_id')->constrained('occupation')->onDelete('cascade');
            $table->primary(['member_id', 'occupation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_occupation');
    }
};
