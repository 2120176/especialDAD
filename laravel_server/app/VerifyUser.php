<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerifyUser extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'id', 'user_id', 'token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
