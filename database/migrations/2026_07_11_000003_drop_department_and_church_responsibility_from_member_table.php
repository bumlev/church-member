<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Drops the direct member.department_id and member.church_responsibility_id FKs:
     * a member's department(s) and church responsibility(ies) are now derived through
     * the member_department_church_responsibility pivot table instead.
     */
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['church_responsibility_id']);
            $table->dropColumn(['department_id', 'church_responsibility_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('employed')->constrained('department');
            $table->foreignId('church_responsibility_id')->nullable()->after('department_id')->constrained('church_responsibility');
        });
    }
};
