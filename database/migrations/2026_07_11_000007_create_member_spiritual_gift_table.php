<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the member_spiritual_gift pivot table, storing the spiritual
     * gifts a member selected (multi-select). Depends on: member, spiritual_gift
     */
    public function up(): void
    {
        Schema::create('member_spiritual_gift', function (Blueprint $table) {
            $table->foreignId('member_id')->constrained('member')->onDelete('cascade');
            $table->foreignId('spiritual_gift_id')->constrained('spiritual_gift')->onDelete('cascade');
            $table->primary(['member_id', 'spiritual_gift_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_spiritual_gift');
    }
};
