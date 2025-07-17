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
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('otp', 6); // 6-digit OTP code
            $table->string('phone'); // Phone number or email
            $table->enum('type', ['phone', 'email','register','login','forget_password']); // Verification type
            $table->timestamp('expires_at'); // Expiration time
            $table->unsignedSmallInteger('attempts')->default(0); // Verification attempts
            $table->boolean('verified')->default(false); // Verification status
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
