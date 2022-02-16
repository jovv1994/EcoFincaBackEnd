<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('description');
            $table->string('quantity');
            $table->string('image');
            $table->string('address');
            $table->string('date')->default('');
            $table->string('hour')->default('');
            $table->string('score')->default('');
            $table->string('rejected')->default('');
            $table->string('scorecomment')->default('');
            $table->unsignedBigInteger('for_user_id');
            $table->foreign('for_user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->string('state')->default('Pendiente');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
