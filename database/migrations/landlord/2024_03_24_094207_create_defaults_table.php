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
        Schema::connection('landlord')->create('defaults', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->text('timezone')->nullable();
            $table->text('date_format')->comment('d-m-Y | d/m/Y | m-d-Y | m/d/Y | Y-m-d | Y/m/d | Y-d-m | Y/d/m');
            $table->text('datepicker_format')->comment('dd-mm-yyyy | mm-dd-yyyy');
            $table->text('currency_code')->nullable();
            $table->text('currency_symbol')->nullable();
            $table->text('currency_position')->comment('left|right');
            $table->text('decimal_separator')->nullable();
            $table->text('thousand_separator')->nullable();
            $table->string('language_default', 40)->default('en')->comment('english|french|etc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defaults');
    }
};
