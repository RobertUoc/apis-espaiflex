<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentari extends Model
{
    protected $table = '_t_reserves_comentaris';

    protected $fillable = [
        'id',
        'id_reserves',
        'id_user',
        'comentari',
        'puntuacio'
    ];
    
}