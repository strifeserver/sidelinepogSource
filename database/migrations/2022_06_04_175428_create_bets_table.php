<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('fight_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('amount',16,6)->default(0.00);
            $table->decimal('plasada',16,6)->default(0.00);
            $table->decimal('operator_commission',16,6)->default(0.00);
            $table->decimal('sub_operator_commission',16,6)->default(0.00);
            $table->decimal('master_agent_commission',16,6)->default(0.00);
            $table->decimal('gold_agent_commission',16,6)->default(0.00);
            $table->decimal('silver_agent_commission',16,6)->default(0.00);
            $table->string('bet')->nullable();
            $table->string('result')->nullable();
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('set null');
            $table->foreign('fight_id')->references('id')->on('fights')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bets');
    }
}
