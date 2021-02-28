<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Categorias
Route::get('/categorias', 'CategoriaController@index');

// Composiciones
Route::get('/composiciones', 'ComposicioneController@index');

// Clientes
Route::get('/clientes', 'ClienteController@index');
Route::put('/clientes/{id}', 'ClienteController@update');
Route::get('/getCliente', 'ClienteController@getCliente');
Route::post('/clientes', 'ClienteController@store');

// Detalle productos
Route::get('/detalleproductos', 'DetalleproductoController@index');
Route::get('/productos', 'ProductoController@index');

// Entradas
Route::get('/entradas', 'InventarioController@entradas');
Route::get('/stocks', 'InventarioController@stocks');
Route::post('/nueva-entrada', 'InventarioController@store');

// Municipios
Route::get('/municipios', 'MunicipioController@index');

// Productos
Route::get('/productos', 'ProductoController@index');
Route::post('/productos', 'ProductoController@store');
Route::put('/productos/{id}', 'ProductoController@update');

// Producto transferencias
Route::get('/producto-transferencias/{id}', 'ProductotransferenciaController@show');

// Transferencias
Route::get('/transferencias', 'TransferenciaController@index');
Route::get('/numero-transferencia/{id}', 'TransferenciaController@findNumberTransferencia');
Route::post('/transferencias', 'TransferenciaController@store');
Route::put('/transferencias/{id}', 'TransferenciaController@update');
Route::put('/estado-transferencias/{id}', 'TransferenciaController@updateState');

// Users
Route::get('/usuarios', 'UserController@index');
Route::put('/usuarios/{id}', 'UserController@update');
Route::get('/getUser', 'UserController@getUser');
Route::post('/usuarios', 'UserController@store');
