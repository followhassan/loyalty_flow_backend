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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('google_key', 191)->nullable();
            $table->string('google_analytics_id', 191)->nullable();
            $table->string('google_recaptcha_key', 255)->nullable();
            $table->string('google_recaptcha_secrect', 255)->nullable();
            $table->tinyInteger('google_recaptcha_status')->default(0);
            $table->longText('site_name')->nullable();
            $table->string('site_logo', 191)->nullable();
            $table->string('admin_logo', 191)->nullable();
            $table->string('favicon', 191)->nullable();
            $table->longText('seo_meta_description')->nullable();
            $table->longText('seo_keywords')->nullable();
            $table->string('seo_image', 191)->nullable();
            $table->string('tawk_chat_bot_key', 191)->nullable();
            $table->longText('name');
            $table->longText('address');
            $table->string('driver', 191);
            $table->string('host', 191);
            $table->integer('port');
            $table->string('encryption', 191);
            $table->string('username', 191)->nullable();
            $table->string('password', 191)->nullable();
            $table->string('status', 191)->default('1');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->string('application_type', 50)->nullable();
            $table->enum('app_mode', ['local', 'live'])->nullable()->comment('local/live');
            $table->string('facebook_client_id', 150)->nullable();
            $table->string('facebook_client_secret', 150)->nullable();
            $table->string('facebook_callback_url', 255)->nullable();
            $table->string('google_client_id', 150)->nullable();
            $table->string('google_client_secret', 150)->nullable();
            $table->string('google_callback_url', 255)->nullable();
            $table->string('copyright_text', 124)->nullable();
            $table->string('office_address', 150)->nullable();
            $table->string('facebook_url', 150)->nullable();
            $table->string('youtube_url', 150)->nullable();
            $table->string('twitter_url', 150)->nullable();
            $table->string('pinterest_url', 150)->nullable();
            $table->string('linkedin_url', 150)->nullable();
            $table->string('telegram_url', 150)->nullable();
            $table->string('whatsapp_number', 150)->nullable();
            $table->string('ios_app_url', 150)->nullable();
            $table->string('android_app_url', 150)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone_no', 150)->nullable();
            $table->string('support_email', 30)->nullable();
            $table->string('instagram_url', 255)->nullable();
            $table->text('map_link')->nullable();
            $table->text('invoice_footer')->nullable();
            $table->text('invoice_footer_de')->nullable();
            $table->float('tax', 13, 2)->default(0.00);
            $table->integer('email_verified')->nullable()->comment('1=yes, 0=no');
            $table->integer('email_enabled')->nullable()->comment('1=yes, 0=no');
            $table->string('google_map_key', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
