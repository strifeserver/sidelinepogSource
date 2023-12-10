<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOpIdColumnToBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bets', function (Blueprint $table) {
            $table->unsignedBigInteger('silver_id')->nullable()->after('updated_at');
            $table->unsignedBigInteger('subop_id')->nullable()->after('ma_id');
            $table->unsignedBigInteger('op_id')->nullable();
            $table->foreign('op_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('subop_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('silver_id')->references('id')->on('users')->onDelete('set null');
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
            $table->dropColumn('op_id');
            $table->dropColumn('subop_id');
            $table->dropColumn('silver_id');
        });
    }
}
