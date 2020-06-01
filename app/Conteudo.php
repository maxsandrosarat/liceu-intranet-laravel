<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conteudo extends Model
{
    public function disciplina(){
        return $this->belongsTo('App\Disciplina');
    }
}
