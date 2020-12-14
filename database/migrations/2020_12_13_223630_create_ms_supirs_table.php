<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsSupirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_supirs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nip', 25);
            $table->string('nama', 25);
            $table->string('no_telp', 25);
            $table->enum('type', ['internal', 'eksternal']);
            $table->unsignedInteger('id_mobil');
            $table->timestamps();

            $table->foreign('id_mobil')->references('id')->on('ms_mobils');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ms_supirs');
    }
}
