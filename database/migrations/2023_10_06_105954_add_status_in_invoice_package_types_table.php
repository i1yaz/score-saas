<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_package_types', function (Blueprint $table) {
            $table->boolean('status')->default(true);
            $table->string('auth_guard');
            $table->bigInteger('added_by');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_package_types', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('auth_guard');
            $table->dropColumn('added_by');
        });
    }
};
