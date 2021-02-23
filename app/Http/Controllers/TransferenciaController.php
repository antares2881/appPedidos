<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Transferencia;
use App\Productotransferencia;

class TransferenciaController extends Controller
{
    public function __construct(){
        return $this->middleware('auth');
    }

    public function index(Request $request){

        if($request->ajax()){
            if(Auth::user()->role_id == 1){
                $transferencias = Transferencia::all();
            }else{
                $cliente = Cliente::where('user_id', Auth::user()->id)->get();
                $transferencias = Transferencia::where('cliente_id', $cliente[0]->id)->get();
            }
            return $transferencias;
        }else{
            return view('historial.transferencias.index');
        }
        
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required',
            'numero' => 'required|unique:transferencias',
            'fecha' => 'required',
            'total' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 200);
        }

        $transferencia = new Transferencia();
        $transferencia->cliente_id = $request->cliente_id;
        $transferencia->mayorista_id = 1;
        $transferencia->estado_id = 1;
        $transferencia->numero = $request->numero;
        $transferencia->fecha = $request->fecha;
        $transferencia->valor = $request->total;

        $transferencia->save();

        for ($i=0; $i < count($request->pedidos); $i++) { 
            $producto_transferencia = new Productotransferencia();
            $producto_transferencia->detalleproducto_id = $request->pedidos[$i]['id'];
            $producto_transferencia->transferencia_id = $transferencia->id;
            $producto_transferencia->cantidad = $request->pedidos[$i]['cantidad'];

            $producto_transferencia->save();
        }

        return 'ok';

    }
}
