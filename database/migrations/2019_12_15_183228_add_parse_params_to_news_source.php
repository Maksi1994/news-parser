<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParseParamsToNewsSource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news_sources', function (Blueprint $table) {
            $table->boolean('show');
            $table->boolean('active_parse');
            $table->integer('parse_interval');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news_sources', function (Blueprint $table) {
            $table->dropColumn(['show', 'active_parse', 'parse_interval']);
        });
    }
}
