<?php

namespace App\Models\Inscripciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveInscription extends Model
{
    use HasFactory;
    protected $table = 'archives_inscriptions';
    protected $fillable = ['archives', 'inscription_id', 'user_id'];

    protected $hidden = ['updated_at'];
}
