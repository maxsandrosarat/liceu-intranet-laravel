<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListaAtividade extends Model
{
    public function disciplina(){
        return $this->belongsTo('App\Disciplina');
    }
}
