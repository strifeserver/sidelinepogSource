<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requested_by')->nullable(); //buyer
            $table->unsignedBigInteger('requested_to')->nullable(); //loader
            $table->string('withdraw_method')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->decimal('amount',16,6)->default(0.00);
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->enum('type',['commission','credits'])->default('credits');
            $table->enum('status',['pending','master_agent_approval','processing','completed','cancelled'])->default('pending');
            $table->timestamps();

            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('requested_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdraws');
    }
}
