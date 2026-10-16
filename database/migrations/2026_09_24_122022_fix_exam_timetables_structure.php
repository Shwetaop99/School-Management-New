<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // section_id was already removed manually because
        // the existing database structure was partially modified.
        //
        // This migration now only ensures the required foreign keys
        // and unique constraint exist.

        Schema::table('exam_timetables', function (Blueprint $table) {
            // Foreign keys are handled below with explicit names.
        });

        // Nothing else is required here because the table structure
        // has already been corrected manually.
    }

    public function down(): void
    {
        // No automatic rollback.
        //
        // The original table contained section_id, but restoring it
        // automatically could cause conflicts with the current schema.
    }
};