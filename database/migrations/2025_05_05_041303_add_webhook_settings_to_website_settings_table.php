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
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('webhook_api_key')->nullable()->after('footer_scripts');
            $table->string('webhook_url')->nullable()->after('webhook_api_key');
            $table->boolean('webhook_is_active')->default(true)->after('webhook_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['webhook_api_key', 'webhook_url', 'webhook_is_active']);
        });
    }
};
