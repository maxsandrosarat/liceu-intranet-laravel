<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
    public function turma(){
        return $this->belongsTo('App\Turma');
    }

    public function disciplina(){
        return $this->belongsTo('App\Disciplina');
    }

    function aluno(){
        return $this->belongsToMany("App\Aluno", "atividade_retornos");
    }
}
