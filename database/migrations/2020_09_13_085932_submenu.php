<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Submenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('submenus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 25)->nullable();
            $table->string('link', 10)->nullable();
            $table->string('icon', 25)->nullable();
            $table->integer('active')->nullable();
            $table->integer('order')->nullable();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade')->onUpdate('cascade');
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
        //
        Schema::dropIfExists('submenus');
    }
}
