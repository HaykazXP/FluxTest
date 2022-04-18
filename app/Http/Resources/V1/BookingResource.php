<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'car' => CarResource::make($this->car),
            'driver' => DriverResource::make($this->driver),
            'start_at' => $this->toDateTime($this->start_at),
            'end_at' => $this->toDateTime($this->end_at),
        ];
    }
}
