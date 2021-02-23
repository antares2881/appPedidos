<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalleproducto extends Model
{
    public function productos(){
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function presentaciones(){
        return $this->belongsTo(Presentacione::class, 'presentacione_id');
    }

    public function categorias(){
        return $this->hasOneThrough(
            Categoria::class,
            Producto::class,
            'id', // Foreign key on the cars table...
            'id', // Foreign key on the owners table...
            'producto_id', // Local key on the mechanics table...
            'categoria_id' // Local key on the cars table...
        );
        
    }

}
