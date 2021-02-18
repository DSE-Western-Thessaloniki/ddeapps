<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExactCopies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exact_copies', function (Blueprint $table) {
            $table->bigInteger('created_by')->unsigned()->change();
            $table->bigInteger('updated_by')->unsigned()->change();
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
        Schema::table('exact_copies', function (Blueprint $table) {
            $table->dropForeign('exact_copies_created_by_foreign');
            $table->dropForeign('exact_copies_updated_by_foreign');
        });
    }
}
