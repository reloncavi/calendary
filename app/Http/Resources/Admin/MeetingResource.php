<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'attendees'  => $this->attendees,
            'start_time' => $this->start_time,
            'end_time'   => $this->end_time,
            'created_at' => $this->created_at,
        ];
    }
}
