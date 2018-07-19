<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    protected $fillable = [
        'name',
        'hidden_face_image_path',
        'active',
        'complete',
    ];


    public function games(){
        return $this->belongsToMany('App\Game');
    }
}
