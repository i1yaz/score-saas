<?php

use App\Models\MonthlyInvoicePackage;
use App\Models\StudentTutoringPackage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->foreignIdFor(MonthlyInvoicePackage::class)->nullable()->after('student_tutoring_package_id')->constrained();
            $table->foreignIdFor(StudentTutoringPackage::class)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('monthly_invoice_package_id');
            $table->foreignIdFor(StudentTutoringPackage::class)->nullable(false)->change();
        });
    }
};
