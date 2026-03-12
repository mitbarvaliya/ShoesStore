<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('registered_at')->nullable()->after('password');
            $table->timestamp('password_updated_at')->nullable()->after('registered_at');
            $table->string('otp_code', 6)->nullable()->after('password_updated_at');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            $table->boolean('is_otp_verified')->default(false)->after('otp_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['registered_at', 'password_updated_at', 'otp_code', 'otp_expires_at', 'is_otp_verified']);
        });
    }
};
