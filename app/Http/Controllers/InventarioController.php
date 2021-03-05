<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Detalleproducto;
use App\Fechaentrada;
use App\Venta;

class InventarioController extends Controller
{
    public function pedidosCalox(){
        return view('inventarios.entradas.index');
    }

    public function findPedidosCalox($num_pedido){
        $pedido = Fechaentrada::with(['detalle','presentaciones', 'productos','ventas'])->whereHas('ventas', function($query) use ($num_pedido){
            $query->where('num_pedido', $num_pedido);
        } )->get();
        return $pedido; 
    }

    public function stocks(){
        return view('inventarios.stocks.index');
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required',
            'num_pedido' => 'required|unique:ventas',
            'fecha' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 200);
        }

        if(count($request->entradas) == 0){
            $error = array('Productos' => 'No puedes registrar una nueva entrada sin productos relacionados' );
            return response()->json($error, 200);
        }

        $venta = new Venta();
        $venta->cliente_id = $request->cliente_id;
        $venta->num_pedido = $request->num_pedido;
        $venta->fecha = $request->fecha;
        $venta->valor = $request->valor;

        $venta->save();

        for ($i=0; $i < count($request->entradas); $i++) { 

            $entrada = new Fechaentrada();
            $entrada->detalleproducto_id = $request->entradas[$i]['id'];
            $entrada->venta_id = $venta->id;
            $entrada->cantidad = $request->entradas[$i]['cantidad'];
            $entrada->adicionales = $request->entradas[$i]['adicional'];
            $entrada->precio_entrada = $request->entradas[$i]['precio'];

            $entrada->save();

            if($request->cliente_id == 3){

                $detalleproducto = Detalleproducto::find($request->entradas[$i]['id']);
                $detalleproducto->stock =  $request->entradas[$i]['cantidad'] + $request->entradas[$i]['adicional'] + $request->entradas[$i]['stock'];
                $detalleproducto->precio = $request->entradas[$i]['precio'];
                
                $detalleproducto->save();
            }

        }

        return 'ok';
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required',
            'num_pedido' => 'required',
            'fecha' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 200);
        }

        if(count($request->entradas) == 0){
            $error = array('Productos' => 'No puedes registrar una nueva entrada sin productos relacionados' );
            return response()->json($error, 200);
        }

        $venta = Venta::find($id);

        $venta->cliente_id = $request->cliente_id;
        $venta->num_pedido = $request->num_pedido;
        $venta->fecha = $request->fecha;
        $venta->valor = $request->valor;

        $venta->save();

        // modificar el guardado

        $entrada = Fechaentrada::where('venta_id', $id)->delete();

        for ($i=0; $i < count($request->entradas); $i++) {            
            $entrada = new Fechaentrada();
            $entrada->detalleproducto_id = $request->entradas[$i]['id'];
            $entrada->venta_id = $id;
            $entrada->cantidad = $request->entradas[$i]['cantidad'];
            $entrada->adicionales = $request->entradas[$i]['adicional'];
            $entrada->precio_entrada = $request->entradas[$i]['precio'];

            $entrada->save();

            if($request->cliente_id == 3){

                $detalleproducto = Detalleproducto::find($request->entradas[$i]['id']);
                $detalleproducto->stock =  $request->entradas[$i]['cantidad'] + $request->entradas[$i]['adicional'] + $request->entradas[$i]['stock'];
                $detalleproducto->precio = $request->entradas[$i]['precio'];
                
                $detalleproducto->save();
            }

        }

        return 'ok';
    }
}
