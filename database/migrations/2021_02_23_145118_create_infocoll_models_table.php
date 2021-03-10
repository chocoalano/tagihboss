<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfocollModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infocoll_models', function (Blueprint $table) {
            $table->id();
            $table->string('title',100)->unique();
            $table->string('slug',100)->unique();
            $table->longText('information');
            $table->enum('state',['active','nonactive']);
            $table->rememberToken();
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
        Schema::dropIfExists('infocoll_models');
    }
}
