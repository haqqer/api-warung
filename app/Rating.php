<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'warung_id', 'user_id', 'score'
    ];
   
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function warung()
    {
        return $this->belongsTo('App\Warung');
    }

}
