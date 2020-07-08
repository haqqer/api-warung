<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $fillable = [
        'user_id', 'warung_id', 'name','description','type','price'
    ];

    // public function comments()
    // {
    //     return $this->hasMany('App\Comment');
    // }

    public function photos()
    {
        return $this->hasMany('App\PhotoFood');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function warung()
    {
        return $this->belongsTo('App\Warung');
    }
}
