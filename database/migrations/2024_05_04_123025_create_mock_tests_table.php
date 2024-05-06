<?php

use App\Models\TutoringLocation;
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
        Schema::create('mock_tests', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignIdFor(TutoringLocation::class)->constrained();
            $table->morphs('proctorable');
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
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
        Schema::drop('mock_tests');
    }
};
