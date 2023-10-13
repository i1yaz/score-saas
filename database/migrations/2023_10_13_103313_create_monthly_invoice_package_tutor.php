<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('monthly_invoice_package_tutor', function (Blueprint $table) {
            $table->unsignedBigInteger('monthly_invoice_package_id');
            $table->unsignedBigInteger('tutor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_invoice_package_tutor');
    }
};
