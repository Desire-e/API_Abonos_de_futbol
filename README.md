# API_Abonos_de_futbol
Tarea práctica del módulo Desarrollo Web Entorno Servidor.
Se trata de un servicio web API REST desarrollada con PHP + Laravel 12, que realiza operaciones para una app/web de compra de abonos junto funcionalidades admin. 
Contiene un cliente en PHP simple a modo de ejemplo para probar su uso.

## Instalación
1.	Clonar o descargar proyecto Laravel 12 /compra_abonos_to6
```
git clone https://github.com/usuario/proyecto.git
cd proyecto
```
3.	Para descargar dependencias (paquetes definidos en composer.json y crear vendor), ejecutar en consola: 
cd compra_abonos_to6
composer install  
4.	Copiar .env.example:
cp .env.example .env
5.	Generar APP_KEY:
php artisan key:generate
6.	Descargar uda_api.sql (estructura y datos principales) y crear la base de datos con este.
7.	Cambiar credenciales de base de datos en archivo de proyecto .env (DB_HOST, DB_PORT, DB_PASSWORD).
8.	Ejecutar migraciones para crear tablas adicionales necesarias para la API: 
php artisan migrate
9.	Ejecutar enlaces simbólicos para servicio de imágenes: 
php artisan storage:link
Puedes probarlo descargando /cliente_rest_to6 (cliente desarrollado en php que hace uso de los endpoints en una web simple para demostrar el funcionamiento) y ejecutándolo en un servidor web (Apache).
