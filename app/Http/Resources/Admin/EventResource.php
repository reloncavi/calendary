<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'start_time' => $this->start_time,
            'end_time'   => $this->end_time,
            'venue'      => new VenueResource($this->whenLoaded('venue')),
            'created_at' => $this->created_at,
        ];
    }
}
