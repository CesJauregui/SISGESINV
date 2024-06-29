<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const UDI = 'UDI';
    const DOCENTE = 'Docente';
    const EGRESADO = 'Egresado';
    const ESTUDIANTE = 'Estudiante';
    const SEMILLERO = 'Semillero';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role',
        'name',
        'surnames',
        'email',
        'password',
        'phone',
        'code',
        'discharge_date',
        'cycle',
        'career',
        'line',
        'sublines',
        'is_reviewer',
        'is_advisor',
        'is_jury',
        'orcid',
        'cip',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function degree_processes()
    {
        return $this->belongsToMany(DegreeProcess::class, 'degree_user', 'user_id');
    }

    public function inscriptions()
    {
        return $this->belongsToMany(Inscription::class, 'inscription_user');
    }
}
