<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCodeToIndicatifInPaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pays', function (Blueprint $table) {
            $table->renameColumn("code", "indicatif");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
