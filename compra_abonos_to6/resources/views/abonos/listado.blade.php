@extends("layouts.default")


<!-- <head> -->
@section('title', 'Listado de abonos')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/listado.css') }}">
@endsection


        @section('header', '')

        @section('contenido')
        <main class="container d-flex justify-content-center text-center flex-column pb-5">

            <h1 class="fs-1 fw-bold lh-lg">Panel Admin</h1>
            <h3 class="lh-lg">
                @if(empty($abonos))
                    No se encontraron abonos registrados 
                @endif

                Listado de abonos comprados
            </h3>

            <!-- CAMPOS DEL REGISTRO -->
            
            <!-- INFO -->
            <div class="row justify-content-center text-start py-5">
                <table class="table border">

                    <thead>
                        <tr>
                            <th scope="col">Tipo de abono</th>
                            <th scope="col">Código de asiento</th>
                            <th scope="col">Datos del abonado</th>
                            <th scope="col">Datos del abonado especial</th>
                            <th scope="col">Importe</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($abonos as $abono)
                        <tr>
                            <!-- MEDALLA -->
                            <td scope="row">
                                @if ($abono->tipoAbono->descripcion === 'Tribuna')
                                <i class="bi bi-award-fill tribuna"></i>
                                @elseif($abono->tipoAbono->descripcion === 'Preferencia')
                                <i class="bi bi-award-fill preferencia"></i>
                                @elseif($abono->tipoAbono->descripcion === 'Fondo')
                                <i class="bi bi-award-fill fondo"></i>
                                @endif                            
                            </td>
                            
                            <!-- ASIENTO -->
                            <td>{{ $abono->asiento }}</td>

                            <!-- INFO DEL ABONADO -->
                            <td>
                                <div class="d-flex gap-5">
                                    <!-- <p>{{ $abono->abonado }}</p> -->
                                    {{ $abono->abonado }}
                                    <i class="bi bi-telephone-fill" title="{{ $abono->telefono }}"></i>
                                    <i class="bi bi-bank2" title="{{ $abono->cuenta_bancaria }}"></i>
                                </div>
                            </td>

                            <!-- EDAD DESCUENTO -->
                            <td>
                                @if($abono->edad < 12) Menor de 12 años
                                @elseif($abono->edad > 65) Jubilado
                                @else Sin abono especial 
                                @endif
                            </td>
                            
                            <!-- PRECIO TOTAL -->
                            <td>{{ $abono->precio }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                          
                <div class="d-flex justify-content-center">
                    <a href="{{ route('usuarios.logout') }}" class="btn btn-primary btn-lg mt-4">Cerrar sesión</a>
                </div>
            </div>    
                        
        </main>
        @endsection

        @section('footer', '')