<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdMobilAndSupir extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_requests', function (Blueprint $table) {
            $table->unsignedInteger('supir_id')->nullable();
            $table->unsignedInteger('mobil_id')->nullable();

            $table->foreign('supir_id')->references('id')->on('ms_supirs');
            $table->foreign('mobil_id')->references('id')->on('ms_mobils');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_requests', function (Blueprint $table) {
            $table->dropColumn(['supir_id', 'mobil_id']);
            $table->dropForeign(['supir_id', 'mobil_id']);
        });
    }
}
