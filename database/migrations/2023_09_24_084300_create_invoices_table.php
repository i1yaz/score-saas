<?php

use App\Models\Invoice;
use App\Models\InvoicePackageType;
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
            $table->bigIncrements('id')->startingValue(Invoice::ID_START);
            $table->foreignIdFor(InvoicePackageType::class)->comment('This is the invoice package type it looks like the type of package like Tutoring Package or Monthly Invoice etc')->constrained();
            $table->timestamp('due_date')->nullable();
            $table->timestamp('fully_paid_at')->nullable();
            $table->text('general_description')->nullable();
            $table->text('detailed_description')->nullable();
            $table->boolean('email_to_parent')->default(false);
            $table->boolean('email_to_student')->default(false);
            $table->decimal('amount_paid')->default(0);
            $table->string('paid_status')->default(Invoice::DRAFT);
            $table->string('paid_by_modal')->nullable();
            $table->bigInteger('paid_by_id')->nullable();
            $table->string('invoiceable_type')->comment('This is the type of package like Tutoring Package or Monthly Invoice etc');
            $table->bigInteger('invoiceable_id')->comment('This is the primary key of above type of package');
            $table->boolean('status')->default(true);
            $table->string('auth_guard');
            $table->bigInteger('added_by');
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
