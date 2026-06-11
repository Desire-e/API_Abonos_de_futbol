<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- CSS bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    
    <!-- mi CSS -->
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    @yield('css') <!-- css concreto de cada vista -->

    <!-- asset() buscará automáticamente dentro de /public/css -->
    <!-- 
    NOTA: Siempre que quieras agregar JS o imágenes también, se recomienda crear carpetas como 
    public/js o public/images. 
    Laravel espera que todo lo accesible públicamente esté en public. 
    -->
</head>

<body>

    <div class="container-fluid g-0">
        @yield('header') <!-- algunas vistas no tienen header -->

        @yield('contenido')

        @yield('footer') <!-- algunas vistas no tienen footer -->
    </div>

    <!-- JS bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
