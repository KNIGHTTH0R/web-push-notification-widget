<?php

namespace App;

use Jenssegers\Mongodb\Model as Eloquent;

class SentNotification extends Eloquent
{
    protected $attributes = array(
        'user_id' => '',
        'notification_id' => '',
        'gcm_response' => '',
        'registration_ids' => ''
    );

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'notification_id', 'gcm_response', 'registration_ids'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function notification()
    {
        return $this->belongsTo('App\Notification');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
