<?php
// PWS#13-bn
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Notifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dbPrefix = DB::getTablePrefix();

        Schema::table($dbPrefix . 'master', function (Blueprint $table) {
            $table->tinyInteger('master_bn')->default(0)->after('master_vt18');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($dbPrefix . 'master', function (Blueprint $table) {
            $table->dropColumn('master_bn');
        });
    }
}
