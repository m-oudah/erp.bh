<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Citizen OTP Verifications
        Schema::create('citizen_otp_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('id_card', 20)->index();
            $table->string('mobile', 25)->index();
            $table->string('otp_code', 6);
            $table->timestamp('expires_at');
            $table->boolean('is_verified')->default(false);
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        // 2. Citizen Access / Audit Logs
        Schema::create('citizen_access_logs', function (Blueprint $table) {
            $table->id();
            $table->string('id_card', 20)->index();
            $table->string('mobile', 25)->nullable();
            $table->string('citizen_name')->nullable();
            $table->string('action', 50)->default('login');
            $table->string('ip_address', 45)->nullable();
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citizen_access_logs');
        Schema::dropIfExists('citizen_otp_verifications');
    }
};
