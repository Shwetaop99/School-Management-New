
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fee_structures', function (Blueprint $table) {
            $table->dropForeign('fee_structures_section_id_foreign');

            $table->foreign('section_id')
                ->references('id')
                ->on('school_classes')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fee_structures', function (Blueprint $table) {
            $table->dropForeign(['section_id']);

            $table->foreign('section_id')
                ->references('id')
                ->on('sections')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }
};
