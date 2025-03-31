<?php
// PWS#02-23
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
        Schema::create('release_formato', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 255);
            $table->text('descrizione')->nullable()->default(NULL);
            $table->int('status')->default(1);
        });

        Schema::create('release_aspect_ratio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 255);
            $table->text('descrizione')->nullable()->default(NULL);
            $table->int('status')->default(1);
        });

        Schema::create('release_camera_format', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 255);
            $table->text('descrizione')->nullable()->default(NULL);
            $table->int('status')->default(1);
        });

        Schema::create('release_region_code', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 255);
            $table->text('descrizione')->nullable()->default(NULL);
            $table->int('status')->default(1);
        });

        Schema::create('release_tipologia', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 255);
            $table->text('descrizione')->nullable()->default(NULL);
            $table->int('status')->default(1);
        });

        Schema::create('release_canali_audio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 255);
            $table->text('descrizione')->nullable()->default(NULL);
            $table->int('status')->default(1);
        });

        $dbPrefix = DB::getTablePrefix();

        Schema::table($dbPrefix . 'release', function (Blueprint $table) {
            $table->int('formato')->nullable()->default(NULL)->after('release_note');
            $table->int('aspect_ratio')->nullable()->default(NULL)->after('formato');
            $table->int('camera_format')->nullable()->default(NULL)->after('aspect_ratio');
            $table->int('region_code')->nullable()->default(NULL)->after('camera_format');
            $table->int('tipologia')->nullable()->default(NULL)->after('region_code');
            $table->int('canali_audio')->nullable()->default(NULL)->after('tipologia');
            $table->int('subtitles')->nullable()->default(NULL)->after('canali_audio');
            $table->int('durata')->nullable()->default(NULL)->after('subtitles');
            $table->text('contenuti_speciali')->nullable()->default(NULL)->after('durata');
            $table->int('numero_catalogo')->nullable()->default(NULL)->after('contenuti_speciali');
            $table->int('barcode')->nullable()->default(NULL)->after('numero_catalogo');
            $table->text('crediti')->nullable()->default(NULL)->after('barcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('release_formato');
        Schema::dropIfExists('release_aspect_ratio');
        Schema::dropIfExists('release_camera_format');
        Schema::dropIfExists('release_region_code');
        Schema::dropIfExists('release_tipologia');
        Schema::dropIfExists('release_canali_audio');
    }
};
