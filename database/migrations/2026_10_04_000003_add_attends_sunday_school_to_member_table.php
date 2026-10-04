<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Flags whether the member (typically under 19) attends Sunday school.
     */
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->boolean('attends_sunday_school')->default(false)->after('is_member');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropColumn('attends_sunday_school');
        });
    }
};
