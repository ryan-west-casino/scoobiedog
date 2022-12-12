<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wainwright_gametemplate_queued', function (Blueprint $table) {
            $table->id('id')->index();
            $table->string('gid', 200);
            $table->string('slug', 250);
            $table->boolean('completed');
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
        Schema::dropIfExists('wainwright_gametemplate_queued');
    }
};