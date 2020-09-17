<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailMergesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_merges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('title');
            $table->string('logo_filename');
            $table->foreignId('logo_id');
            $table->string('address');
            $table->string('name');
            $table->string('telephone');
            $table->string('email');
            $table->date('date');
            $table->text('subject');
            $table->text('text');
            $table->foreignId('exact_copy_id');
            $table->foreignId('signature_id');
            $table->foreignId('data_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_merges');
    }
}
