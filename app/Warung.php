<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warung extends Model
{
    protected $fillable = [
       'user_id', 'name','description','address','status','latitude','longitude'
    ];

    public function photos()
    {
        return $this->hasMany('App\PhotoWarung');
    }

    public function foods() 
    {
        return $this->hasMany('App\Food');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
