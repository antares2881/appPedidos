<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Detalleproducto;

class DetalleproductoController extends Controller
{
    public function __construct(){
        return $this->middleware('auth');
    }

    public function index(Request $request){
        if($request->ajax()){
            $detalleproductos = Detalleproducto::with(['categorias', 'productos', 'presentaciones'])->get();
            return $detalleproductos;
        }
    }
}
