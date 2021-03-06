@extends('layouts.plantilla')

    @section('contenido')

        <h1>Baja de una categoría</h1>

        <div class="alert alert-danger col-6 mx-auto p-4">

            Se eliminará la categoría:
            <span class="lead">
                {{ $Categoria->catNombre }}
            </span>
            <form action="/eliminarCategoria" method="post">
        @method('delete')
        @csrf
                <input type="hidden" name="catNombre"
                       value="{{ $Categoria->catNombre }}">
                <input type="hidden" name="idMarca"
                       value="{{ $Categoria->idMarca }}">
                <button class="btn btn-danger btn-block my-3">
                    Confirmar baja
                </button>
                <a href="/adminCategorias" class="btn btn-light btn-block">
                    volver a panel
                </a>
            </form>
        </div>

        <script>
            Swal.fire(
                'Advertencia',
                'Si pulsa el botón "Confirmar baja", se eliminará la categoría seleccionada.',
                'warning'
            )
        </script>

    @endsection
