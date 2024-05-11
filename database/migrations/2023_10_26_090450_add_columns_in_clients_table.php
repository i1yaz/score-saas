<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('address');
            $table->string('auth_guard')->after('status');
            $table->unsignedBigInteger('added_by')->after('auth_guard');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('auth_guard');
            $table->dropColumn('added_by');
        });
    }
};
