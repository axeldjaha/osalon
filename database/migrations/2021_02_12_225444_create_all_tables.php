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

        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('libelle');
            $table->bigInteger('prix');
            $table->unsignedBigInteger('salon_id');
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

        Schema::create('paniers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('total');
            $table->dateTime('date');
            $table->unsignedBigInteger('salon_id');
            $table->timestamps();
        });

        Schema::create('article_panier', function (Blueprint $table) {
            $table->integer('quantite');
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('panier_id');
        });

        Schema::create('rdvs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date("date");
            $table->time("heure")->nullable();
            $table->string("client")->nullable();
            $table->string("telephone")->nullable();
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

        Schema::create('salon_user', function (Blueprint $table) {
            $table->unsignedBigInteger('salon_id')->index();
            $table->unsignedBigInteger('user_id')->index();
        });

        Schema::create('sms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('to')->nullable();
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
