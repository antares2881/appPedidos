<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Municipio;

class MunicipioController extends Controller
{
    public function index(){
        $municipios = Municipio::all();
        return $municipios;
    }
}
