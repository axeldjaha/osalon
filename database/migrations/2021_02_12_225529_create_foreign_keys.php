<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('abonnements', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
        });

        Schema::table('depenses', function (Blueprint $table) {
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
        });

        Schema::table('prestations', function (Blueprint $table) {
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
        });

        Schema::create('prestation_service', function (Blueprint $table) {
            $table->unsignedBigInteger('prestation_id')->index();
            $table->foreign('prestation_id')->references('id')->on('prestations')->onDelete('cascade');
            $table->unsignedBigInteger('service_id')->index();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });

        Schema::create('salon_user', function (Blueprint $table) {
            $table->unsignedBigInteger('salon_id')->index();
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
        });

        Schema::table('sms', function (Blueprint $table) {
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
        });

        Schema::table('liens', function (Blueprint $table) {
            $table->foreign('sms_id')->references('id')->on('sms')->onDelete('cascade');
        });

        Schema::table('images', function (Blueprint $table) {
            $table->foreign('lien_id')->references('id')->on('liens')->onDelete('cascade');
        });

        Schema::table('paiements', function (Blueprint $table) {
            $table->foreign('abonnement_id')->references('id')->on('abonnements')->onDelete('set null');
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('set null');
        });


        /**
         * **************************************************
         * ADMINISTRATION
         * **************************************************
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
        //Schema::dropIfExists('foreign_keys');
    }
}
