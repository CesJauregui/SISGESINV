<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DegreeUser extends Model
{
    use HasFactory;

    protected $table = 'degree_user';

    protected $fillable = ['degree_processes_id', 'user_id'];

    public $timestamps = false;
}
