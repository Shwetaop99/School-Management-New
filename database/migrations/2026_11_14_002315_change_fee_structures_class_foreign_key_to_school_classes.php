
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fee_structures', function (Blueprint $table) {
            $table->dropForeign('fee_structures_class_id_foreign');

            $table->foreign('class_id')
                ->references('id')
                ->on('school_classes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fee_structures', function (Blueprint $table) {
            $table->dropForeign(['class_id']);

            $table->foreign('class_id')
                ->references('id')
                ->on('classes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }
};
