# Plantillas pequeñas para construir el backend

Este directorio no es una aplicación instalada: no contiene `artisan`, `vendor` ni credenciales. Crea primero el esqueleto oficial siguiendo Montaje del entorno de desarrollo. Las plantillas ilustran piezas concretas; no implementan autenticación, migraciones, autorización ni el circuito completo de Fundación.

## Primera página sin compilación

1. Abre `backend/routes/web.php` en el editor. Conserva su contenido y comprueba que ya contiene `use Illuminate\Support\Facades\Route;`.
2. Abre `rutas_demo.php` y copia únicamente el bloque desde `Route::get` hasta su cierre `});`, al final de `routes/web.php`. No dupliques la apertura `<?php` ni el `use`.
3. Copia `demo-centros.blade.php` a `backend/resources/views/demo-centros.blade.php`. Si ya existe una vista con ese nombre, compara antes de sustituirla.
4. Desde `backend`, ejecuta `php artisan route:list --path=demo-centros`. Esperado: GET/HEAD de `/demo-centros`.
5. Arranca `php artisan serve --host=127.0.0.1 --port=8000` y abre `http://127.0.0.1:8000/demo-centros`. Esperado: los dos centros y aviso de array fijo. Cambia un nombre ficticio en la ruta y recarga para observar qué archivo proporciona los datos.

La vista no usa `@vite` ni necesita Node. Mantén el array mientras no exista una migración y semillas de centros. Cuando sustituyas por una consulta de Query Builder, sus filas serán objetos: adapta `$centro['nombre']` a `$centro->nombre` y lo mismo para código. No afirmes persistencia a partir del array.

## Reserva y clave: piezas para adaptar

`CrearReserva.php` es el bloque PHP extraído de Recetas de backend y firma. Su destino previsto es `backend/app/Services/CrearReserva.php`, creando la carpeta si falta. Antes de utilizarlo necesitas migraciones, datos, validación y autorización descritos en la receta; no lo conectes directamente a una ruta pública.

`ClaveGestionDemo.php` ilustra emisión de 32 bytes aleatorios y comparación de huellas. Su destino previsto es `backend/app/Support/ClaveGestionDemo.php`. La clave se muestra una sola vez al crear; en base solo se conserva `huella`. No registres ni imprimas la clave en logs o evidencias. El ejemplo no concede permisos por sí solo ni vincula una clave a una reserva: debes comprobar esa relación y los permisos antes de cada acción. Restablecer sustituye la huella de esa reserva y deja inválida la clave anterior.

## Comprobación y límite

Desde esta carpeta, `php -l rutas_demo.php`, `php -l CrearReserva.php` y `php -l ClaveGestionDemo.php` deben terminar con `No syntax errors detected`. Esos son resultados esperados. La comprobación local de redacción usó PHP 8.2.28; solo acredita sintaxis PHP. Blade requiere además compilación y renderizado en Laravel. Laravel 13 con PHP 8.4, conexión PostgreSQL 18 y funcionamiento integrado permanecen NO-VERIFICADOS hasta que los ejecutes y registres resultados.


## Ejercicios individuales con defecto señalado

Abre una terminal en esta carpeta. Ejecuta `php BuscarPorContacto_primera.php`: esperado son dos códigos, observado es solo uno. En una copia, devuelve todos los candidatos y ensaya un contacto que no existe. Ejecuta `php CambiarReserva_fallido.php`: el destino cambia aunque el ensayo dice SIN_PLAZA. En una copia comprueba antes de mutar y repite con/sin hueco. Ambos usan arrays; la corrección real de servidor necesita permisos y, para cambiar, transacción y bloqueos.

## Dos procesos ejecutando el servicio real

Primero termina el ejercicio SQL. Después, en tu backend, crea las migraciones del esquema reducido de Recetas o adapta las lecturas al diccionario completo. Crea en fm_test una sesión abierta, futura, de capacidad 1 y sin reservas; anota su ID. No uses las tablas ensayo_sesiones del laboratorio SQL: este comando utiliza sesiones y reservas de tu aplicación.

Copia CrearReserva.php y ConflictoReserva.php a app/Services; EnsayoCarrera.php a app/Console/Commands. Mantén estos archivos fuera de rutas públicas. Comprueba `php artisan list --env=testing` y que aparece fm:ensayo-plaza. .env.testing debe seleccionar pgsql/fm_test. El comando comprueba ambas condiciones antes de modificar; prepara también el reloj de tus semillas para que la sesión sea futura respecto al instante real del ensayo.

Abre dos terminales dentro del mismo backend. Sustituye 123 por tu sesión:

```powershell
php artisan fm:ensayo-plaza 123 A --pausa=15 --operacion=ENSAYO-A-001 --env=testing
```

Cuando A muestre «Reserva escrita sin confirmar», antes de quince segundos ejecuta en B:

```powershell
php artisan fm:ensayo-plaza 123 B --pausa=0 --operacion=ENSAYO-B-001 --env=testing
```

B debe mostrar inicio/PID pero esperar dentro de CrearReserva hasta que A termine. El comando no bloquea la sesión antes de llamar al servicio: la pausa sucede después de su escritura y antes del COMMIT exterior. Esperado: A crea, B recibe SIN_PLAZA y el recuento final es 1; los PID deben diferir. Guarda instantes y salidas. Si no hubo solapamiento, repite sobre otra sesión vacía; no declares concurrencia a partir de dos ejecuciones sucesivas. La pausa pertenece solo a este comando de ensayo. No la añadas a CrearReserva ni a una ruta de producto.

El comando verifica la transacción del servicio reducido; después repite la batería sobre los controladores reales para acreditar validación, CSRF y permisos. La aplicación completa sigue siendo trabajo del equipo. Los ocho archivos PHP de ejemplo han pasado php -l con PHP 8.2.28 el 25/09/2026; la plantilla Blade necesita compilación del framework. Laravel/PostgreSQL y dompdf requieren ejecución en el entorno montado por el grupo.


Para repetir el ganador usa la misma sesión, etiqueta A y --operacion=ENSAYO-A-001: esperado mismo ID y creada=false. Cambia la etiqueta conservando operación: esperado OPERACION_REUTILIZADA. Para el taller usa una sesión de capacidad 3, --capacidad=3 y operaciones distintas; prepara dos reservas previas para competir por la última plaza, o coordina cuatro procesos para comprobar tres éxitos y un rechazo.

Comprueba también que el ensayo detecta una implementación incorrecta. En una copia aislada del backend de pruebas, comenta solo lockForUpdate del servicio y repite sobre otra sesión vacía: B puede contar cero mientras A no confirma y el recuento debe evidenciar dos reservas. Restaura inmediatamente el servicio y repite: una reserva. Este ensayo de sensibilidad sigue NO-VERIFICADO hasta ejecutarlo en PostgreSQL; no se habilita en rutas de la aplicación ni sobre datos ajenos.
