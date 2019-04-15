<?php

namespace App\Http\Resources;

class OrderResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'user'          => $this->user()->select('id', 'name', 'phone')->first(),
            'tariff'        => $this->tariff()->select('name')->first()->name,
            'location'      => $this->location()->select('city')->first()->city,
            'address'       => $this->address,
            'started_at'    => $this->started_at,
            'ended_at'      => $this->ended_at,
        ];
    }
}
