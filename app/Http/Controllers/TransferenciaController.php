<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Detalleproducto;
use App\Productotransferencia;
use App\Transferencia;

class TransferenciaController extends Controller
{
    public function __construct(){
        return $this->middleware('auth');
    }

    public function index(Request $request){

        if($request->ajax()){
            if(Auth::user()->role_id == 1){
                $transferencias = Transferencia::with(['clientes', 'estados', 'productos'])->get();
            }else{
                $cliente = Cliente::where('user_id', Auth::user()->id)->get();
                $transferencias = Transferencia::with(['clientes', 'estados', 'productos'])->where('cliente_id', $cliente[0]->id)->get();
            }
            return $transferencias;
        }else{
            return view('historial.transferencias.index');
        }
        
    }

    public function findNumberTransferencia($numero){
        $transferencia = Transferencia::where('numero', $numero)->get();
        return $transferencia;
    }

    public function updateState($id){
        
        $transferencia = Transferencia::find($id);
        $transferencia->estado_id = 3;
        $transferencia->save();

        return 'ok';
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
            $producto_transferencia->entregados = $request->pedidos[$i]['entregar'];

            $producto_transferencia->save();

            $producto = Detalleproducto::find($request->pedidos[$i]['id']);
            $producto->stock = $request->pedidos[$i]['stock'] - $request->pedidos[$i]['entregar'];

            $producto->save();
        }

        return 'ok';

    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required',
            'numero' => 'required',
            'fecha' => 'required',
            'total' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 200);
        }

        $transferencia = Transferencia::find($id);

        $transferencia->cliente_id = $request->cliente_id;
        $transferencia->mayorista_id = 1;
        $transferencia->estado_id = 1;
        $transferencia->numero = $request->numero;
        $transferencia->fecha = $request->fecha;
        $transferencia->valor = $request->total;

        $transferencia->save();

        $productos = Productotransferencia::where('transferencia_id', $request->id)->delete();
        
        for ($i=0; $i < count($request->pedidos); $i++) { 
            $producto_transferencia = new Productotransferencia();
            $producto_transferencia->detalleproducto_id = $request->pedidos[$i]['id'];
            $producto_transferencia->transferencia_id = $request->id;
            $producto_transferencia->cantidad = $request->pedidos[$i]['cantidad'];

            $producto_transferencia->save();
        }

        return 'ok';

    }
}
