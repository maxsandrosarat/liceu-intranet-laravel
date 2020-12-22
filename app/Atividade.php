<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
    public function prof(){
        return $this->belongsTo('App\Prof');
    }
    
    public function turma(){
        return $this->belongsTo('App\Turma');
    }

    public function disciplina(){
        return $this->belongsTo('App\Disciplina');
    }

    function aluno(){
        return $this->belongsToMany("App\Aluno", "atividade_retornos");
    }

    public function retornos()
    {
        return $this->hasMany('App\AtividadeRetorno')
            ->select( \DB::raw('aluno_id') )
            ->groupBy('aluno_id')
            ->orderBy('aluno_id');
    }
}
