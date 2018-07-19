<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'created_by',
        'created_at',
        'total_players',
        'deck_used'
    ];
    public function getWinnerName()
    {
		if ($this->winner == 1) {
            return $this->player1;
        } else if ($this->winner == 2) {
            return $this->player2;
        } else if (is_null($this->winner)) {
            return '';
        } else if ($this->winner == 0) {
            return 'tie';
        } 
        return "Unknown Winner";
    }

    public function players(){
        return $this->belongsToMany('App\User');
    }

    public function decks(){
        return $this->belongsToMany('App\Deck');
    }
}
