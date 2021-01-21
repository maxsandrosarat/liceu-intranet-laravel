<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Planejamento extends Model
{
    public function series()
    {
        return $this->hasMany('App\AnexoPlanejamento')
            ->select( \DB::raw('serie') )
            ->groupBy('serie')
            ->orderBy('serie');
    }
}
