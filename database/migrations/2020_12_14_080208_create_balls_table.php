<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBallsTable extends Migration
{


    public function up()
    {
        Schema::create(
            'balls',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('nr_of_colors');
                /*We are cheating here, ideally the distribution and groups should
                 not be stored as json, they should be normalized */
                $table->text('distribution');
                $table->text('groups');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balls');
    }
}
