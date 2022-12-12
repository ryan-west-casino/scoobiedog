<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wainwright_gametemplate', function (Blueprint $table) {
            $table->id('id')->index();
            $table->string('gid', 200);
            $table->string('game_data', 18000);
            $table->string('game_type', 200);
            $table->string('debit', 55);
            $table->string('credit', 55);
            $table->string('round_id', 255);
            $table->string('round_state', 255);
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
        Schema::dropIfExists('wainwright_gametemplate');
    }
};