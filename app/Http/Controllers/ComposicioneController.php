<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Composicione;

class ComposicioneController extends Controller
{
    public function index(){
        $composiciones = Composicione::all();
        return $composiciones;
    }
}
