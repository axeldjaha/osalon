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
            $table->foreign('compte_id')->references('id')->on('comptes')->onDelete('cascade');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
        });

        Schema::table('depenses', function (Blueprint $table) {
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
        });

        Schema::table('paniers', function (Blueprint $table) {
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
        });

        Schema::table('article_panier', function (Blueprint $table) {
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('panier_id')->references('id')->on('paniers')->onDelete('cascade');
        });

        Schema::table('salons', function (Blueprint $table) {
            $table->foreign('compte_id')->references('id')->on('comptes')->onDelete('cascade');
            $table->foreign('pays_id')->references('id')->on('pays')->onDelete('set null');
        });

        Schema::table('salon_user', function (Blueprint $table) {
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });


        Schema::table('sms', function (Blueprint $table) {
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('compte_id')->references('id')->on('comptes')->onDelete('cascade');
        });

        Schema::table('comptes', function (Blueprint $table) {
            $table->unsignedBigInteger('pays_id')->nullable()->after("sms_balance");
        });

        Schema::table('comptes', function (Blueprint $table) {
            $table->foreign('pays_id')->references('id')->on('pays')->onDelete('set null');
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
