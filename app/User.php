<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'username', 'email', 'role', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier() 
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    
    public function warung()
    {
        return $this->hasMany('App\Warung');
    }

    public function food()
    {
        return $this->hasMany('App\Food');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function photoWarung()
    {
        return $this->hasMany('App\PhotoWarung');
    }

    public function photoFood()
    {
        return $this->hasMany('App\PhotoFood');
    }
}
