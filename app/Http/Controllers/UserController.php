<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\User;

class UserController extends Controller
{
    public function __construct(){
        return $this->middleware('auth');
    }

    public function index(Request $request){
        if($request->ajax()){
            $users = DB::table('users')
            ->leftJoin('clientes', 'users.id', '=', 'clientes.user_id')
            ->select('users.id', 'users.name', 'users.email', 'users.role_id', 'clientes.id AS cliente_id', 'clientes.user_id', 'clientes.municipio_id', 'clientes.tipocliente_id', 'clientes.nit', 'clientes.dv', 'clientes.razon_social', 'clientes.direccion', 'clientes.telefono')
            ->get();
            // $users = User::all();
            return $users;
        }else{
            return view('admin.usuarios.index');
        }
    }

    public function getUser(){
        $user = User::find(Auth::user()->id);
        return $user;
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {    
            return response()->json($validator->messages(), 200);
        }

        $user = new User();
        $user->role_id = $request->role_id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return 'ok';
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'name' => 'required',
            'email' => 'required'
        ]);

        if ($validator->fails()) {    
            return response()->json($validator->messages(), 200);
        }

        $user = User::find($id);
        $user->role_id = $request->role_id;
        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return 'ok';
    }
}
