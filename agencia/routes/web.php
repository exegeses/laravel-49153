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
Route::get('/modificarRegion/{id}', function ($id){
    //obtenemos region por su id
    /*
    $region = DB::select("SELECT regID, regNombre
                                FROM regiones
                                WHERE regID = :id",
                        [ $id ]
                );
    */
    $region = DB::table('regiones')
                    ->where('regID',  $id)
                    ->first(); //fetch()
    return view('modificarRegion', [ 'region'=>$region ] );
});
Route::post('/modificarRegion', function (){
    //capturamos datos enviados
    $regNombre = $_POST['regNombre'];
    $regID = $_POST['regID'];
    //modificamos
    /*
    DB::update( "UPDATE regiones
                    SET regNombre = :regNombre
                    WHERE regID = :regID",
                [ $regNombre, $regID ]
            );
    */
    DB::table('regiones')
            ->where('regID', $regID)
            ->update([ 'regNombre'=>$regNombre ]);
    //redirección con mensaje
    return redirect('/adminRegiones')
                ->with('mensaje', 'Región: '.$regNombre.' modificada correctamente');
});
