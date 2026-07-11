<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the member_department_church_responsibility pivot table, storing the
     * church responsibilities a member selected for their department (multi-select).
     * Depends on: member, department, church_responsibility
     */
    public function up(): void
    {
        // Explicit short constraint/index names: the default auto-generated names
        // exceed MySQL's 64-character identifier limit for this table name.
        Schema::create('member_department_church_responsibility', function (Blueprint $table) {
            $table->foreignId('member_id')->constrained('member', 'id', 'mdcr_member_fk')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('department', 'id', 'mdcr_department_fk')->onDelete('cascade');
            $table->foreignId('church_responsibility_id')->constrained('church_responsibility', 'id', 'mdcr_responsibility_fk')->onDelete('cascade');
            $table->primary(['member_id', 'department_id', 'church_responsibility_id'], 'mdcr_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_department_church_responsibility');
    }
};
