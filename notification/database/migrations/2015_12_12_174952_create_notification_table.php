<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration
{
    protected $attributes = array(
        '' => 9,
    );

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function($collection)
        {
            $collection->string('title');
            $collection->string('body');
            $collection->string('icon_url');
            $collection->string('image_url');
            $collection->string('redirect_url');
            $collection->foreign('user_id')->references('_id')->on('users');
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notifications');
    }
}
