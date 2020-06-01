<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    public function tipo_ocorrencia(){
        return $this->belongsTo('App\TipoOcorrencia');
    }

    public function aluno(){
        return $this->belongsTo('App\Aluno');
    }

    public function disciplina(){
        return $this->belongsTo('App\Disciplina');
    }

    public function prof(){
        return $this->belongsTo('App\Prof');
    }

    public function turma(){
        return $this->belongsTo('App\Turma');
    }
}
