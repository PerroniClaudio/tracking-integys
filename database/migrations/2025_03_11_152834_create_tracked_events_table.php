<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('tracked_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_code');
            $table->string('ip_address');
            $table->string('user_agent');
            $table->string('referer')->nullable();
            $table->string('url');
            $table->string('session_id')->nullable();
            $table->float('scroll_percentage')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('element_id')->nullable();
            $table->json('custom_get_params')->nullable();
            $table->json('custom_post_params')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('tracked_events');
    }
};
