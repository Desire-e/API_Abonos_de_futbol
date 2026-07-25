
# API REST para Gestión de Abonos de Fútbol
 
API REST desarrollada con **PHP** y **Laravel 12** para la gestión y compra de abonos de fútbol. 

Tarea práctica del módulo *Desarrollo Web en Entorno Servidor*, orientada a demostrar el diseño de endpoints REST, autenticación y consumo desde un cliente externo. Incluye funcionalidades para usuarios y administradores, así como un cliente PHP sencillo para probar los distintos endpoints.

## Demo
 
Próximamente. Mientras tanto, puedes probar la instalación en entorno local siguiendo los pasos a continuación.

## Instalación para pruebas en entorno local
 
### Requisitos
 
- PHP 8.2.12 o superior
- Composer
- MySQL
- Laravel 12
- Apache o servidor compatible

### Instalación
 
1. Clonar o descargar el proyecto.
```bash
   git clone https://github.com/Desire-e/API_Abonos_de_futbol.git
```
 
2. Instalar las dependencias de la API (paquetes definidos en `composer.json`, crea la carpeta `vendor`).
```bash
   cd API_Abonos_de_futbol/TO6/compra_abonos_to6
   composer install
```
 
3. Copiar `.env.example` a `.env` (ajusta el idioma si es necesario).
```bash
   cp .env.example .env
```
 
4. Generar la `APP_KEY`.
```bash
   php artisan key:generate
```
 
5. Crear la base de datos e importar `uda_api.sql` (contiene la estructura y los datos principales).
6. Configurar las credenciales de la base de datos en el archivo `.env` (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
7. Ejecutar las migraciones para crear las tablas adicionales necesarias para la API.
```bash
   php artisan migrate
```
 
8. Ejecutar el enlace simbólico para el servicio de imágenes.
```bash
   php artisan storage:link
```

### Cómo usarla
 
Puedes probarla con `/cliente_rest_to6`, un cliente desarrollado en PHP que consume los endpoints desde una web simple para demostrar el funcionamiento de la API.
 
Para ejecutarlo, despliega la carpeta del cliente en un servidor con soporte para PHP (Apache, Nginx, Laragon, etc.) y accede a la URL correspondiente según tu configuración.
 
Por ejemplo, si usas XAMPP, mueve la carpeta `/TO6` del repositorio a `/xampp/htdocs`.
 
> Al mover la carpeta que contiene la API, asegúrate de ejecutar de nuevo el enlace simbólico. Si al mover el proyecto ya existe un archivo `storage` dentro de `/public`, elimínalo antes de volver a ejecutar el enlace.
 
El cliente de prueba estará disponible en:
```
http://localhost/TO6/cliente_rest_to6/abonos/compra_abonos.php
```

Para probar el login de administrador, usa las credenciales de prueba incluidas en `uda_api.sql`:
 
- **Usuario:** `uda`
- **Contraseña:** `1234`

## Documentación

Para consultar la documentación completa de la API, incluidos los endpoints y ejemplos de petición y respuesta, revise el archivo `Documentacion API abonos.pdf` incluido en el repositorio.
 
## Autor
 
**Desire-e** — [GitHub](https://github.com/Desire-e)