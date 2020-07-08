<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhotoFood extends Model
{
    protected $table = 'photo_foods';
    protected $fillable = [
        'user_id', 'food_id', 'path'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function food()
    {
        return $this->belongsTo('App\Food');
    }
}
