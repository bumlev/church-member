<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Drops the unused member.fax_number column.
     */
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropColumn('fax_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->string('fax_number', 20)->nullable()->after('email');
        });
    }
};
