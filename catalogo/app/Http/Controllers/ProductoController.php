<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //obtenemos listado de productos
        $productos = Producto::with('relMarca', 'relCategoria')
                                ->paginate(6);
        //retornamos vista con datos
        return view('adminProductos',
                    [ 'productos' => $productos ]
                );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $marcas = Marca::all();
        $categorias = Categoria::all();
        return view('agregarProducto',
            [
                'marcas' => $marcas,
                'categorias' => $categorias
            ]
        );
    }

    private function validarForm(Request $request)
    {
        $request->validate(
                [
                    'prdNombre'=>'required|min:3|max:70',
                    'prdPrecio'=>'required|numeric|min:0',
                    'prdPresentacion'=>'required|min:3|max:150',
                    'prdStock'=>'required|integer|min:1',
                    'prdImagen'=>'mimes:jpg,jpeg,png,gif,svg,webp|max:2048'
                ],
                [
                    'prdNombre.required'=>'Complete el campo Nombre',
                    'prdNombre.min'=>'Complete el campo Nombre con al menos 3 caractéres',
                    'prdNombre.max'=>'Complete el campo Nombre con 70 caractéres como máxino',
                    'prdPrecio.required'=>'Complete el campo Precio',
                    'prdPrecio.numeric'=>'Complete el campo Precio con un número',
                    'prdPrecio.min'=>'Complete el campo Precio con un número positivo',
                    'prdPresentacion.required'=>'Complete el campo Presentación',
                    'prdPresentacion.min'=>'Complete el campo Presentación con al menos 3 caractéres',
                    'prdPresentacion.max'=>'Complete el campo Presentación con 150 caractérescomo máxino',
                    'prdStock.required'=>'Complete el campo Stock',
                    'prdStock.integer'=>'Complete el campo Stock con un número entero',
                    'prdStock.min'=>'Complete el campo Stock con un número positivo',
                    'prdImagen.mimes'=>'Debe ser una imagen',
                    'prdImagen.max'=>'Debe ser una imagen de 2MB como máximo'
                ]
        );
    }

    private function subirImagen(Request $request)
    {
        //si no enviaron archivo en método store()
        $prdImagen = 'noDisponible.jpg';
        //si no enviaron archivo en método update()
        if( $request->has('imgActual') ){
            $prdImagen = $request->imgActual;
        }
        //si enviaron imagen SUBIR ARCHIVO
        if( $request->file('prdImagen') ){
            //renombrar time().extension de archivo
            $ext = $request->file('prdImagen')->extension();
            $prdImagen = time().'.'.$ext;
            //subir archivo al directorio productos en public
            $request->file('prdImagen')
                    ->move( public_path('productos/'), $prdImagen );
        }
        return $prdImagen;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validación
        $this->validarForm($request);
        //subir imagen (si fue enviada)
        $prdImagen = $this->subirImagen($request);
        //instanciacion, asignacion, guardado
        $Producto = new Producto;
        $Producto->prdNombre = $prdNombre = $request->prdNombre;
        $Producto->prdPrecio = $request->prdPrecio;
        $Producto->idMarca = $request->idMarca;
        $Producto->idCategoria = $request->idCategoria;
        $Producto->prdPresentacion = $request->prdPresentacion;
        $Producto->prdStock = $request->prdStock;
        $Producto->prdImagen = $prdImagen;
        $Producto->save();
        //redirección con mensaje ok
        return redirect('/adminProductos')
                    ->with([ 'mensaje' => 'Producto: '.$prdNombre. ' agregado correctamente' ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {
        $marcas = Marca::all();
        $categorias = Categoria::all();
        $Producto = Producto::with('relMarca', 'relCategoria')
                                ->find($id);
        return view('modificarProducto',
                [
                    'marcas'=>$marcas,
                    'categorias'=>$categorias,
                    'Producto'=>$Producto
                ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $prdNombre = $request->prdNombre;
        //validar
        $this->validarForm($request);
        //subir imagen
        $prdImagen = $this->subirImagen($request);
        //obtener objeto, asignar atributos, guardar
        $Producto = Producto::find($request->idProducto);
        $Producto->prdNombre = $prdNombre;
        $Producto->prdPrecio = $request->prdPrecio;
        $Producto->idMarca = $request->idMarca;
        $Producto->idCategoria = $request->idCategoria;
        $Producto->prdPresentacion = $request->prdPresentacion;
        $Producto->prdStock = $request->prdStock;
        $Producto->prdImagen = $prdImagen;
        $Producto->save();
        //redirección con mensaje ok
        return redirect('/adminProductos')
            ->with([ 'mensaje' => 'Producto: '.$prdNombre. ' modificado correctamente' ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
