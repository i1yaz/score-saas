<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('auth_guard')->after('address');
            $table->bigInteger('added_by')->after('auth_guard');
            $table->boolean('status')->default(true)->after('added_by');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            //
        });
    }
};
