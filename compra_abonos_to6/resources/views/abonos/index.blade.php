@extends("layouts.default")


<!-- <head> -->
@section('title', 'UD Almería B - Abonos')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <style>
        .hero::before{
            content: "";
            background: url('{{ asset('images/hero-1.jpg') }}');
            background-size: cover;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.2; /* Ajusta la opacidad aquí (0.0 - 1.0) */
            z-index: -1; /* Asegura que el fondo esté detrás del texto */
        }
    </style>
@endsection


<!-- <body> -->

        @section('header')
        <!-- solo en esta vista tiene css distinto -->
        <nav id="mainNav"
            class="position-fixed top-0 start-0 w-100">
            <div class="container-fluid px-4 py-2 d-flex justify-content-between align-items-center">
                <a href="{{ route('abonos.index') }}">
                    <img src="{{ asset('images/logo.png') }}" class="logo img-fluid" alt="logo">
                </a> 
                <a href="{{ route('usuarios.login') }}" class="btn btn-outline-light">Login administrador</a>
            </div>
        </nav>   
        @endsection


        @section('contenido')
        <!-- HERO -->
        <section class="hero text-center d-flex justify-content-center flex-column vh-100">
            <div>
                <h1 class="display-4 fw-bold">Compra tu Abono Online</h1>
                <p class="lead my-4">
                    Reserva tu asiento en segundos. Seguro, rápido y sin registro.
                </p>
            </div>

            <div class="d-flex flex-column flex-sm-row justify-content-center gap-2 my-5">
                <a href="{{ route('abonos.compra') }}" class="btn btn-primary btn-lg px-4 me-md-2">Comprar ahora</a>
                <a href="#moreInfo" class="btn btn-outline-light btn-lg px-4 me-md-2">Más información</a>            
            </div>

            <nav class="navHero navbar rounded-4 mt-auto mb-5 mx-5 p-5 d-flex justify-content-center flex-wrap">
                <div class="container-fluid">
                    <span class="navbar-text">
                        <i class="bi bi-house"></i>
                        Todos los partidos de Liga y Copa en casa
                    </span>
                    <span class="navbar-text">
                        <i class="bi bi-piggy-bank"></i>
                        Ahorra un 30% respecto a entradas individuales
                    </span>
                    <span class="navbar-text">
                        <i class="bi bi-calendar4-event"></i>
                        Preventa para competiciones europeas y eventos del club
                    </span>
                </div>
            </nav>
        </section>

        
        <main class="container">
            <div id="moreInfo"></div>
            <!-- TIPOS DE ABONO -->
             <!-- **MOD añadir componente -->
            <section class="container-lg text-center d-flex justify-content-center flex-column py-5">
                <h1 class="fs-1 lh-lg mb-4 fw-bold">Nuestros tipos de abonos</h1>

                <div class="cardTipos card-group">
                    <div class="card rounded-4">
                        <i class="bi bi-award-fill tribuna"></i>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fs-1 fw-bold">500€</h5>
                            <h5 class="card-title fs-3 fw-bold">Tribuna</h5>
                            <p class="card-text">
                                Disfruta de la mejor experiencia en el estadio desde la tribuna principal. 
                                Asientos cómodos, con excelente visión del campo y acceso privilegiado a servicios exclusivos. 
                                Ideal para quienes quieren vivir cada partido con la máxima emoción y comodidad.
                            </p>
                            <a href="{{ route('abonos.compra') }}" class="btn btn-primary btn-lg px-4 mt-auto">Comprar</a>
                        </div>
                    </div>
                    <div class="card rounded-4">
                        <i class="bi bi-award-fill preferencia"></i>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fs-1 fw-bold">300€</h5>
                            <h5 class="card-title fs-3 fw-bold">Preferencia</h5>
                            <p class="card-text">
                                Ubicación estratégica con buena visibilidad del juego y fácil acceso a las zonas de restauración. 
                                Perfecto para quienes buscan disfrutar del fútbol con comodidad sin estar en la tribuna principal.
                            </p>
                            <a href="{{ route('abonos.compra') }}" class="btn btn-primary btn-lg px-4 mt-auto">Comprar</a>
                        </div>
                    </div>
                    <div class="card rounded-4">
                        <i class="bi bi-award-fill fondo"></i>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fs-1 fw-bold">110€</h5>
                            <h5 class="card-title fs-3 fw-bold">Fondo</h5>
                            <p class="card-text">
                                Vive la pasión del fútbol junto a la hinchada más animada. 
                                Asientos económicos en la zona de fondo, donde la energía de la afición crea un ambiente inolvidable. 
                                Ideal para los seguidores más fervientes.
                            </p>
                            <a href="{{ route('abonos.compra') }}" class="btn btn-primary btn-lg px-4 mt-auto">Comprar</a>
                        </div>
                    </div>
                </div>
            </section>

            <div id="discounts"></div>
            <section class="container-lg text-center py-5">
                <h1 class="fs-1 lh-lg mb-4 fw-bold">Precios especiales</h1>
                
                <div class="row justify-content-center g-4 cardDescuentos">
                    <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                        <div class="card h-100 w-100">
                            <div class="card-body">
                                <h5 class="card-title">Jubilados</h5>
                                <p class="card-text">
                                    Descuento especial para mayores de 65 años. 
                                    Disfruta del fútbol con un precio reducido.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                        <div class="card h-100 w-100">
                            <div class="card-body">
                                <h5 class="card-title">Menores de 12 años</h5>
                                <p class="card-text">
                                    Tarifa especial para los más pequeños de la casa.
                                    Vive el fútbol en familia.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <a href="{{ route('abonos.compra') }}" class="btn btn-secondary btn-lg px-4">Benefíciate</a>
                </div>
            </section>
        </main>
        @endsection


        @section('footer')
        <x-layouts.footer/>
        @endsection




    <!-- ***MOD ver como implementar fuera de la vista -->
    <!-- para animar fondo del nav al bajar del hero -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const nav = document.getElementById("mainNav");
            const hero = document.querySelector(".hero");

            window.addEventListener("scroll", () => {
                const heroBottom = hero.offsetTop + hero.offsetHeight;

                if (window.scrollY >= heroBottom - 80) {
                    nav.classList.add("nav-scrolled");
                } else {
                    nav.classList.remove("nav-scrolled");
                }
            });

        });


    </script>