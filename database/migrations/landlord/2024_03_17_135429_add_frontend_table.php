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
        Schema::connection('landlord')->create('frontend', function (Blueprint $table) {
            $table->engine = "MyISAM";
            $table->id();
            $table->string('name')->nullable()->comment('e.g. hero-header');
            $table->string('group')->nullable()->comment('optional');
            $table->string('directory')->nullable();
            $table->string('filename')->nullable();
            $table->text('data_1')->nullable();
            $table->text('data_2')->nullable();
            $table->text('data_3')->nullable()->comment('optional');
            $table->text('data_4')->nullable()->comment('optional');
            $table->text('data_5')->nullable()->comment('optional');
            $table->text('data_6')->nullable()->comment('optional');
            $table->text('data_7')->nullable();
            $table->text('data_8')->nullable();
            $table->text('data_9')->nullable();
            $table->text('data_10')->nullable();
            $table->text('data_11')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frontend');
    }
};
