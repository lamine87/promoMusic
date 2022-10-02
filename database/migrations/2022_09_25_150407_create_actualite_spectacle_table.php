<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActualiteSpectacleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actualite_spectacle', function (Blueprint $table) {
            $table->unsignedBigInteger('actualite_id');
            $table->foreign('actualite_id')->references('id')->on('actualites')->onDelete('cascade');

            $table->unsignedBigInteger('spectacle_id');
            $table->foreign('spectacle_id')->references('id')->on('spectacles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actualite_spectacle');
    }
}
