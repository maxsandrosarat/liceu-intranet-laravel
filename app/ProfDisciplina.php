<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfDisciplina extends Model
{
    public function disciplina(){
        return $this->belongsTo('App\Disciplina');
    }

    public function prof(){
        return $this->belongsTo('App\Prof');
    }
}
