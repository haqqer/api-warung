<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhotoWarung extends Model
{
    protected $fillable = [
        'user_id', 'warung_id', 'path'
    ];

    public function warung()
    {
        return $this->belongsTo('App\Warung');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
