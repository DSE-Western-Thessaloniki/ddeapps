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
            $table->dropColumn('address');
            $table->dropColumn('name');
            $table->dropColumn('telephone');
            $table->dropColumn('email');
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
            $table->dropColumn('address_id');
        });
    }
}
