<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMailMerges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_merges', function (Blueprint $table) {
            $table->bigInteger('created_by')->unsigned()->change();
            $table->bigInteger('updated_by')->unsigned()->change();
            $table->dropColumn('user_id');
            $table->foreign('created_by')
                ->references('id')->on('users');
            $table->foreign('updated_by')
                ->references('id')->on('users');
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
            $table->bigInteger('user_id')->unsigned();
            $table->dropForeign('mail_merges_created_by_foreign');
            $table->dropForeign('mail_merges_updated_by_foreign');
        });
    }
}
