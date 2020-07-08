<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'warung_id', 'user_id', 'comment',  'status', 'score'
    ];

    public function photos()
    {
        return $this->hasMany('App\Photo');
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
