<?php

use App\Models\Invoice;
use App\Models\LineItem;
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
        Schema::create('invoice_line_item', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Invoice::class)->constrained();
            $table->foreignIdFor(LineItem::class)->constrained();
            $table->string('tax_ids')->nullable()->comment('for reference only');
            $table->decimal('price')->default(0);
            $table->decimal('qty')->default(0);
            $table->decimal('tax_amount')->default(0);
            $table->decimal('final_amount');
            $table->boolean('status')->default(true);
            $table->string('auth_guard');
            $table->bigInteger('added_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('invoice_items');
    }
};
