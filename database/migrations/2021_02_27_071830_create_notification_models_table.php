<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('event',['visit','activity','payment','helper']);
            $table->integer('user_id');
            $table->longText('desc');
            $table->timestamps();
        });
        Schema::create('notification_to', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('notification_models_id');
            $table->foreign('notification_models_id')->references('id')->on('notification_models')->constrained()->cascadeOnDelete();
            $table->integer('user_id');
            $table->enum('show',['y','n']);
            $table->timestamps();
        });
        Schema::create('notification_helper_approved', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('notification_to_id');
            $table->foreign('notification_to_id')->references('id')->on('notification_to')->constrained()->cascadeOnDelete();
            $table->enum('approved_state',['y','n']);
            $table->string('approved_img');
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
        Schema::dropIfExists('notification_models');
    }
}
