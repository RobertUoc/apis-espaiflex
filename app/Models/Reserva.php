<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = '_t_reserves';

    protected $fillable = [
        'sala',
        'dia_inici',
        'dia_fi',
        'hora_inici',
        'hora_fi',
        'frequencia',
        'dilluns',
        'dimarts',
        'dimecres',
        'dijous',
        'divendres',
        'dissabte',
        'diumenge',
        'tipo',
        'dia_mes',
        'el_semana',
        'el_dia',        
        'import',
        'actiu'.
        'id_user',
        'data_creacio',
        'data_update',
        'data_delete',
    ];

    public function salaRel()
    {
        return $this->belongsTo(Sala::class, 'sala');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function complements()
    {
        return $this->belongsToMany(
            Complement::class,
            '_t_reserves_in_complements',
            'id_reserves',
            'id_complements'
        );
    }

    public function comentaris()
    {
        return $this->belongsToMany(
            Comentari::class,
            '_t_reserves_comentaris',
            'id_reserves',            
        );
    }

}