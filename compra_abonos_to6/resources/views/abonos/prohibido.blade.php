@extends("layouts.default")

<!-- <head> -->
@section('title', 'Contenido protegido')

@section('css')
    <link href="{{ asset('css/prohibido.css') }}" rel="stylesheet">
@endsection


<!-- <body> -->
        @section('header', '')

        @section('contenido')
        <!-- CONTENIDO -->
        <main class="container d-flex justify-content-center text-center flex-column py-0 my-0">

            <h1 class="fs-1 fw-bold lh-lg">Alerta de acceso</h1>
            <h3 class="">Contenido protegido</h3>

            <div class="row justify-content-center text-start pt-5">
                          
                <!-- ALERTA DIV -->
                 <div class=" alertaContent p-5 d-flex align-items-center text-center flex-column w-50 gap-5">
                    <i class="bi bi-exclamation-triangle-fill alerta"></i>
                    <p>
                        Lo sentimos, no tiene acceso a este contenido. Solo permitido para cuentas autorizadas.
                        Inicie sesión como administrador.
                    </p>
                    <a href="{{ route('abonos.index') }}" class="btn btn-primary btn ">Volver</a>
                </div>
            </div>    
                        
        </main>
        @endsection

        @section('footer', '')
