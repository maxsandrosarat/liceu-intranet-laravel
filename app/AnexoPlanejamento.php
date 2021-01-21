<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnexoPlanejamento extends Model
{
    public function planejamento(){
        return $this->belongsTo('App\Planejamento');
    }

    public function disciplina(){
        return $this->belongsTo('App\Disciplina');
    }
}
