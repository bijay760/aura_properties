<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ----- Step 1: rename columns -----
        Schema::table('temp_properties', function (Blueprint $table) {
            $table->renameColumn('floor_no', 'floor_count');
            $table->renameColumn('possession_by', 'possession');
        });

        // ----- Step 2: change `possession` to TIMESTAMP -----
        Schema::table('temp_properties', function (Blueprint $table) {
            $table->timestamp('possession')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Roll back type change first
        Schema::table('temp_properties', function (Blueprint $table) {
            $table->string('possession')->nullable()->change();
        });

        // Then rename columns back to the originals
        Schema::table('temp_properties', function (Blueprint $table) {
            $table->renameColumn('floor_count', 'floor_no');
            $table->renameColumn('possession',   'possession_by');
        });
    }
};
