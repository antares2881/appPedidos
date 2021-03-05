<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Detalleproducto;

class DetalleproductoController extends Controller
{
    public function __construct(){
        return $this->middleware('auth');
    }

    public function index(Request $request){
        if($request->ajax()){
            $detalleproductos = Detalleproducto::with(['categorias', 'productos', 'presentaciones'])->orderBy('stock', 'desc')->get();
            return $detalleproductos;
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'producto_id' => 'required',
            'presentacione_id' => 'required',
            'codigo' => 'required|unique:detalleproductos',
            'precio' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 200);
        }

        $producto = new Detalleproducto();
        $producto->producto_id = $request->producto_id;
        $producto->presentacione_id = $request->presentacione_id;
        $producto->codigo = $request->codigo;
        $producto->precio = $request->precio;

        $producto->save();

        return 'ok';
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'producto_id' => 'required',
            'presentacione_id' => 'required',
            'codigo' => 'required',
            'precio' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 200);
        }

        $producto = Detalleproducto::find($id);
        $producto->producto_id = $request->producto_id;
        $producto->presentacione_id = $request->presentacione_id;
        $producto->codigo = $request->codigo;
        $producto->precio = $request->precio;

        $producto->save();

        return 'ok';
    }
}
