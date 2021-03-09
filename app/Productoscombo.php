<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productoscombo extends Model
{
    public function productos(){
        return $this->hasOneThrough(
            Producto::class,
            Detalleproducto::class,
            'id', 
            'id', 
            'detalleproducto_id', 
            'producto_id' 
        );
    }

    public function presentaciones(){
        return $this->hasOneThrough(
            Presentacione::class,
            Detalleproducto::class,
            'id', 
            'id', 
            'detalleproducto_id', 
            'presentacione_id' 
        );
    }
}
