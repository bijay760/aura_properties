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
        Schema::create('register', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 15)->unique()->comment('User phone number');
            $table->string('otp', 6)->nullable()->comment('OTP code');
            $table->string('email')->unique()->nullable()->comment('User email');
            $table->timestamp('otp_expires_at')->nullable()->comment('OTP expiration time');
            $table->tinyInteger('otp_attempts')->default(0)->comment('OTP attempt count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register');
    }
};
