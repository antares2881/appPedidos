<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    public function categorias(){
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function composiciones(){
        return $this->belongsTo(Composicione::class, 'composicione_id');
    }

    public function detalle(){
        return $this->hasMany(Detalleproducto::class, 'producto_id');
    }

    public function presentaciones(){
        return $this->hasManyThrough(
            Presentacione::class,
            Detalleproducto::class,
            'producto_id', // Foreign key on the cars table...
            'id', // Foreign key on the owners table...
            'id', // Local key on the mechanics table...
            'presentacione_id' // Local key on the cars table...
        );
    }
}
