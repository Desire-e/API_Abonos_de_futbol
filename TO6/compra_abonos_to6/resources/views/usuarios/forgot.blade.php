@extends("layouts.default")

<!-- <head> -->
@section('title', 'Olvidaste tu contraseña')

@section('css', '')


<!-- <body> -->

        @section('header', '')        

        @section('contenido')        
        <main class="container d-flex justify-content-center text-center flex-column py-0 my-0">
            <div class="row justify-content-center text-center pt-5">
                <h1 class="fs-1 fw-bold lh-lg">Pues haz memoria...</h1>
                <a href="{{ route('usuarios.login') }}" class="btn btn-primary btn w-25">Volver</a>
            </div>             
        </main>
        @endsection

        @section('footer', '')
