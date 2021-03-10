<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentationsModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentations_models', function (Blueprint $table) {
            $table->id();
            $table->string('title',100)->unique();
            $table->string('slug',100)->unique();
            $table->longText('information');
            $table->longText('attention');
            $table->string('authorization',20);
            $table->enum('state',['active','nonactive']);
            $table->string('created_by_name',100);
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
        Schema::dropIfExists('documentations_models');
    }
}
