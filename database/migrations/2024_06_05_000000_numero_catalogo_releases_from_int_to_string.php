<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyNumeroCatalogoColumnTypeIn666ReleasesTable extends Migration
{
    /**
     * Esegui la migrazione.
     *
     * @return void
     */
    public function up()
    {   
        $dbPrefix = DB::getTablePrefix();
        Schema::table($dbPrefix . 'releases', function (Blueprint $table) {
            // Cambia il tipo di colonna `numero_catalogo` a VARCHAR(30)
            $table->string('numero_catalogo', 30)->change();
        });
    }

    /**
     * Reverti la migrazione.
     *
     * @return void
     */
    public function down()
    {
        $dbPrefix = DB::getTablePrefix();
        Schema::table($dbPrefix . 'releases', function (Blueprint $table) {
            // Ripristina il tipo di colonna `numero_catalogo` a INT
            $table->integer('numero_catalogo')->change();
        });
    }
}