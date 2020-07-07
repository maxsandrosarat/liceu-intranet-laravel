<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResponsavelAluno extends Model
{
    public function responsavel(){
        return $this->belongsTo('App\Responsavel');
    }

    public function aluno(){
        return $this->belongsTo('App\Aluno');
    }
}
