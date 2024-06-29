<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surnames' => $this->surnames,
            'code' => $this->code,
            'email' => $this->email,
            'phone' => $this->phone,
            'orcid' => $this->orcid,
            'cip' => $this->cip
        ];
    }
}
