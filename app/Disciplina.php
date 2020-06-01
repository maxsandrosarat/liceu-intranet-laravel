<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    function turmas(){
        return $this->belongsToMany("App\Turma", "turma_disciplinas");
    }

    function profs(){
        return $this->belongsToMany("App\Prof", "prof_disciplinas");
    }
}
