<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBackendTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offre_sms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quantite');
            $table->bigInteger('prix');
            $table->timestamps();
        });

        Schema::create('fakedatas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('data');
            $table->timestamps();
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom')->nullable();
            $table->string('telephone');
            $table->unsignedBigInteger('sms_groupe_id')->nullable();
            $table->timestamps();
        });

        Schema::create('backsms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('to');
            $table->text('message');
            $table->string('user');
            $table->timestamps();
        });

        Schema::create('sms_groupes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('intitule');
            $table->timestamps();
        });

        Schema::create('tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('access_token');
            $table->bigInteger('expires_in');
            $table->dateTime('valid_until');
            $table->timestamps();
        });

        /**
         * **************************************************************
         * FOREIGN KEYS
         * **************************************************************
         */

        Schema::table('contacts', function (Blueprint $table) {
            $table->foreign('sms_groupe_id')->references('id')->on('sms_groupes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offre_sms');
        Schema::dropIfExists('fakedatas');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('sms_groupes');
        Schema::dropIfExists('backsms');
    }
}
