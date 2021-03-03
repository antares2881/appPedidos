<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    public function entradas(){
        return $this->hasMany(Fechaentrada::class, 'venta_id');
    }
}
