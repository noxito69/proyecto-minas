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
        Schema::create('fleet', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable();
            $table->string('class')->nullable();
            $table->string('equipment_no')->nullable();
            $table->float('programmed_and_non_programmed_stop')->nullable();
            $table->string('availability')->nullable();
            $table->float('operating_time')->nullable();
            $table->string('availability_use')->nullable();
            $table->float('stand_by')->nullable();
            $table->float('tonnage')->nullable();
            $table->integer('ton_per_hour')->nullable();
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
        //
    }
};
