<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisplayNameColumnToGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->string('display_name')->after('name');
            $table->integer('multiplier1')->after('status')->default(0);
            $table->integer('multiplier2')->after('multiplier1')->default(0);
            $table->integer('multiplier3')->after('multiplier2')->default(0);
            $table->integer('jackpot')->after('multiplier3')->default(0);
            $table->enum('type',['banker','commission'])->default('commission')->after('display_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('display_name');
            $table->dropColumn('type');
            $table->dropColumn('multiplier1');
            $table->dropColumn('multiplier2');
            $table->dropColumn('multiplier3');
            $table->dropColumn('jackpot');
        });
    }
}
