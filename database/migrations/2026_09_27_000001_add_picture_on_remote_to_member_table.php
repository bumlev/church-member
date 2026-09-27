<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tracks whether the member's picture has been mirrored to remote storage.
     */
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->boolean('picture_on_remote')->default(false)->after('picture');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropColumn('picture_on_remote');
        });
    }
};
