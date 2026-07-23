<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the family_membership table, the association class linking a
     * member to a family together with the role they play in it (father,
     * mother, child, guardian). Unlike the other member pivots, this one
     * carries its own identity and attributes (role_type, start_date,
     * end_date), so it gets a surrogate id rather than a composite key.
     * Depends on: family, member
     */
    public function up(): void
    {
        Schema::create('family_membership', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->constrained('family')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('member')->onDelete('cascade');
            $table->string('role_type', 20);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unique(['family_id', 'member_id', 'role_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_membership');
    }
};
