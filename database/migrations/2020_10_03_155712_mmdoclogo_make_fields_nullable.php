<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MmdoclogoMakeFieldsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mmdoclogo', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
            $table->text('text')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mmdoclogo', function (Blueprint $table) {
            $table->string('image')->nullable(false)->change();
            $table->text('text')->nullable(false)->change();
        });
    }
}
