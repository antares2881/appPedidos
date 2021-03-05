<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Venta;

class VentaController extends Controller
{
    public function __construct(){
        return $this->middleware('auth');
    }

    public function index(Request $request){

        $ventas = Venta::with(['clientes', 'cobros', 'entradas', 'municipios'])->get();

        if($request->ajax()){
            return $ventas;
        }else{
            return view('historial.pedidos.index', compact('ventas'));
        }
    }
}
