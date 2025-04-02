<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('tracked_events', function (Blueprint $table) {
            //
            // Aggiungi la colonna "user_email" alla tabella "tracked_events"
            $table->string('user_email')->nullable()->after('ip_zip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('tracked_events', function (Blueprint $table) {
            //
            // Rimuovi la colonna "user_email" dalla tabella "tracked_events"
            $table->dropColumn('user_email');
        });
    }
};
