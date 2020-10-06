<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MailMergesRenameTitleToProtocolNum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_merges', function (Blueprint $table) {
            $table->renameColumn('title', 'protocol_num');
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
            $table->renameColumn('protocol_num', 'title');
        });
    }
}
