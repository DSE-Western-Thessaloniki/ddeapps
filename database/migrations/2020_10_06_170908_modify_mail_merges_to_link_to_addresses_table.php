<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMailMergesToLinkToAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_merges', function (Blueprint $table) {
            $table->removeColumn('address');
            $table->removeColumn('name');
            $table->removeColumn('telephone');
            $table->removeColumn('email');
            $table->foreignId('address_id');
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
            $table->string('address');
            $table->string('name');
            $table->string('telephone');
            $table->string('email');
            $table->removeColumn('address_id');
        });
    }
}
