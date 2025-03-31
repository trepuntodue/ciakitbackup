<?php
// PWS#7-2
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
        Schema::create('master_relazioni', function (Blueprint $table) {
            $table->increments('id_relazione');
            $table->integer('master_id');
            $table->integer('elemento_id');
            $table->tinyInteger('tipo_relazione');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_relazioni');
    }
};
