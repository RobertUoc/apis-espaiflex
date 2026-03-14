<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complement extends Model
{
    protected $table = '_t_complements';

    protected $fillable = [
        'descripcio',
        'preu',
        'actiu'
    ];
}