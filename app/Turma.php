<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    function disciplinas(){
        return $this->belongsToMany("App\Disciplina", "turma_disciplinas");
    }
}
