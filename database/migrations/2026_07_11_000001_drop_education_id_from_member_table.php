<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Drops the direct member.education_id FK: a member's education level is
     * now derived through the member_education_faculty pivot table instead.
     */
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropForeign(['education_id']);
            $table->dropColumn('education_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->foreignId('education_id')->nullable()->after('employed')->constrained('education');
        });
    }
};
