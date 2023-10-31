<?php

use App\Models\Invoice;
use App\Models\Transaction;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Invoice::class)->constrained();
            $table->smallInteger('payment_gateway_id');
            $table->string('transaction_id')->comment('payment gateway\'s payment id ');
            $table->float('amount')->default(0);
            $table->string('paid_by_modal')->comment('This is the model of the payer like, Student,Parent of Client')->nullable();
            $table->unsignedBigInteger('paid_by_id')->comment('This is the primary key of above model')->nullable();
            $table->text('meta');
            $table->unsignedSmallInteger('status');
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
        Schema::drop('payments');
    }
};
