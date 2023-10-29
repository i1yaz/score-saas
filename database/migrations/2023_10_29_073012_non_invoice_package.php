<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('non_invoice_packages', function (Blueprint $table) {
            $table->bigIncrements('id')->startingValue(\App\Models\NonInvoicePackage::CODE_START);
            $table->foreignIdFor(\App\Models\Client::class)->constrained();
            $table->string('tax2_ids')->nullable()->comment('for reference only');
            $table->decimal('discount_amount')->default(0);
            $table->decimal('tax_amount')->default(0);
            $table->decimal('sub_total')->default(0);
            $table->decimal('final_amount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
