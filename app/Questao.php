<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questao extends Model
{
    protected $table = 'questoes';

    public function simulado(){
        return $this->belongsTo('App\Simulado');
    }

    public function disciplina(){
        return $this->belongsTo('App\Disciplina');
    }
}
