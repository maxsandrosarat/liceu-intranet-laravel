<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }

    public function listaCompra(){
        return $this->belongsToMany("App\ListaCompra", "compra_produtos");
    }
}
