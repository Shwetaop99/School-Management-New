<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('result_version_details', function (Blueprint $table) {
            $table->foreign('result_version_id')
                ->references('id')
                ->on('result_versions')
                ->cascadeOnDelete();

            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects')
                ->nullOnDelete();

            $table->index(
                ['result_version_id', 'subject_id'],
                'result_version_details_version_subject_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('result_version_details', function (Blueprint $table) {
            $table->dropForeign([
                'result_version_id',
            ]);

            $table->dropForeign([
                'subject_id',
            ]);

            $table->dropIndex(
                'result_version_details_version_subject_index'
            );
        });
    }
};