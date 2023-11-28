<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('charge_for_missed_session');
        });
    }

    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->boolean('charge_for_missed_session')->default(0);
        });
    }
};
