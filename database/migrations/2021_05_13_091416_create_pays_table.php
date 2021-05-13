<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom');
            $table->integer('code');
            $table->timestamps();
        });

        Schema::table('salons', function (Blueprint $table) {
            $table->unsignedBigInteger('pays_id')->nullable()->after("compte_id");
        });

        Schema::table('salons', function (Blueprint $table) {
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
        Schema::dropIfExists('pays');
    }
}
