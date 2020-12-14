<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMailMergesToSaveDataAndSelectedCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_merges', function (Blueprint $table) {
            $table->dropColumn('data_id');
            $table->json('xlsxdata');
            $table->json('mergefields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mail_merges', function (Blueprint $table) {
            $table->foreignId('data_id')->nullable();
            $table->dropColumn('xlsxdata');
            $table->dropColumn('mergefields');
        });
    }
}
