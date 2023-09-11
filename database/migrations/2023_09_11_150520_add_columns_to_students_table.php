<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->after('user_id');
            $table->boolean('status')->default(true)->after('test_anxiety_challenge');
            $table->unsignedBigInteger('added_by')->after('status');
            $table->timestamp('added_on')->after('added_by');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
};
