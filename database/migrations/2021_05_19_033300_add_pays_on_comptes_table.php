<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaysOnComptesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
        //
    }
}
