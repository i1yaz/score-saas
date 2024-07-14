<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('status', 30)->default('new');
            $table->string('source', 100)->nullable();
            $table->string('reference', 100)->nullable();
            $table->string('type', 100)->nullable();
            $table->string('gateway_reference', 100)->nullable();
            $table->string('gateway_id', 100)->nullable();
            $table->string('transaction_type', 100)->nullable();
            $table->integer('added_by')->default(0);
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency', 40)->nullable();
            $table->date('payment_date')->nullable();
            $table->date('next_due_date')->nullable();
            $table->string('transaction_id', 150)->nullable();
            $table->string('subscription_id', 150)->nullable();
            $table->string('gateway_reference_type', 100)->nullable();
            $table->string('attributes_1', 100)->nullable();
            $table->string('attributes_2', 100)->nullable();
            $table->text('payload')->nullable();
            $table->text('comment')->nullable();
            $table->integer('attempts')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webhooks');
    }
};
