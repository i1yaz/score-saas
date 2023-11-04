<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->time('attended_duration')->nullable()->after('session_completion_code');
            $table->boolean('charge_for_missed_session')->default(false)->after('attended_duration');
            $table->time('charged_missed_session')->nullable()->after('charge_for_missed_session');
        });
    }

    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('attended_duration');
            $table->dropColumn('charge_for_missed_session');
            $table->dropColumn('charged_missed_session');
        });
    }
};
