@extends( 'layouts.plantilla' )

    @section('contenido')
        <h1>Estrucuras de control</h1>
        <h2>Condicional</h2>

        @if( $nombre == 'Marcos' )
            bienvenido
        @else
            usuario invitado
        @endif



    @endsection
