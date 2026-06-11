@extends("layouts.default")

<!-- <head> -->
@section('title', 'Inicio de sesión')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/formulario.css') }}">
@endsection


<!-- <body> -->


        @section('header')
        <nav id="mainNav" class=" mainNav-solid top-0 start-0 w-100">
            <div class="container-fluid px-4 py-2 d-flex justify-content-between align-items-center">
                <a href="{{ route('abonos.index') }}"> <!-- ***MOD RUTA landing -->
                    <img src="{{ asset('images/logo.png') }}" class="logo img-fluid" alt="logo">
                </a> 
                <a href="{{ route('usuarios.login') }}" class="btn btn-outline-light">Login administrador</a>
            </div>
        </nav>   
        @endsection
        

        @section('contenido')        
        <main class="container d-flex justify-content-center text-center flex-column pb-5">

            <!-- FORMULARIO -->
            <div class="row justify-content-center text-center pt-4">
                <div class="wrapper-admin col-12 col-md-8 col-lg-6 col-xl-4 p-5">
                    <h1 class="fs-1 lh-lg mb-4 fw-bold">Inicio de sesión</h1>

                    
                    <form action="{{ route('usuarios.authenticate') }}" method="post" class="row g-3 gap-2 text-start">
                        @csrf
                        <div class="col-md-12">
                            <label for="username" class="form-label">Nombre de usuario</label>
                            <input value = "{{ old('username') }}" type="text" name="username" class="form-control" id="username">

                            @error('username')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="password" class="form-label">Contraseña</label>
                            <input  value = "{{ old('password') }}" name="password" type="password" class="form-control" id="password">
                            @error('password')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-12 d-flex justify-content-center">
                            <button type="submit" name="login" value="ok" class="btn btn-primary">Acceder</button>
                        </div>
                    </form>
                    <div class="mt-4">
                        <a href="{{ route('usuarios.forgot') }}">¿Olvidaste tu contraseña?</a>
                    </div>
                </div>
            </div>    
                        
        </main>
        @endsection





        @section('footer')
        <x-layouts.footer/>
        @endsection
