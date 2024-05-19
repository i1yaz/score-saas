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
        Schema::connection('landlord')->create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('new')->comment('new|processing|failed|completed');
            $table->string('gateway')->default(null)->comment('stripe|paypal | etc| all-gateways');
            $table->string('type')->default(null)->comment('delete-database | cancel-subscription | delete-plan | update-plan-name |  update-plan-price');
            $table->string('payload_1')->default('')->comment('subscription id | plan id | etc');
            $table->text('payload_2')->nullable()->comment('optional data');
            $table->text('payload_3')->nullable();
            $table->text('payload_4')->nullable();
            $table->text('payload_5')->nullable();
            $table->integer('attempts')->default(0);
            $table->string('manual_action_required')->default('no')->comment('yes|no (can use this in the future to show admin an action log)');
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('landlord')->dropIfExists('schedules');
    }
};
