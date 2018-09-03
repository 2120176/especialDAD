<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;


class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'nickname',
        'admin',
        'blocked',
        'password',
        'reason_blocked',
        'reason_reactivated',
        'total_points',
        'total_games_played',
        'verified',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function games(){
        return $this->belongsToMany('App\Game');
    }

    public function findForPassport($identifier) {
        return $this->orWhere('email', $identifier)->orWhere('nickname', $identifier)->first();
    }

    /*public function verifyUser()
    {
        return $this->hasOne('App\VerifyUser');
    }

    public function Token()
    {
        return $this->hasOne('App\Token');
    }*/


}
