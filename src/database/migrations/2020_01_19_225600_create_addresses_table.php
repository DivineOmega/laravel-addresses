<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('addressable_type');
            $table->unsignedBigInteger('addressable_id');
            $table->string('line_1');
            $table->string('line_2')->nullable();
            $table->string('town_city');
            $table->string('state_county');
            $table->string('postcode');
            $table->string('country_code');
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);
            $table->json('meta')->default('[]');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('addresses');
    }
}