<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->increments('config_id');
            $table->string('name', 64)->collation('utf8mb4_general_ci');
            $table->string('config', 255)->collation('utf8mb4_general_ci');
            $table->tinyText('description')->collation('utf8mb4_general_ci');
            $table->unsignedTinyInteger('required')->default(0);
            $table->unique('name', 'config_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configs');
    }
};
