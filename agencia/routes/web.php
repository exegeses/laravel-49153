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

############################################
####### CRUD regiones
Route::get('/adminRegiones', function ()
{
    //obtenemos listado de regiones
    $regiones = DB::select('SELECT regID, regNombre FROM regiones');
    return view('adminRegiones',
                    [ 'regiones'=>$regiones ]
            );
});
Route::get('/agregarRegion', function ()
{
    return view('agregarRegion');
});
Route::post('/agregarRegion', function ()
{
    //capturamos datos enviados por el form
    $regNombre = $_POST['regNombre'];
    //insertamos datos en tabla regiones
    DB::insert("INSERT INTO regiones
                        ( regNombre )
                    VALUE
                        ( :regNombre )",
                [ $regNombre ] );
    //redireccion con mensaje de ok
    return redirect('/adminRegiones')
                ->with([ 'mensaje'=>'Región '.$regNombre.' agregada corectamente' ]);
});
