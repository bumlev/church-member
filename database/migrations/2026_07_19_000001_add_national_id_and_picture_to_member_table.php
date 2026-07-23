<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds national_id and picture columns to member.
     */
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->string('national_id', 20)->nullable()->unique();
            $table->string('picture')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropColumn(['national_id', 'picture']);
        });
    }
};
