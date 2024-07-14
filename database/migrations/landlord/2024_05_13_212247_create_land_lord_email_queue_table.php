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
        Schema::connection('landlord')->create('email_queues', function (Blueprint $table) {
            $table->id('id');
            $table->datetime('created')->nullable();
            $table->datetime('updated')->nullable();
            $table->string('to', 150)->nullable();
            $table->string('from_email', 150)->nullable()->comment('optional (used in sending client direct email)');
            $table->string('from_name', 150)->nullable()->comment('optional (used in sending client direct email)');
            $table->string('subject', 250)->nullable();
            $table->text('message')->nullable();
            $table->string('type', 10)->index()->default('general')->comment('general|pdf (used for emails that need to generate a pdf)');
            $table->text('attachments')->nullable();
            $table->string('resource_type', 10)->index()->nullable()->comment('e.g. invoice. Used mainly for deleting records, when resource has been deleted');
            $table->integer('resource_id')->index()->nullable();
            $table->string('pdf_resource_type', 50)->index()->nullable()->comment('estimate|invoice');
            $table->integer('pdf_resource_id')->index()->nullable()->comment('resource id (e.g. estimate id)');
            $table->datetime('started_at')->nullable()->comment('timestamp of when processing started');
            $table->string('status', 20)->index()->default('new')->comment('new|processing (set to processing by the cronjob, to avoid duplicate processing)');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->dropIfExists('email_queues');
    }
};
