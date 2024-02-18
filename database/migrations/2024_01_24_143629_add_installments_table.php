<?php

use App\Models\Invoice;
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
        Schema::create('installments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Invoice::class)->constrained();
            $table->decimal('amount');
            $table->date('due_date');
            $table->boolean('is_paid')->default(false);
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
        Schema::dropIfExists('installments');
    }
};
