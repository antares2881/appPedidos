<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fechaentrada extends Model
{
    public function detalle(){
        return $this->belongsTo(Detalleproducto::class, 'detalleproducto_id');
    }

    public function presentaciones(){
        return $this->hasOneThrough(
            Presentacione::class,
            Detalleproducto::class,
            'id', // Foreign key on the cars table...
            'id', // Foreign key on the owners table...
            'detalleproducto_id', // Local key on the mechanics table...
            'presentacione_id' // Local key on the cars table...
        ); 
    }

    public function productos(){
        return $this->hasOneThrough(
            Producto::class,
            Detalleproducto::class,
            'id', // Foreign key on the cars table...
            'id', // Foreign key on the owners table...
            'detalleproducto_id', // Local key on the mechanics table...
            'producto_id' // Local key on the cars table...
        ); 
    }

    public function ventas(){
        return $this->belongsTo(Venta::class, 'venta_id');
    }
}
