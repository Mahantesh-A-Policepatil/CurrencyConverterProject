<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency', function (Blueprint $table) {
            $table->increments('id');

            $table->string('currency_code')->unique();
            $table->string('country')->unique();
            $table->string('currency_name')->unique();
            $table->float('convertion_rate');
            /*
             $table->integer('user_id')->unsigned();
             $table->foreign('user_id')->references('id')->on('users');
            */
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
        Schema::dropIfExists('currency');
    }
}
