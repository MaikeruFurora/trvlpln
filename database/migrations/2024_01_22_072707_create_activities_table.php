<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('activity_list_id');
            $table->foreign('activity_list_id')->references('id')->on('activity_lists')->onDelete('cascade')->onUpdate('cascade');
            $table->string('client',100)->nullable();
            $table->string('sttus',20)->nullable();
            $table->text('note')->nullable();
            $table->string('osnum',100)->nullable();
            $table->dateTime('date_from');
            $table->dateTime('date_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
};
