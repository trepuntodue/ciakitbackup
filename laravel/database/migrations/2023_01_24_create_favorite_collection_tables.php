<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('release_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('release_id');
            $table->unsignedInteger('user_id');
        });

        Schema::create('release_user_favorite', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('release_id');
            $table->unsignedInteger('user_id');
        });

        Schema::create('master_user_favorite', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('master_id');
            $table->unsignedInteger('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('release_user');
        Schema::dropIfExists('release_user_favorite');
        Schema::dropIfExists('master_user_favorite');
    }
};
