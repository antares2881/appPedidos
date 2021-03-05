<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Abonopedido;

class AbonopedidoController extends Controller
{
    public function __construct(){
        return $this->middleware('auth');
    }

    public function store(Request $request){
        $abono = new Abonopedido();
        $abono->venta_id = $request->id;
        $abono->fecha = $request->fechaAbono;
        $abono->valor = $request->maximo;
        $abono->observacion = $request->observacion;

        $abono->save();

        return 'ok';
    }
}
