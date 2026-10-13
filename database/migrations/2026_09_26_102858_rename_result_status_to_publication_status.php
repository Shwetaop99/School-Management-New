<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // publication_status was already added by the previous migration.
        // Nothing needs to be added here.
    }

    public function down(): void
    {
        // Do not remove publication_status here because it belongs
        // to the previous publishing-fields migration.
    }
};