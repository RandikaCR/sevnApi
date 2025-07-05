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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('ref_id')->nullable();
            $table->string('public_key')->nullable();
            $table->integer('user_role_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('is_email_subscribed')->nullable();
            $table->tinyInteger('is_phone_subscribed')->nullable();
            $table->tinyInteger('is_whatsapp_subscribed')->nullable();
            $table->float('profit_margin_on_sale')->nullable();
            $table->string('user_code')->nullable();
            $table->tinyInteger('is_discount_available')->nullable();
            $table->bigInteger('discount_rule_id')->nullable();
            $table->bigInteger('default_business_id')->nullable();
            $table->bigInteger('default_business_branch_id')->nullable();
            $table->bigInteger('registered_business_id')->nullable();
            $table->bigInteger('registered_business_branch_id')->nullable();
            $table->bigInteger('registered_by')->nullable();
            $table->dateTime('last_activity_time')->nullable();
            $table->dateTime('last_order_time')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('is_deleted')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
