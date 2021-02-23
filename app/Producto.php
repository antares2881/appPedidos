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
}
