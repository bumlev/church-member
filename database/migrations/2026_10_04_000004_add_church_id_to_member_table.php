<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds the church_id foreign key column to member: each member belongs to
     * one church, and a church has many members.
     * Depends on: churches
     */
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->foreignId('church_id')->nullable()->constrained('churches')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropConstrainedForeignId('church_id');
        });
    }
};
