<?php

namespace App;

use Jenssegers\Mongodb\Model as Eloquent;

class Segment extends Eloquent
{
    protected $attributes = array(
        'name' => '',
        'description' => '',
        'rules'=> ''
    );

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'rules'];

    /**
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];
}
