<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Drops the direct member.talent (free-text) and member.occupation_id FK
     * columns: a member's talent(s) and occupation(s) are now derived through
     * the member_talent and member_occupation pivot tables instead.
     */
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropForeign(['occupation_id']);
            $table->dropColumn(['talent', 'occupation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->string('talent', 150)->after('last_name');
            $table->foreignId('occupation_id')->nullable()->after('mothers_name')->constrained('occupation');
        });
    }
};
