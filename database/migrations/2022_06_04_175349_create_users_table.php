<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('username')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('password')->nullable();
            $table->enum('status',['active','inactive'])->default('inactive');
            $table->enum('type',['super-admin','admin','operator','sub-operator','master-agent','gold-agent','silver-agent','declarator','loader','booster','player'])->default('player');
            $table->string('forgot_token')->nullable();
            $table->string('referral_code')->nullable();
            $table->unsignedBigInteger('referred_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->enum('referrer_type',['super-admin','admin','operator','sub-operator','master-agent','gold-agent','silver-agent'])->default('super-admin');
            $table->decimal('commission',6,2)->default(0.00);
            $table->foreign('referred_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
