<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSentNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_notifications', function($collection)
        {
            $collection->string('registration_ids');
            $collection->string('gcm_response');
            $collection->foreign('user_id')->references('_id')->on('user');
            $collection->foreign('notification_id')->references('_id')->on('notification');
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
        drop('sent_notifications');
    }
}
