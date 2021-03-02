<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Presentacione;

class PresentacioneController extends Controller
{
    public function index(){
        $presentaciones = Presentacione::all();
        return $presentaciones;
    }
}
