<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentLoanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'equipment'     => new EquipmentResource($this->whenLoaded('equipment')),
            'borrower_name' => $this->borrower_name,
            'purpose'       => $this->purpose,
            'start_time'    => $this->start_time,
            'end_time'      => $this->end_time,
            'returned_at'   => $this->returned_at,
            'notes'         => $this->notes,
            'created_at'    => $this->created_at,
        ];
    }
}
