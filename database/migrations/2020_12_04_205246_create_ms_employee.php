<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->unsignedInteger('departement_id');
            $table->date('birth_date');
            $table->string('city');
            $table->text('address');
            $table->text('address_2')->nullable();
            $table->string('postcode')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('hp')->nullable();
            $table->string('password');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('departement_id')->references('id')->on('ms_departements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ms_employees');
    }
}
