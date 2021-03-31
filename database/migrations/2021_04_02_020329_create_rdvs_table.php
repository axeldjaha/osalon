<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRdvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rdvs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date("date");
            $table->time("heure")->nullable();
            $table->string("nom")->nullable();
            $table->string("telephone")->nullable();
            $table->unsignedBigInteger('salon_id');
            $table->timestamps();

        });

        Schema::table('rdvs', function (Blueprint $table) {
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
        Schema::dropIfExists('rdvs');
    }
}
