<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TurmaDisciplina extends Model
{
    public function disciplina(){
        return $this->belongsTo('App\Disciplina');
    }

    public function turma(){
        return $this->belongsTo('App\Turma');
    }
}
