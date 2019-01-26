## jruiz-goal-inventory

## Gestor de inventarios. API Web. Goal

Descripción:

Gestor de inventarios mediante una API Web con
Framework PHP Symfony 4.1.

##### Nota: El repositorio ya tiene dos bases de datos generadas con algún dato para pruebas. Sin contraseñas.

app.db para las APIs

test.db3 para los tests

Pasos para probar la API
------------------------

Requisitos previos instalados:
- PHP 7.2 con extensiones:
    ```
   # sudo apt install php7.2-sqlite3
   # sudo apt install php7.2-zip
   # sudo apt install php7.2-mbstring
- composer
- Postman


#### 1º Descarga el código del repositorio

```
  # git clone https://github.com/javier-ruiz-87/jruiz-goal-inventory.git
```

#### 2º Instala las dependencias. Ejecuta

```
 # cd jruiz-goal-inventory
 # composer install
 ```

#### 3º Inicia un servidor. Como:

```
 # php bin/console server:run
 ```
 
http://localhost:8000/

#### 4º Probar la api. Abre Postman

ACCIONES DE LAS APIs: 
#### 1.1 API para añadir un artículo al inventario ##
Cuando añado un artículo al inventario, éste contiene información sobre el elemento que se acaba de añadir, tal como Nombre, Fecha de caducidad, Tipo, etc.

En Postman indicamos:
localhost:8000/api/articulo-nuevo
Pasa por POST los parámetros:
 - KEY = 'nombre' y VALUE = 'manzanas'
 - KEY = 'tipo' y VALUE = 'fruta'
 - KEY = 'fecha_caducidad' y VALUE = '01-01-2018'
 
Damos a Send.

El artículo se ha añadido a la base de datos app.db

Nos aparecerá una respuesta json con el resultado.

Podemos comprobar el resultado en la base de datos con:

 ```
 # php bin/console doctrine:query:sql 'SELECT * FROM articulo WHERE nombre = "manzanas"'  
 ```
 
#### 1.2 API para sacar un elemento del inventario por nombre ##
Cuando saco un elemento del inventario, el elemento deja de estar disponible en el inventario.

En Postman indicamos:
localhost:8000/api/articulo-extract
Pasar por POST el parámetro:
 - KEY = 'nombre' y VALUE = 'manzanas'
 
Damos a Send.

El artículo se devuelve con sus datos y se cambia su valor disponible a false en la base de datos app.db indicando que ya no está disponible para su extracción.

Nos aparecerá una respuesta json con el resultado.

#### 1.3 Notificar que un elemento se ha sacado del inventario ##
Cuando saco un elemento del inventario, entonces hay una notificación de que el elemento se ha sacado.

Al dejar de estar disponible un artículo tras extraerlo se lanza un evento que a través de un listener envía una notificación. Esta notificación podría enviar un correo, un sms, etc. En el caso desarrollado se añade un registro en la tabla notificaciones indicando datos referentes a la acción.

Tras haber realizado la acción del apartado 1.2 tendremos un nuevo registro en la tabla notificaciones. Si no disponemos de un visor de la base de datos podemos comprobar el resultado con:

 ```
 # php bin/console doctrine:query:sql 'SELECT * FROM notificacion WHERE objeto_nombre = "manzanas"' 
 ```

Veremos el registro de la notificación de que el artículo ha dejado de estar disponible.

#### 1.4 Notificar cuando un elemento caduca ##
Cuando un elemento del inventario caduca, entonces hay una notificación sobre el elemento caducado.

Para comprobar cuando un elemento caduca se ha realizado una tarea. Podemos ejecutarla:
 ```
 # php bin/console goalinventory:caducar
 ```


Buscará los artículos con fecha de caducidad inferior a la actual y actualizará el estado caducado a true. 

Veremos un mesaje del comando que indica que se ha caducado el artículo que creamos de 'manzanas'.

Al marcar como caducado un artículo se lanza un evento que a través de un listener envía una notificación. Esta notificación podría enviar un correo, un sms, etc. En el caso desarrollado se muestran dos notificaciones distintas: una añade un registro en la tabla notificaciones indicando datos referentes a la acción y otra es un mensaje que veremos por consola para el caso de ejecutar la tarea goalinventory:caducar.

Podemos ver la nueva notificación:
 ```
# php bin/console doctrine:query:sql 'SELECT * FROM notificacion WHERE objeto_nombre = "manzanas"'
 ```

#### 5º TESTS

Testean varias de las acciones realizadas. 
Lanzamos los test con:
 ```
# ./bin/phpunit
 ```

Podemos comprobar los resultados en la base de datos test.db3



Nota: no se ha implementado seguridad como podría ser un sistema de alta de usuarios de API, token, api key... No lo incluyo en el código porque hubiera sido demasiado costoso en tiempo.


#### TODO: meter pedidos y usuarios/shoppers como en lxlx market








