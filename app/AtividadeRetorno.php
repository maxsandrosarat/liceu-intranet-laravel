<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtividadeRetorno extends Model
{
    public function atividade(){
        return $this->belongsTo('App\Atividade');
    }

    public function aluno(){
        return $this->belongsTo('App\Aluno');
    }
}
