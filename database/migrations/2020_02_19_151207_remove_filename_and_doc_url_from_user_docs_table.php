<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFilenameAndDocUrlFromUserDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_docs', function (Blueprint $table) {
            $table->dropColumn('filename');
            $table->dropColumn('doc_url');
        });
        Schema::table('doc_version', function (Blueprint $table) {
            $table->string('filename')->default('');
            $table->string('doc_url')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_docs', function (Blueprint $table) {
            $table->string('filename')->default('');
            $table->string('doc_url')->default('');
        });
        Schema::table('doc_version', function (Blueprint $table) {
            $table->dropColumn('filename');
            $table->dropColumn('doc_url');
        });
    }
}
