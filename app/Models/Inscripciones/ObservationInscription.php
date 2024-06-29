<?php

namespace App\Models\Inscripciones;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservationInscription extends Model
{
    use HasFactory;

    protected $table = 'observations_inscriptions';

    protected $fillable = [
        'inscription_id',
        'user_id',
        'description',
        'status'
    ];

    protected $hidden = ['updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
