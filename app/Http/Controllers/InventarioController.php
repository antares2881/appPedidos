<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Fechaentrada;
use App\Detalleproducto;

class InventarioController extends Controller
{
    public function entradas(){
        return view('inventarios.entradas.index');
    }

    public function stocks(){
        return view('inventarios.stocks.index');
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'numero_factura' => 'required|unique:fechaentradas',
            'fecha' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 200);
        }

        if(count($request->entradas) == 0){
            $error = array('Productos' => 'No puedes registrar una nueva entrada sin productos relacionados' );
            return response()->json($error, 200);
        }

        for ($i=0; $i < count($request->entradas); $i++) { 

            $entrada = new Fechaentrada();
            $entrada->detalleproducto_id = $request->entradas[$i]['id'];
            $entrada->cantidad = $request->entradas[$i]['cantidad'];
            $entrada->precio_entrada = $request->entradas[$i]['precio'];
            $entrada->fecha = $request->fecha;
            $entrada->numero_factura = $request->numero_factura;

            $entrada->save();

            $detalleproducto = Detalleproducto::find($request->entradas[$i]['id']);
            $detalleproducto->stock =  $request->entradas[$i]['cantidad'] + $request->entradas[$i]['stock'];
            $detalleproducto->precio = $request->entradas[$i]['precio'];
            
            $detalleproducto->save();
        }

        return 'ok';
    }
}
