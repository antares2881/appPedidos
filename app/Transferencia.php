<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transferencia extends Model
{
    public function clientes(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function estados(){
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function mayoristas(){
        return $this->belongsTo(Mayorista::class, 'mayorista_id');
    }

    public function productos(){
        return $this->hasMany(Productotransferencia::class, 'transferencia_id');
    }
}
