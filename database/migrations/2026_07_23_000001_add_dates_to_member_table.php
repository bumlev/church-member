<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds date_birthday, date_salvation, date_baptism and member_since columns to member.
     */
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->date('date_birthday')->nullable();
            $table->date('date_salvation')->nullable();
            $table->date('date_baptism')->nullable();
            $table->date('member_since')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropColumn(['date_birthday', 'date_salvation', 'date_baptism', 'member_since']);
        });
    }
};
