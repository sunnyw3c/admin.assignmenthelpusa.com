<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('tags')->nullable();
            $table->boolean('is_subscribed')->default(true);
            $table->dateTime('last_sent_at')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('scheduled_campaign_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('recipient_email')->index();
            $table->string('recipient_name')->nullable();
            $table->string('mail_type')->default('promotional'); // promotional | direct
            $table->json('campaign_content');
            $table->dateTime('scheduled_at')->index();
            $table->enum('status', ['pending', 'processing', 'sent', 'deferred', 'failed', 'cancelled'])->default('pending')->index();
            $table->dateTime('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });

        Schema::create('campaign_delivery_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_email')->index();
            $table->string('subject')->nullable();
            $table->string('mail_type')->default('promotional');
            $table->dateTime('sent_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_delivery_logs');
        Schema::dropIfExists('scheduled_campaign_emails');
        Schema::dropIfExists('marketing_contacts');
    }
};
