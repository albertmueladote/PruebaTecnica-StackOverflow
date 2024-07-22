# Albert Muela Dote

## Descripción

Este es un proyecto Laravel que permite gestionar y visualizar búsquedas y preguntas desde la API de Stack Overflow. Incluye funcionalidades para realizar búsquedas, guardar resultados y ver los detalles de búsquedas guardadas.

## Requisitos

PHP 8.2.0
Mysql
Laravel 11.16.0
Composer 2.5.5

## Instalación

Clonamos el proyecto con "git clone https://github.com/albertmueladote/stackoverflowtest" en el directorio correspondiente de la aplicación que usaremos, preferiblemente una como Xampp o Laragon, para mayor sencillez. Configuramos el entorno para poder ver el proyecto, por ejemplo, en Xampp no olvidemos añadir la ruta al hosts y la configuración a httpd-vhosts.conf, pero varía para cada aplicación.

Entramos en el directorio con "cd stackoverflowtest"

Instalamos dependencias con "composer install"

Creamos nuestro fichero .env a partir de la plantilla con "cp .env.example .env" y editamos el fichero .env con las credenciales adecuadas
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

Generamos una clave nueva para el proyecto con "php artisan key:generate"

Migramos la base de datos con "php artisan migrate"
 
## Estructura

Contamos con un controlador, ApiController con las funciones que vamos a necesitar, están explicadas en sus respectivas cabeceras.
Tenemos los modelos Search y Question. Cada pregunta está asociada a una búsqueda y gracias a eloquent, facilitaremos las consultas a la DDBB.
Por último tenemos el web.php con las rutas y el list.blade.php que es la única vista en la que trabajaremos, para mayor sencillez de la prueba.

## Guía

La vista está dividia en cuatro bloques. 

El primer bloque contiene los filtros que podemos rellenar para hacer llamadas a la API, esta devolverá los resultados y alimentaremos la tabla que tenemos mas abajo.

El segundo bloque contiene los datos de la última búsqueda realizada, búsqueda a la que hace referencia la tabla que tenemos mas abajo. De este modo, aunque modifiquemos los filtros, siempre sabremos que datos se usaron para alimentar la tabla que tenemos actualmente en pantalla.

En el tercer bloque tenemos la tabla con los datos buscados. Tenemos el título, un link a la misma y cuando se creó.

En el cuarto y último bloque tenemos todas las búsquedas que hemos guardado y que podemos consultar cuando queramos.

## A tener en cuenta

Para evitar que se hagan llamadas excesivas a la API, simulando un entorno con un flujo enorme de datos, he decidido trabajar con sesiones, cuando hago una búsqueda o relleno unos filtros, estos datos quedan en sesión y los sacamos de ahí cada vez que necesitamos y solo llamamos a la API en dos ocasiones:

- Cuando realizamos una búsqueda nueva
- Cuando guardamos, por si han aparecido nuevas respuestas que encajen con nuestro filtro desde la última búsqueda.

Si le damos a limpiar, no solo reseteamos los filtros, si no que eliminamos los registros de la sesión para limpiar la vista. 
