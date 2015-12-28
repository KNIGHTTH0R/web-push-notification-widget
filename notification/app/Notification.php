<?php

namespace App;

use Jenssegers\Mongodb\Model as Eloquent;

class Notification extends Eloquent
{
    protected $attributes = array(
        'title' => '',
        'body' => '',
        'icon_url' => '',
        'redirect_url' => '',
        'image_url' => '',
        'user_id' => '',
        'segment' =>''
    );

    /**
     * @var array
     */
    protected $fillable = ['title','body', 'icon_url', 'redirect_url', 'image_url', 'segment'];

    /**
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'user_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sentNotifications()
    {
        return $this->hasMany('App\SentNotification');
    }

    public function segment()
    {
        return $this->embedsOne('App\Segment');
    }
}
