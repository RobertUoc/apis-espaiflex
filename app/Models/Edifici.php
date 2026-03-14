<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edifici extends Model
{
    protected $table = '_t_edificis';
    protected $fillable = [
        'nom', 'id_provincia', 'imatge', 'descripcio', 'actiu', 'latitud', 'longitud'
    ];    
    
}
