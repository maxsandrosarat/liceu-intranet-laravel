<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListaCompra extends Model
{
    function produtos(){
        return $this->belongsToMany("App\Produto", "compra_produtos");
    }
}
