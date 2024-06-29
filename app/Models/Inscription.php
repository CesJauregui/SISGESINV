<?php

namespace App\Models;

use App\Models\Inscripciones\ArchiveInscripcion;
use App\Models\Inscripciones\ArchiveInscription;
use App\Models\Inscripciones\ObservationInscription;
use App\Models\Inscripciones\TaskInscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'degree_processes_id',
        'reception_date_faculty',
        'approval_date_udi',
        'status'
    ];

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'inscription_user');
    }

    public function degree_processes(): BelongsTo
    {
        return $this->belongsTo(DegreeProcess::class, 'degree_processes_id');
    }

    public function observations()
    {
        return $this->hasMany(ObservationInscription::class);
    }

    public function archives()
    {
        return $this->hasMany(ArchiveInscription::class, 'inscription_id');
    }

    public function tasks()
    {
        return $this->hasMany(TaskInscription::class);
    }
}
