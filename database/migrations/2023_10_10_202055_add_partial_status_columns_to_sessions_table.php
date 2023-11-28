<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->time('attended_start_time')->nullable()->after('home_work_completed');
            $table->time('attended_end_time')->nullable()->after('attended_start_time');
            $table->boolean('charge_missed_time')->default(false)->after('attended_end_time');
        });
    }

    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('attended_start_time');
            $table->dropColumn('attended_end_time');
            $table->dropColumn('charge_missed_time');
        });
    }
};
