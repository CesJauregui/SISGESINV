<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DegreeProcessesResource extends JsonResource
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
            'file' => $this->file,
            'professional_school' => $this->professional_school,
            'thesis_project_title' => $this->thesis_project_title,
            'office_number' => $this->office_number,
            'resolution_number' => $this->resolution_number,
            'general_status' => $this->general_status,
            'inscriptions' => InscriptionResource::collection($this->whenLoaded('inscriptions')),
            'graduates' => GraduateResource::collection($this->whenLoaded('graduates'))
        ];
    }
}
