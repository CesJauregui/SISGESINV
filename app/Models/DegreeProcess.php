<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DegreeProcess extends Model
{
    use HasFactory;

    //protected $table = 'procesos_titulaciones';
    protected $fillable = [
        'file',
        'professional_school',
        'thesis_project_title',
        'office_number',
        'resolution_number',
        'general_status',
    ];

    public function graduates()
    {
        return $this->belongsToMany(User::class, 'degree_user', 'degree_processes_id', 'user_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'degree_processes_id');
    }
}
