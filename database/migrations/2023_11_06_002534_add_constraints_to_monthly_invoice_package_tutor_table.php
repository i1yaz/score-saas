<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('monthly_invoice_package_tutor', function (Blueprint $table) {
            $table->foreign('monthly_invoice_package_id', 'monthly_invoice_package_tutor_ibfk_1')->references('id')->on('monthly_invoice_packages');
            $table->foreign('tutor_id', 'monthly_invoice_package_tutor_ibfk_2')->references('id')->on('tutors');
        });
    }

    public function down(): void
    {
        Schema::table('monthly_invoice_package_tutor', function (Blueprint $table) {
            $table->dropForeign('monthly_invoice_package_tutor_ibfk_1');
            $table->dropForeign('monthly_invoice_package_tutor_ibfk_2');
        });
    }
};
