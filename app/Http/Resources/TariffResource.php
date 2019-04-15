<?php

namespace App\Http\Resources;

class TariffResource extends Resource
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
            'id'        => $this->id,
            'name'      => $this->name,
            'locations' => $this->locations, // TODO Ресурсы для локаций
            'duration'  => $this->duration,
            'price'     => $this->price,
        ];
    }
}
