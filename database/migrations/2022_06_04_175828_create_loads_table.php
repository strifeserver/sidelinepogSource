<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requested_by')->nullable(); //buyer
            $table->unsignedBigInteger('requested_to')->nullable(); //loader
            $table->string('reference_number');
            $table->string('screenshot');
            $table->decimal('amount',16,6)->default(0.00);
            $table->unsignedBigInteger('loaded_by')->nullable();
            $table->enum('status',['pending','completed','cancelled'])->default('pending');
            $table->timestamps();

            $table->foreign('loaded_by')->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('loads');
    }
}
