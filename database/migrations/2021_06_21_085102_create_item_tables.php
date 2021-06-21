<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom');
            $table->bigInteger('prix');
            $table->integer('quantite')->default(1);
            $table->boolean('canceled')->default(false);
            $table->unsignedBigInteger('panier_id');
            $table->unsignedBigInteger('salon_id');
            $table->timestamps();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreign('panier_id')->references('id')->on('paniers')->onDelete('cascade');
            $table->foreign('salon_id')->references('id')->on('salons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
