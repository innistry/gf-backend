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
            'id'            => $this->id,
            'params'          => $this->params,
        ];
    }
}
