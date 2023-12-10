<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgentIdColumnToBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bets', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('ma_id')->nullable();

            $table->foreign('agent_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('ma_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bets', function (Blueprint $table) {
            $table->dropColumn('ma_id');
            $table->dropColumn('agent_id');
        });
    }
}
