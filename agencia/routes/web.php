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
    return view('welcome');
});
//Route::get('peticion', 'acción');
Route::get('/test', function (){
    return 'hola mundo';
} );
Route::get('/hola', function (){
    return view('saludo');
});

Route::get('/inicio', function (){
    return view('inicio');
});
Route::get('/condicional', function (){
    $nombre = 'Marcos';
    return view('/condicional', [ 'nombre'=>$nombre ] );
});
