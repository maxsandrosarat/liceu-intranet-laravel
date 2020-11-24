<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Simulado extends Model
{
    public function series()
    {
        return $this->hasMany('App\Questao')
            ->select( \DB::raw('serie') )
            ->groupBy('serie')
            ->orderBy('serie');
    }
}
