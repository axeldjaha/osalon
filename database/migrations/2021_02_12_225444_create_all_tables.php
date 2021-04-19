<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abonnements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('montant');
            $table->dateTime('echeance');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('compte_id');
            $table->timestamps();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom')->nullable();
            $table->string('telephone')->nullable();
            $table->date('anniversaire')->nullable();
            $table->unsignedBigInteger('salon_id');
            $table->timestamps();
        });

        Schema::create('comptes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sms_balance')->default(0);
            $table->timestamps();
        });

        Schema::create('depenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('objet');
            $table->bigInteger('montant');
            $table->date('date_depense')->nullable();
            $table->unsignedBigInteger('salon_id');
            $table->timestamps();
        });

        Schema::create('prestations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('total');
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('salon_id');
            $table->timestamps();
        });

        Schema::create('salons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom');
            $table->string('adresse')->nullable();
            $table->string('telephone');
            $table->unsignedBigInteger('compte_id');
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom');
            $table->bigInteger('tarif');
            $table->unsignedBigInteger('salon_id');
            $table->timestamps();
        });

        Schema::create('sms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('to');
            $table->text('message');
            $table->dateTime('date');
            $table->string('user')->nullable();
            $table->unsignedBigInteger('salon_id');
            $table->timestamps();
        });

        Schema::create('types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('intitule');
            $table->bigInteger('montant');
            $table->integer('validity'); //nombre de jour
            $table->timestamps();
        });


        /**
         * **************************************************
         * ADMINISTRATION
         * **************************************************
         */
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('all_tables');
    }
}
