Lotería de animalitos
======================================================

Sistema web para la venta por taquilla de tickets para sorteo de animalitos con 2 niveles de usuario

Niveles de usuario
---------------------------------------------------------------------------------------------

### Taquilla
El usuario encargado de las ventas, ejerce las siguientes funciones:

* Venta de tickets
* Consultar resultados de los sorteos
* Consultar los tickets registrados y ganadores
* Registrar el pago a ticket ganador

### Administrador
Usuario encargado de controlar lo que se vende desde la taquilla, ejerce las siguientes funciones:

* Todas las del usuario **Taquilla**
* Registrar sorteos por hora
* Administrar los usuarios para las taquillas
* Registrar animal ganador de cada sorteo

Pasos para la instalación
---------------------------------------------------------------------------------------------

1. Renombrar el archivo **.env_example** por **.env**
2. En el archivo **.env** se debe configurar los parametros de conexion a una base de datos previamente creada
3. Generar la base de datos corriendo el siguiente comando desde la raiz del proyecto

        php artisan migrate
4. Cargar los usuarios predeterminados

        php artisan db:seed
Estos usuarios son **admin** y **taq1**, ambas contraseñas **123456**

¿Como imprimir desde una tickera?
---------------------------------------------------------------------------------------------

Nativamente el navegador no podrá establecer una conexión con la tickera, para que esta comunicación sea posible es necesario que la misma este configurada como **Generic text** y hacer una instalación local que lo permita. En la carpeta **setup** esta el instalador de momento solo para windows que se encargara de esto.

Lo único que hay que hacer es seguir los pasos con el típico siguiente .. siguiente ..siguiente .. Luego debes configurar dos parámetros que te los pedirá con una interfaces gráfica.

**dominio**= (Dominio raíz de tu proyecto)

**usuario**= (El id del usuario en base de datos)

Ya configurado hacer click en guardar para mantener esta configuración y luego iniciar para que este a la escucha de lo que imprime la taquilla establecida. Al minimizar este se ocultara automáticamente en la barra de notificaciones.