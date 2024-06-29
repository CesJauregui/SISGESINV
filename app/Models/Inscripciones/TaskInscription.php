<?php

namespace App\Models\Inscripciones;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskInscription extends Model
{
    use HasFactory;

    protected $table = 'tasks_inscriptions';

    protected $fillable = [
        'inscription_id',
        'user_id',
        'description',
        'status'
    ];

    protected $hidden = [
        'id'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
