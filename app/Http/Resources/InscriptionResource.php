<?php

namespace App\Http\Resources;

use App\Models\Inscripcion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InscriptionResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'degree_processes_id' => $this->degree_processes_id,
            'reception_date_faculty' => $this->reception_date_faculty,
            'approval_date_udi' => $this->approval_date_udi,
            'status' => $this->status,
            'teachers' => TeacherResource::collection($this->teachers),
            'observations' => $this->observations,
            'archives' => $this->archives
        ];
    }
}
