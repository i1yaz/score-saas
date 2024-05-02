<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mock_test_codes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('auth_guard');
            $table->bigInteger('added_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_test_codes');
    }
};
