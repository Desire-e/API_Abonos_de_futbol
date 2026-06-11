@extends("layouts.default")


<!-- <head> -->
@section('title', 'Ticket de compra')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
@endsection


<!-- <body> -->


        @section('header')
        <nav id="mainNav" class=" mainNav-solid top-0 start-0 w-100">
            <div class="container-fluid px-4 py-2 d-flex justify-content-between align-items-center">
                <a href="{{ route('abonos.index') }}">
                    <img src="{{ asset('images/logo.png') }}" class="logo img-fluid" alt="logo">
                </a> 
                <a href="{{ route('usuarios.login') }}" class="btn btn-outline-light">Login administrador</a>
            </div>
        </nav>   
        @endsection


        @section('contenido')        
        <main class="container d-flex justify-content-center text-center flex-column pb-5">

            <h6 class="fw-light">Compra realizada con éxito</h6>
            <h1 class="fs-1 fw-bold lh-lg">Detalles del ticket</h1>
            <hr/>
            <!-- INFO -->
            <div class="row justify-content-center text-start">
                <div class="wrapper-ticket col-12 col-md-10 col-lg-8 col-xl-6 p-5">
                    <ul class="list-group list-group-flush bg-transparent">
                        <li class="list-group-item bg-transparent text-light">
                            <p class="fw-bold">Fecha de compra</p> 
                            <p class="">{{ $fecha }}</p> 
                        </li>
                        <li class="list-group-item bg-transparent text-light">
                            <p class="fw-bold">Nombre</p> 
                            <p class="">{{ $nombre }}</p> 
                        </li>
                        <li class="list-group-item bg-transparent text-light">
                            <p class="fw-bold">DNI</p> 
                            <p class="">{{ $dni }}</p> 
                        </li>
                        <li class="list-group-item bg-transparent text-light">
                            <p class="fw-bold">Teléfono</p> 
                            <p class="">{{ $telefono }}</p> 
                        </li>
                        <li class="list-group-item bg-transparent text-light">
                            <p class="fw-bold">Tipo de abono</p> 
                            <p class="">{{ $tipoAbono }}</p> 
                        </li>
                        <li class="list-group-item bg-transparent text-light"> 
                            <p class="fw-bold">Código de asiento</p> 
                            <p class="">{{ $asiento }}</p> 
                        </li>

                        <li class="list-group-item bg-transparent text-light mt-4">
                            <p class="fs-4 fw-bold">Importe {{ $precio }}€</p> 
                            <p>
                                @if ($edad < 12) * Tarifa especial Niños/as menores de 12 años: Rebaja de 80€.
                                @elseif ($edad > 65) * Tarifa especial Jubilados y mayores de 65 años: Rebaja del 50%.
                                @endif
                            </p>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-center">
                        <a href="{{ route('abonos.compra') }}" class="btn btn-primary btn-lg mt-4">Volver</a>
                    </div>

                    <div class="d-flex justify-content-center">
                        <a href="{{ route('abonos.downloadTicket', $id) }}" class="btn btn-primary mt-4">Descargar ticket</a>
                    </div>

                </div>
            </div>    
                        
        </main>
        @endsection



        @section('footer')
        <x-layouts.footer/>
        @endsection
