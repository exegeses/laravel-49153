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

############################################
####### CRUD destinos
Route::get('/adminDestinos', function(){
    //obtener listado de destinos
    /*
    $destinos = DB::select(
                    'SELECT destID, destNombre,
                            regiones.regID, regiones.regNombre,
                            destPrecio, destAsientos, destDisponibles, destActivo
                       FROM destinos
                       INNER JOIN regiones on destinos.regID = regiones.regID'
                );
    */
    $destinos = DB::table('destinos')
                ->join('regiones', 'destinos.regID','=','regiones.regID')
                ->select('destinos.destID', 'destinos.destNombre', 'regiones.regNombre','destinos.destPrecio')
                ->get();
    //retornar vista
    return view('adminDestinos', [ 'destinos'=>$destinos ] );
});
Route::get('/agregarDestino', function (){
    //obtenemos listado de regiones
    $regiones = DB::table('regiones')->get();
    //retornamos vista del form
    return view('agregarDestino', [ 'regiones'=>$regiones ]);
});
Route::post('/agregarDestino', function(){
    //capturamos datos enviados por el form
    $destNombre = $_POST['destNombre'];
    $regID = $_POST['regID'];
    $destPrecio = $_POST['destPrecio'];
    $destAsientos = $_POST['destAsientos'];
    $destDisponibles = $_POST['destDisponibles'];
    //insertamos datos en tabla
    DB::table('destinos')
                ->insert(
                        [
                            'destNombre'=>$destNombre,
                            'regID'=>$regID,
                            'destPrecio'=>$destPrecio,
                            'destAsientos'=>$destAsientos,
                            'destDisponibles'=>$destDisponibles
                        ]
                        );
    //redirigimos con mensaje de ok
    return redirect('/adminDestinos')
            ->with(['mensaje' => 'Destino ' . $destNombre . ' agregado correctamente']);
});
Route::get('/modificarDestino/{id}', function($id){
    //obtenemos datos de un destino por su id
    $destino = DB::table('destinos')
                    ->where('destID', $id)
                    ->first();
    //obtenemos datos de regiones
    $regiones = DB::table('regiones')->get();
    //retornamos vista con datos
    return view('modificarDestino',
                [
                    'destino' => $destino,
                    'regiones' => $regiones
                ]
            );
});
Route::post('/modificarDestino', function()
{
    //capturamos datos enviados por el form
    $destNombre = $_POST['destNombre'];
    $regID = $_POST['regID'];
    $destPrecio = $_POST['destPrecio'];
    $destAsientos = $_POST['destAsientos'];
    $destDisponibles = $_POST['destDisponibles'];
    $destID = $_POST['destID'];
    //modificamos
    DB::table('destinos')
        ->where('destID', $destID)
        ->update(
            [
                'destNombre'=>$destNombre,
                'destPrecio'=>$destPrecio,
                'destAsientos'=>$destAsientos,
                'destDisponibles'=>$destDisponibles,
                'regID'=>$regID
            ]
        );
    //redirección a admin + mensaje ok
    return redirect('/adminDestinos')
        ->with(['mensaje' => 'Destino ' . $destNombre . ' modificado correctamente']);
});
Route::get('/eliminarDestino/{id}', function($id)
{
    //obtenemos datos de un destino por su id
    $destino = DB::table('destinos as d')
                ->join('regiones as r', 'd.regID', '=', 'r.regID')
                ->where('destID', $id)
                ->first();
    //retornamos vista de confirmación
    return view('eliminarDestino', [ 'destino'=>$destino ] );
});
Route::post('/eliminarDestino', function ()
{
    //capturamos datos enviados por el from
    $destNombre = $_POST['destNombre'];
    $destID = $_POST['destID'];
    //eliminamos el destino
    DB::table('destinos')
        ->where('destID', $destID)
        ->delete();
    //redirección a admin + mensaje ok
    return redirect('/adminDestinos')
        ->with(['mensaje' => 'Destino ' . $destNombre . ' eliminado correctamente']);
});
