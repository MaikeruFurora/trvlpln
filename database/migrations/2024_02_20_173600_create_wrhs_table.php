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
        Schema::create('wrhs', function (Blueprint $table) {
            $table->id();
            $table->string('name',50)->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('is_show')->default(true);
            $table->string('slug',100)->nullable();
            $table->string('created_by',100)->nullable();
            $table->string('modified_by',100)->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('wrhs');
    }
};
