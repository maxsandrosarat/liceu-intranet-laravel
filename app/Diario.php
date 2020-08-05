<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diario extends Model
{
    public function turma(){
        return $this->belongsTo('App\Turma');
    }
    
    public function disciplina(){
        return $this->belongsTo('App\Disciplina');
    }

    public function prof(){
        return $this->belongsTo('App\Prof');
    }
}
