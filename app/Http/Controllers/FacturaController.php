<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Combo;
use App\Detalleproducto;
use App\Factura;
use App\Facturaproducto;
use App\Transferencia;
use App\Productoscombo;

class FacturaController extends Controller
{
    public function __construct(){
        return $this->middleware('auth');
    }

    public function index(){
        return view('facturas.realizar-facturas.index');
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required',
            'formapago_id' => 'required',
            'mediopago_id' => 'required',
            'numero_factura' => 'required|unique:facturas',
            'fecha_factura' => 'required',
            'fecha_vencimiento' => 'required',
            'hora_factura' => 'required',
            'valor' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }

        $factura = new Factura();
        $factura->cliente_id = $request->cliente_id;
        $factura->numero_transferencia = $request->numero;
        $factura->estado_id = 4;
        $factura->formapago_id = $request->formapago_id;
        $factura->mediopago_id = $request->mediopago_id;
        $factura->numero_factura = $request->numero_factura;
        $factura->fecha_factura = $request->fecha_factura;
        $factura->fecha_vencimiento = $request->fecha_vencimiento;
        $factura->hora_factura = $request->hora_factura;
        $factura->calidad_retenedor = 0;
        $factura->iva = 0;
        $factura->impuesto_consumo = 0;
        $factura->valor = $request->total;

        $factura->save();

        for ($i=0; $i < count($request->pedidos); $i++) { 

            $productos = new Facturaproducto();
            $productos->factura_id = $factura->id;
            $productos->detalleproducto_id = $request->pedidos[$i]['id'];
            $productos->cantidad = $request->pedidos[$i]['cantidad'];
            $productos->precio_factura = $request->pedidos[$i]['precio'];

            $productos->save();

            // Comprueba si el producto a ingresar tiene inventario
            if(($request->pedidos[$i]['cantidad'] - $request->pedidos[$i]['entregar'])  > $request->pedidos[$i]['stock']){

                // Compruebo si el producto que se esta despachando es un combo. 
                $combo = Combo::with(['detalleproductos' ,'productos'])->where('detalleproducto_id', $request->pedidos[$i]['id'])->get();  

                // Si es un combo busco los productos que componen ese combo y les modifico el stock
                if(count($combo) > 0){
                    for ($j=0; $j < count($combo); $j++) { 
                        $productocombo = Detalleproducto::find($combo[$j]['detalleproductos']['id']);
                        $productocombo->stock = $combo[$j]['detalleproductos']['stock'] - $request->pedidos[$i]['cantidad'] + $request->pedidos[$i]['entregar'];
                        $productocombo->save();
                    }   
                }else{
                    // Busco el producto individual a ver a que combo pertenece para hacerle la resta al stock.
                    $productocombo = Productoscombo::with(['combos' ,'detalleproductos'])->where('detalleproducto_id', $request->pedidos[$i]['id'])->get();

                    for ($j=0; $j < count($productocombo); $j++) { 

                        $producto = Detalleproducto::find($productocombo[$j]['combos']['detalleproducto_id']);
                        $producto->stock = $productocombo[$j]['detalleproductos']['stock'] - $request->pedidos[$i]['cantidad'] + $request->pedidos[$i]['entregar'];
                        $producto->save();

                    }
                }
            }else{
                // Actualiza el stock de cada producto facturado
                $detalleproducto = Detalleproducto::find($request->pedidos[$i]['id']);
                $detalleproducto->stock = $request->pedidos[$i]['stock'] - $request->pedidos[$i]['cantidad'] + $request->pedidos[$i]['entregar'];

                $detalleproducto->save();
            }
             
        }

        // Actualiza el estado de la transferencia a facturado.
        $transferencia = Transferencia::where('numero', $request->numero)
            ->update(['estado_id' => 2]);

        return 'ok';
    }
}
