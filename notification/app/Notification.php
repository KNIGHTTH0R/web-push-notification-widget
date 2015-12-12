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
        'user_id' => ''
    );

    /**
     * @var array
     */
    protected $fillable = ['title','body', 'icon_url', 'redirect_url', 'image_url'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
