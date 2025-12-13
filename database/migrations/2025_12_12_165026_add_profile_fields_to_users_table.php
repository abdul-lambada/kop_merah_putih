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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->date('birth_date')->nullable()->after('phone');
            $table->text('address')->nullable()->after('birth_date');
            $table->boolean('email_notifications')->default(false)->after('address');
            $table->string('theme')->default('light')->after('email_notifications');
            $table->string('language')->default('id')->after('theme');
            $table->string('timezone')->default('Asia/Jakarta')->after('language');
            $table->boolean('notification_email')->default(false)->after('timezone');
            $table->boolean('notification_push')->default(false)->after('notification_email');
            $table->boolean('notification_sms')->default(false)->after('notification_push');
            $table->string('member_number')->nullable()->after('notification_sms');
            $table->timestamp('last_login_at')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'birth_date', 
                'address',
                'email_notifications',
                'theme',
                'language',
                'timezone',
                'notification_email',
                'notification_push',
                'notification_sms',
                'member_number',
                'last_login_at'
            ]);
        });
    }
};
