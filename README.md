# API REST para Gestión de Abonos de Fútbol
Tarea práctica del módulo Desarrollo Web Entorno Servidor.
Se trata de una API REST desarrollada con PHP y Laravel 12 para la gestión y compra de abonos de fútbol. Incluye funcionalidades para usuarios y administradores, así como un cliente PHP sencillo para probar los distintos endpoints.

## Requisitos
- PHP 8.2.12 o superior
- Composer
- MySQL
- Laravel 12
- Apache o servidor compatible

## Instalación
1.	Clonar o descargar proyecto
```
git clone https://github.com/Desire-e/API_Abonos_de_futbol.git
```
2.	Descargar dependencias en la API (paquetes definidos en composer.json y crear vendor)
```
cd API_Abonos_de_futbol/TO6/compra_abonos_to6
composer install
```
3.	Copiar `.env.example` (cambiar idiomas si es necesario)
```
cp .env.example .env
```
4.	Generar APP_KEY
```
php artisan key:generate
```
5.	Crear la base de datos haciendo uso de `uda_api.sql` (estructura y datos principales).
6.	Cambiar credenciales de base de datos en archivo de proyecto `.env` (DB_HOST, DB_PORT, DB_PASSWORD).
7.	Ejecutar migraciones para crear tablas adicionales en la base de datos, necesarias para la API
```
php artisan migrate
```
8.	Ejecutar enlaces simbólicos para servicio de imágenes
```
php artisan storage:link
```

## Cómo usarla
Puede probarla con `/cliente_rest_to6` (cliente desarrollado en PHP que hace uso de los endpoints en una web simple para demostrar el funcionamiento) y ejecutándolo en un servidor web (Apache).

Para ejecutarlo, despliegue la carpeta del cliente en un servidor web con soporte para PHP (Apache, Nginx, Laragon, etc.) y acceda a la URL correspondiente según su configuración.
Por ejemplo, si quiere ejecutar este cliente con XAMPP (Apache), mover carpeta la carpeta `/TO6` de la carpeta del repositorio, a la carpeta de su equipo `/xampp/htdocs`.
Estará disponible el cliente de prueba en: http://localhost/TO6/cliente_rest_to6/abonos/compra_abonos.php

Para consultar la documentación completa de la API, incluidos los endpoints y ejemplos de petición y respuesta, revise el archivo `Documentacion API abonos.pdf` incluido en el repositorio.
