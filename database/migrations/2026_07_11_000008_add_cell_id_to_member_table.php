<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds the cell_id foreign key column to member (single select).
     * Depends on: cell
     */
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->foreignId('cell_id')->nullable()->constrained('cell');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cell_id');
        });
    }
};
