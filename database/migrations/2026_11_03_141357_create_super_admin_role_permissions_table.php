<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('super_admin_role_permissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('super_admin_role_id')
                ->constrained('super_admin_roles')
                ->cascadeOnDelete();

            $table->foreignId('super_admin_permission_id')
                ->constrained('super_admin_permissions')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(
                ['super_admin_role_id', 'super_admin_permission_id'],
                'super_admin_role_permission_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('super_admin_role_permissions');
    }
};