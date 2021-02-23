<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public function municipios(){
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }
}
