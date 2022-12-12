<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wainwright_casino_profiles', function (Blueprint $table) {
            $table->id('id')->index();
            $table->string('player_id', 100);
            $table->string('user_id', 100);
            $table->string('currency', 100)->default('USD');
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
        Schema::dropIfExists('wainwright_casino_profiles');
    }
};