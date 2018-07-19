<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'nickname' => $this->nickname,
            'avatar' => $this->avatar,
            'activated' => $this->activated,
            'admin' => $this->admin,
            'blocked' => $this->blocked,
            'reason_blocked' => $this->reason_blocked,
            'password' => $this->password,
            'total_points' => $this->total_points,
            'total_games_played' => $this->total_games_played,
        ];
    }
}
