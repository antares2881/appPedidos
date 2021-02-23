<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;

class ProductoController extends Controller
{
    public function __construct(){
        return $this->middleware('auth');
    }

    public function index(Request $request){
        $productos = Producto::with(['categorias', 'composiciones'])->get();
        if($request->ajax()){
            return $productos;
        }
    }
}
