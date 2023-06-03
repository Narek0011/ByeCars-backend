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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("brand_id");
            $table->string('model');
            $table->string("petrol");
            $table->string("sedan");
            $table->string("box");
            $table->string("year");
            $table->string("location");
            $table->integer("mileage");
            $table->integer("price");
            $table->integer("sale");
            $table->timestamps();

            $table->foreign('brand_id')
                ->references('id')->on('brands')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
};
