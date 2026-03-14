<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    protected $table = '_t_sales';

    protected $fillable = [
        'descripcio',
        'id_edifici',
        'preu',
        'actiu',
        'color',
        'missatge',
        'max_ocupacio',
        'horari',
        'imatge'
    ];

    public function complements()
    {
        return $this->belongsToMany(
            Complement::class,
            '_t_sales_in_complements',
            'id_sales',
            'id_complements'
        );
    }
}