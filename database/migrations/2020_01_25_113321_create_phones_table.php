<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',13);
            $table->timestamps();
        });

        Schema::table('phones', function (Blueprint $table) {
            $table->unique('name');
        });

        Schema::create('phone_user', function (Blueprint $table) {
            $table->unsignedBigInteger('phone_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::table('phone_user', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('phone_id')->references('id')->on('phones')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phone_user');
        Schema::dropIfExists('phones');
    }
}
