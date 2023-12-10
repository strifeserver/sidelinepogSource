<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToFightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fights', function (Blueprint $table) {
            $table->decimal('ghost_bet_meron',16,6)->after('result')->default(0);
            $table->decimal('ghost_bet_wala',16,6)->after('result')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fights', function (Blueprint $table) {
            $table->dropColumn('ghost_bet_meron');
            $table->dropColumn('ghost_bet_wala');
        });
    }
}
