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
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('invoice_package_type_id');
            $table->timestamp('due_date');
            $table->timestamp('fully_paid_at');
            $table->text('general_description');
            $table->text('detailed_description');
            $table->boolean('email_to_parent');
            $table->boolean('email_to_student');
            $table->float('amount_paid');
            $table->boolean('paid_status');
            $table->string('paid_by_modal');
            $table->bigInteger('paid_by_id');
            $table->string('invoiceable_type');
            $table->bigInteger('invoiceable_id');
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
        Schema::drop('invoices');
    }
};
