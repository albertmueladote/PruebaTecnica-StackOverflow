# Albert Muela Dote

## Descripción

Este es un proyecto Laravel que permite gestionar y visualizar búsquedas y preguntas desde la API de Stack Overflow. Incluye funcionalidades para realizar búsquedas, guardar resultados y ver los detalles de búsquedas guardadas.

## Requisitos

PHP 8.2.0
Mysql
Laravel 11.16.0
Composer 2.5.5

## Instalación

Clonamos el proyecto con "git clone https://github.com/albertmueladote/stackoverflowtest"

Instalamos dependencias con "composer install"

Edita el fichero .env con las credenciales adecuadas
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

Generamos una clave nueva para el proyecto con "php artisan key:generate"

Migramos la base de datos con "php artisan migrate"

Configuramos el entorno donde lo vamos a probar, se aconseja Xampp o similares por sencillez.

  