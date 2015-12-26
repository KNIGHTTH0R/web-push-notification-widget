<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Model as Eloquent;

class Subscriber extends Eloquent
{
    protected $attributes = array(
        "did" => "",
        "device" => "",
        "platform" => ""
    );

    /**
     * @var array
     */
    protected $fillable = ['did', 'device', 'platform'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
