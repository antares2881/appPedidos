<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Cliente;

class ClienteController extends Controller
{
    public function __construct(){
        return $this->middleware('auth');
    }

    public function index(Request $request){

        if($request->ajax()){
            $clientes = Cliente::with(['municipios'])->get();
            return $clientes;
        }

    }

    public function getCliente(){

        $cliente = Cliente::where(Auth::user()->user_id)->get();
        return $cliente;
        
    }

    public function show($id){
        $cliente = Cliente::with(['departamentos','municipios'])->find($id);
        return $cliente;
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|unique:clientes',
            'municipio_id' => 'required',
            'tipocliente_id' => 'required',
            'nit' => 'required',
            'dv' => 'required',
            'razon_social' => 'required',
            'direccion' => 'required',
            'telefono' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 200);
        }

        $cliente = new Cliente();
        $cliente->user_id = $request->user_id;
        $cliente->municipio_id = $request->municipio_id;
        $cliente->tipocliente_id = $request->tipocliente_id;
        $cliente->nit = $request->nit;
        $cliente->dv = $request->dv;
        $cliente->razon_social = $request->razon_social;
        $cliente->direccion = $request->direccion;
        $cliente->telefono = $request->telefono;

        $cliente->save();

        return 'ok';

    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'municipio_id' => 'required',
            'tipocliente_id' => 'required',
            'nit' => 'required',
            'dv' => 'required',
            'razon_social' => 'required',
            'direccion' => 'required',
            'telefono' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(), 200);
        }

        $cliente = Cliente::find($id);
        $cliente->municipio_id = $request->municipio_id;
        $cliente->tipocliente_id = $request->tipocliente_id;
        $cliente->nit = $request->nit;
        $cliente->dv = $request->dv;
        $cliente->razon_social = $request->razon_social;
        $cliente->direccion = $request->direccion;
        $cliente->telefono = $request->telefono;

        $cliente->save();

        return 'ok';

    }
}
