<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFNameMNameLNameToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::table('users', function (Blueprint $table) {
                $table->string('f_name')->default('');
                $table->string('l_name')->default('');
                $table->string('m_name')->default('');
                $table->string('phone',13)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['f_name','l_name','m_name','phone']);
        });
    }
}
