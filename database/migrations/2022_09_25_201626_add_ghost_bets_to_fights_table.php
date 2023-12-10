<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGhostBetsToFightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fights', function (Blueprint $table) {
            $table->decimal('ghost_bet_blue',16,6)->after('ghost_bet_wala')->default(0);
            $table->decimal('ghost_bet_grey',16,6)->after('ghost_bet_blue')->default(0);
            $table->decimal('ghost_bet_red',16,6)->after('ghost_bet_grey')->default(0);
            $table->decimal('ghost_bet_yellow',16,6)->after('ghost_bet_red')->default(0);
            $table->decimal('ghost_bet_white',16,6)->after('ghost_bet_yellow')->default(0);
            $table->decimal('ghost_bet_pink',16,6)->after('ghost_bet_white')->default(0);
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
            $table->dropColumn('ghost_bet_blue');
            $table->dropColumn('ghost_bet_grey');
            $table->dropColumn('ghost_bet_red');
            $table->dropColumn('ghost_bet_yellow');
            $table->dropColumn('ghost_bet_white');
            $table->dropColumn('ghost_bet_pink');
        });
    }
}
