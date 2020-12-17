<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_transaksi');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('departement_id');
            $table->string('destination');
            $table->text('description');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['PROCESS', 'RESERVED', 'APPROVED', 'CANCELED', 'OPEN'])->default("OPEN");
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('reserved_at')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('ms_employees');
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
        Schema::dropIfExists('car_requests');
    }
}
