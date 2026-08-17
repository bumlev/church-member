<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the member_talent pivot table, storing the talents a member
     * selected (multi-select). Depends on: member, talent
     */
    public function up(): void
    {
        Schema::create('member_talent', function (Blueprint $table) {
            $table->foreignId('member_id')->constrained('member')->onDelete('cascade');
            $table->foreignId('talent_id')->constrained('talent')->onDelete('cascade');
            $table->primary(['member_id', 'talent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_talent');
    }
};
