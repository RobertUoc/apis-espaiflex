<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = '_t_provincies';

    protected $fillable = [
        'nom',   
        'codi'
    ];
    
    public $timestamps = false;
}