<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('bet_id')->nullable();
            $table->unsignedBigInteger('withdraw_id')->nullable();
            $table->unsignedBigInteger('load_id')->nullable();
            $table->decimal('amount',16,6)->default(0.00);
            $table->decimal('ending_balance',16,6)->default(0.00);
            //$table->enum('type',['bet-meron','bet-wala','bet-draw','bet-cancelled','withdraw-credits','withdraw-commission','load','refund','plasada-in','commission-in','plasada-out','commission-out'])->default('load');
            $table->string('type')->nullable();
            $table->enum('direction',['in','out'])->default('in');
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('bet_id')->references('id')->on('bets')->onDelete('set null');
            $table->foreign('withdraw_id')->references('id')->on('withdraws')->onDelete('set null');
            $table->foreign('load_id')->references('id')->on('loads')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
