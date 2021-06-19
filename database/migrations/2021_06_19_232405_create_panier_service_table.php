<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePanierServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panier_service', function (Blueprint $table) {
            $table->bigInteger('prix');
            $table->boolean('canceled')->default(false);
            $table->unsignedBigInteger('panier_id');
            $table->unsignedBigInteger('service_id');
        });

        Schema::table('panier_service', function (Blueprint $table) {
            $table->foreign('panier_id')->references('id')->on('paniers')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('panier_service');
    }
}
