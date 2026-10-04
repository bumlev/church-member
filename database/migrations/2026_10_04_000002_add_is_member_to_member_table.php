<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Flags whether the person is an official church member. Existing rows default to true.
     */
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->boolean('is_member')->default(true)->after('employed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropColumn('is_member');
        });
    }
};
