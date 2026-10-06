# Montaje del entorno de desarrollo

Guía del alumno y de la pareja de integración. Revisada documentalmente el 24/09/2026; arranque completo NO-VERIFICADO en el equipo de redacción. Sigue la rama PHP 8.4 / Laravel 13 / PostgreSQL 18 acordada. Los resultados de cada paso son lo que debes observar, no resultados ya obtenidos por ti.

## 1. Dos entradas para empezar sin esperar

**Sprint 1:** abre `laboratorio/index.html` con el navegador. Ese laboratorio HTML sí está incluido y funciona como maqueta local sin servidor. Lee su indicación de maqueta, crea solo personas ficticias, prueba un dato válido y otro inválido y explica qué validación se ejecuta. El contrato de datos y los casos de prueba se utilizarán después en el backend.

**Qué contiene el recurso:** HTML, datos ficticios, ejercicios SQL y plantillas pequeñas de ejemplo. No incluye una aplicación Laravel instalada, `artisan`, dependencias descargadas ni un MVP terminado. En el apartado 5 crearás tú el esqueleto oficial con Composer. El montaje completo con PHP 8.4 y PostgreSQL 18 sigue NO-VERIFICADO en el equipo de redacción; el PHP 8.2.28 disponible solo ha permitido comprobar sintaxis de ejemplos, no arrancar Laravel 13.

**Montaje backend:** primero comprueba lo que ya tienes. No instales cinco herramientas a la vez ni cambies la configuración de otro proyecto. Abre PowerShell sin administrador y ejecuta uno a uno:

```powershell
php -v
php --ini
php -m
composer --version
psql --version
```

Resultado: versión PHP 8.4.x, Composer 2.x, PostgreSQL 18.x y extensiones requeridas. Si un comando no existe, eso significa que no está disponible por ese nombre en la terminal; anota el mensaje. No demuestra que nunca se instalara. Registra versión, ruta y captura sin contraseñas. Limita a 45 minutos el primer intento; después abre incidencia y continúa con la maqueta, contratos o pruebas asignadas. Integración acompaña; cada alumno conserva su propia evidencia.

## 2. Preparar PHP sin cambiar el sistema

La ruta portátil solo sirve si tu centro permite ejecutar binarios descargados y ya tienes los componentes de Windows que PHP requiere. Si la política lo impide, pide al coordinador una solución autorizada; no desactives controles. La página oficial identifica el runtime Visual C++ 2015–2022 requerido. [Descarga Windows](https://windows.php.net/download/).

1. Crea, con el Explorador, una carpeta `HerramientasFM` dentro de tu perfil y otra `ProyectosFM`.
2. En la web oficial selecciona PHP 8.4, x64, Non Thread Safe y ZIP. Guarda la versión y verifica el SHA-256 publicado con `Get-FileHash -Algorithm SHA256 -LiteralPath 'ruta-del-zip'`.
3. Extrae todo el ZIP en `HerramientasFM\php84`; no copies solo php.exe, porque necesita bibliotecas.
4. Dentro de esa carpeta copia php.ini-development a php.ini si todavía no existe. Abre ese php.ini con el editor. Fija `extension_dir="ext"`. Habilita quitando el punto y coma las entradas `extension=curl`, `extension=fileinfo`, `extension=mbstring`, `extension=openssl`, `extension=pdo_pgsql`, `extension=pgsql`, `extension=gd` y `extension=zip` que ofrece el archivo. No dupliques líneas ni descargues DLL de versiones distintas.
5. Usa una ruta explícita para comprobar el ejecutable. Estos nombres de variable son de la tarea; no sustituyen variables del sistema:

```powershell
$fmPhp = Join-Path $env:USERPROFILE 'HerramientasFM\php84\php.exe'
& $fmPhp -v
& $fmPhp --ini
& $fmPhp -m
```

Resultado: aparecen PHP 8.4.x, el php.ini que acabas de editar y pdo_pgsql, mbstring, dom y gd. Si sigue saliendo PHP 8.2 con `php -v`, tu ruta global apunta a otra instalación; usa `$fmPhp`. Si falta VCRUNTIME, el coordinador debe resolver ese requisito. No se soluciona ignorando requisitos de Composer. [Configuración manual](https://www.php.net/manual/en/install.windows.manual.php).

## 3. Composer y dependencias reproducibles

Si Composer ya funciona con PHP 8.4, úsalo. Para modo local, descarga composer.phar siguiendo la sección Manual Download de la [página oficial](https://getcomposer.org/download/) y su verificación publicada; guárdalo en HerramientasFM. Para que los subprocesos de Composer encuentren el mismo PHP durante esta terminal, añade su carpeta al principio de PATH, conservando el contenido anterior:

```powershell
$fmComposer = Join-Path $env:USERPROFILE 'HerramientasFM\composer.phar'
$env:PATH = (Split-Path $fmPhp) + ';' + $env:PATH
& $fmPhp $fmComposer --version
```

El cambio de PATH anterior solo afecta a esta terminal. Esperado: Composer 2.x y PHP 8.4. Al cerrar la terminal tendrás que repetir las variables y el PATH de sesión. En todos los pasos siguientes, `composer ...` se puede sustituir por `& $fmPhp $fmComposer ...`; `php ...`, por `& $fmPhp ...`.

Este recurso todavía no trae `composer.json` ni `composer.lock` de la aplicación: los creará Composer en el apartado 5. En copias posteriores del proyecto real, `composer install` reproduce su lock; no ejecutes `create-project` otra vez. Conserva y comparte el lock generado después de probarlo. No uses `--ignore-platform-reqs` para saltar PHP/extensiones. `composer check-platform-reqs` debe confirmar requisitos. Una descarga fallida se resuelve revisando red/certificados con el coordinador; no desactives TLS.

## 4. PostgreSQL local, sin servicio obligatorio

Con permisos de instalación puedes usar el instalador enlazado por PostgreSQL. No necesitas StackBuilder ni complementos. Sin elevación, y solo si la ejecución está permitida, utiliza el ZIP de binarios enlazado como opción avanzada en [Windows installers](https://www.postgresql.org/download/windows/). Extrae PostgreSQL 18 en `HerramientasFM\pgsql18` de modo que dentro esté `bin\psql.exe`.

Prepara una carpeta NUEVA `pgdatos18` junto a esa distribución. `initdb` solo se usa una vez sobre una carpeta vacía: no borres una base existente para repetirlo. Estos comandos crean un clúster dedicado a la práctica, con contraseña solicitada por terminal, y arrancan en puerto 55432 y dirección local:

```powershell
$fmPg = Join-Path $env:USERPROFILE 'HerramientasFM\pgsql18\bin'
$fmDatos = Join-Path $env:USERPROFILE 'HerramientasFM\pgdatos18'
& "$fmPg\initdb.exe" -D $fmDatos -U postgres -W -A scram-sha-256 -E UTF8 --locale=C
& "$fmPg\pg_ctl.exe" -D $fmDatos -l "$fmDatos\servidor.log" -o '-h 127.0.0.1 -p 55432' -w start
& "$fmPg\psql.exe" -h 127.0.0.1 -p 55432 -U postgres -d postgres -W
```

Resultado: initdb termina, pg_ctl comunica inicio y psql muestra el prompt `postgres=#`. Guarda tu contraseña local; no la publiques ni la pongas en capturas. Dentro de psql, crea una cuenta restringida y dos bases de esta práctica. La segunda solo servirá para pruebas automáticas. `\password` pregunta la contraseña sin escribirla en el historial SQL.

```sql
CREATE ROLE fm_app LOGIN NOSUPERUSER NOCREATEDB NOCREATEROLE;
\password fm_app
CREATE DATABASE fm_demo OWNER fm_app;
CREATE DATABASE fm_test OWNER fm_app;
\q
```

Si ya existen, no repitas CREATE ni los borres; identifica si son los de tu práctica. Comprueba la cuenta:

```powershell
& "$fmPg\psql.exe" -h 127.0.0.1 -p 55432 -U fm_app -d fm_demo -W -c 'SELECT current_database(), current_user;'
```

Esperado: fm_demo y fm_app. La aplicación no se conectará como postgres. Con instalador, adapta puerto al valor real, normalmente 5432. No cambies un servicio PostgreSQL que pertenezca a otro proyecto. El modelo portátil no registra un servicio Windows; para detenerlo al terminar:

```powershell
& "$fmPg\pg_ctl.exe" -D $fmDatos -m fast -w stop
```

Fuentes de estos comandos: [initdb](https://www.postgresql.org/docs/18/app-initdb.html), [pg_ctl](https://www.postgresql.org/docs/18/app-pg-ctl.html). La falta de permisos o runtime puede impedir este camino; el coordinador habilita un entorno compatible y confirma su acceso, sin dar por hecho un servidor remoto.

## 5. Crear el esqueleto oficial y abrir una página propia

Hazlo cuando `php -v` muestre 8.4 y Composer funcione con ese ejecutable. Abre PowerShell en una carpeta nueva de tu práctica, dentro de `ProyectosFM`. No debe contener otra carpeta `backend`: si ya existe, inspecciona su origen y continúa esa instalación, sin sobrescribirla. Mantén `laboratorio` separado del backend.

```powershell
composer create-project laravel/laravel backend '13.*' --no-scripts
Set-Location -LiteralPath backend
Get-Item -LiteralPath artisan,composer.json,composer.lock
```

Ejecuta una línea cada vez y detente si falla. Esperado: Composer descarga el esqueleto y dependencias y aparecen esos tres archivos dentro de `backend`. `--no-scripts` aplaza los scripts automáticos hasta configurar PostgreSQL; evita que el primer arranque prepare otra base por defecto. La sintaxis de paquete, carpeta y versión procede de [create-project de Composer](https://getcomposer.org/doc/03-cli.md#create-project). Este paso necesita red; no se ha ejecutado durante la redacción.

Abre la carpeta `backend` completa en el editor. Copia `.env.example` a `.env` solo si aún no existe; el archivo local contiene claves y nunca se añade a Git. Configura estos valores en el editor, sustituyendo la contraseña de ejemplo por la de fm_app:

```dotenv
APP_NAME="Fundacion demo"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=55432
DB_DATABASE=fm_demo
DB_USERNAME=fm_app
DB_PASSWORD="TU_CONTRASENA_LOCAL"
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

Se usan sesiones/cola/caché locales para que el primer arranque no exija servicios extra. No son una configuración de producción. Antes de migrar, comprueba mediante psql que `fm_demo` pertenece a esta práctica; no uses una base de otro proyecto. Después ejecuta, de una en una:

```powershell
composer check-platform-reqs
composer dump-autoload
php artisan key:generate
php artisan config:clear
php artisan migrate
php artisan route:list
```

Esperado: requisitos compatibles, descubrimiento de paquetes al reconstruir autoload, clave generada, migraciones iniciales y rutas listadas. Esas migraciones pertenecen al esqueleto: todavía no crean centros, usuarios atendidos, sesiones o documentos de Fundación. No ejecutes `migrate:fresh` para “arreglar” un problema: borra tablas. Crear migraciones y seeders del dominio es trabajo posterior del roadmap. La clave APP_KEY se genera una vez por entorno, no en cada arranque.

Ahora crea una página que no necesite compilar recursos:

1. Lee `laboratorio/plantillas_backend/LEEME.md`. Copia `demo-centros.blade.php` a `backend/resources/views/`, sin sustituir vistas existentes.
2. Abre `backend/routes/web.php` y añade solo la declaración de ruta de `rutas_demo.php`. Conserva la apertura PHP y el `use Route` existentes; no los dupliques ni reemplaces el archivo entero.
3. Ejecuta `php artisan route:list --path=demo-centros`. Esperado: una ruta GET/HEAD para `/demo-centros`.
4. Ejecuta `php artisan serve --host=127.0.0.1 --port=8000` y abre `http://127.0.0.1:8000/demo-centros`. Esperado: Centro Norte y Centro Sur con una nota que explica que los datos todavía son un array fijo.

La plantilla incluida no usa `@vite` ni dependencias JS. La página inicial oficial puede seguir otro flujo; utiliza `/demo-centros` para este ensayo. No ejecutes `composer run dev`, pues puede iniciar compilación o colas ajenas a este recorrido. Detén el servidor con Ctrl+C. Al día siguiente inicia PostgreSQL y `artisan serve`; no repitas creación, migraciones destructivas ni generación de clave. [Configuración Laravel](https://laravel.com/docs/13.x/installation).

## 6. Primera contribución comprobable

1. Localiza tu GET `/demo-centros` y la vista que devuelve; cambia una etiqueta y recarga. Esperado: la etiqueta cambia sin tocar el array ni compilar JavaScript.
2. Cambia un nombre del array por otro ficticio. Esperado: la vista escapa y muestra el nuevo texto; todavía no hay persistencia.
3. Durante la tarea de registro, crea la migración y el POST con validación siguiendo Recetas de backend y firma. Esa operación no viene implementada por el esqueleto.
4. Cuando exista ese POST, envía un campo vacío y luego uno válido. Esperado: error recuperable en el primero y fila consultable por ID en el segundo.
5. Explica qué parte has ejecutado realmente. Conserva versión, entrada, salida esperada, salida observada y revisión de un compañero.

Si el backend aún solo tiene la página inicial, construir el formulario y su migración es trabajo de las tareas del roadmap: no inventes que el paquete ya lo implementa. La contribución empieza por ejecutar y explicar el ejemplo disponible.

## 7. Verificar última plaza con dos conexiones

Primero sigue `laboratorio/sql/LEEME.md`. Incluye `preparar.sql`, `conexion_A.sql`, `conexion_B.sql`, `reiniciar.sql` y `retirar.sql`; comprueban `fm_test` y utilizan únicamente el esquema `fm_laboratorio_plazas`. El montaje y la carrera SQL son resultados esperados, NO-VERIFICADOS aquí porque no se dispone de PostgreSQL 18. No ejecutan migraciones de tu aplicación.

El recorrido es: preparar el esquema una vez, abrir dos terminales psql, ejecutar A sin terminar su transacción, ejecutar B hasta observar espera y volver a A para escribir COMMIT. B continúa y termina sin insertar. El LEEME contiene los comandos completos y una variante con ROLLBACK. El siguiente SQL resume el mecanismo; usa los archivos para hacer el ejercicio, no crees una segunda copia de tablas:

```sql
SELECT current_database(), pg_backend_pid();
```

En terminal A ejecuta y DETENTE antes de COMMIT:

```sql
BEGIN;
SELECT id FROM fm_laboratorio_plazas.ensayo_sesiones WHERE id=1 FOR UPDATE;
INSERT INTO fm_laboratorio_plazas.ensayo_reservas(sesion_id,etiqueta)
SELECT 1,'A' WHERE (SELECT count(*) FROM fm_laboratorio_plazas.ensayo_reservas WHERE sesion_id=1) < (SELECT capacidad FROM fm_laboratorio_plazas.ensayo_sesiones WHERE id=1);
```

En terminal B ejecuta los mismos tres comandos cambiando 'A' por 'B'. Debe esperar en SELECT FOR UPDATE. Vuelve a A y ejecuta COMMIT. B continúa, pero su INSERT muestra cero filas porque la plaza ya está ocupada. Ejecuta COMMIT en B y consulta:

```sql
SELECT count(*) AS reservas, max(etiqueta) AS ganador FROM fm_laboratorio_plazas.ensayo_reservas WHERE sesion_id=1;
```

Resultado esperado: reservas=1 y ganador=A. Guarda una captura de espera y el resultado. Si B entró primero, puede ganar B; lo esencial es una reserva y el segundo intento sin inserción. Para repetir, termina las dos transacciones y ejecuta `reiniciar.sql`, que solo limpia las filas de este ejercicio. `retirar.sql` elimina únicamente sus dos tablas y esquema, sin CASCADE. No cierres una terminal dejando una transacción abierta; COMMIT o ROLLBACK la termina.

Este ensayo no da por probado el servicio Laravel. Después, dos procesos CLI independientes deben ejecutar la función de reserva del equipo sobre una sesión de prueba. Uno crea y el otro informa sin plazas; verifica exactamente una fila y repite cancelación y cambio fallido. En Windows dos pestañas del servidor PHP incorporado pueden atenderse secuencialmente; no las presentes como prueba de concurrencia. Revisa que el código real usa la transacción y el mismo bloqueo para público y recepción.

## 8. Firma y documento: recorrido de ensayo

Desde la carpeta backend instala la biblioteca de PDF con `composer require "dompdf/dompdf:^3.1.6"`, ejecuta `composer check-platform-reqs` y registra la versión resuelta en composer.lock. [Instalación y uso de dompdf](https://github.com/dompdf/dompdf). No viene incluida en el esqueleto Laravel.

Copia `laboratorio/plantillas_backend/ensayo_pdf.php` a backend/ensayo_pdf.php y ejecuta `php ensayo_pdf.php`. Esperado: PDF ficticio en storage/app/private/ensayo_pdf/ensayo.pdf y su hash. Ábrelo localmente; no publiques esa carpeta. Este ensayo solo comprueba generación, no la firma ni los permisos. Si falla, guarda el error y comprueba GD, extensiones y escritura antes de desarrollar el circuito.


Con la tarea de firma implementada, abre una cita ficticia, confirma persona y texto y dibuja con ratón. Antes de eso pulsa confirmar con lienzo vacío: debe fallar. Borra, vuelve a dibujar y confirma. Recupera el PDF desde la ruta autorizada, reinicia el servidor y repite la recuperación. Comprueba versión/texto, persona, firmante, centro y fecha. Una cuenta sin permiso no debe recuperar el archivo aunque conozca su URL.

En un taller confirma una firma, pulsa siguiente y verifica que no se conserva el dibujo o nombre anterior. Emula un dispositivo táctil desde herramientas del navegador para comprobar tamaños y mensajes; registra “emulación”, no “tableta real”. La prueba en tableta del coordinador o cliente se registra aparte.

Antes de correr tests que reinicien tablas, revisa `.env.testing`: debe apuntar a fm_test, nunca fm_demo. Revisa también phpunit.xml: el esqueleto oficial puede fijar DB_CONNECTION=sqlite y DB_DATABASE=:memory:, prevaleciendo sobre lo esperado. Sustituye esas dos entradas por pgsql y fm_test o retíralas para utilizar .env.testing; conserva APP_ENV=testing. Revisa DB_URL y cualquier variable heredada que cambie la conexión. Ejecuta config:clear y comprueba conexión efectiva y SELECT current_database() antes de habilitar pruebas destructivas. [phpunit.xml oficial de Laravel 13](https://github.com/laravel/laravel/blob/13.x/phpunit.xml). Confirma el nombre efectivo de base en el arranque de pruebas y aborta si no coincide. Conserva archivos firmados de la demo fuera de public y fuera de la carpeta que borren las pruebas.

## 9. Errores frecuentes y una acción concreta

| Mensaje/síntoma | Revisa | Acción y salida esperada |
|---|---|---|
| php no se reconoce | Ruta del ZIP | Ejecuta con $fmPhp; -v muestra 8.4. |
| Requires PHP >=8.3 | Ejecutable usado por Composer | PATH de esta terminal y ruta explícita; no ignorar requisitos. |
| could not find driver | php.ini real y pdo_pgsql | Habilita extensión del mismo ZIP, reinicia servidor; -m la muestra. |
| VCRUNTIME o DLL ausente | Runtime y ZIP completo | Incidencia al coordinador si exige instalación; no mezclar DLL externas. |
| connection refused | PostgreSQL parado/puerto distinto | pg_ctl status o servicio autorizado; comprobar puerto real de .env. |
| password authentication failed | Cuenta, contraseña, base | Probar psql con fm_app; corregir .env y config:clear. |
| No application encryption key | APP_KEY no generada | key:generate una vez en .env local, no en archivo compartido. |
| 419 Page Expired | CSRF, sesión, host | Usar @csrf, recargar formulario y mismo host; no desactivar CSRF. |
| Vite manifest not found | Vista o script de otro flujo | Abrir /demo-centros con la plantilla incluida sin @vite. |
| 403 en una ruta | Cuenta/centro/policy | Comparar matriz de permisos; no convertirlo automáticamente en fallo de login. |
| Firma desaparece al girar | Redimensión canvas | Conservar/restaurar trazos o pedir repetir explícitamente; no enviar vacío. |
| PDF sin imagen | GD, ruta privada, imagen válida | Ensayo con PNG local y plantilla fija; no habilitar remoto para tapar error. |
| Prueba bloqueada en SQL | Transacción abierta | Terminar A con COMMIT/ROLLBACK; revisar orden uniforme de bloqueos. |

## 10. Entrega reproducible

Entrega código, composer.json y composer.lock real; .env.example sin secretos; migraciones/seeders sintéticos; guía de cuentas de demo locales; datos exportables y archivos de ejemplo necesarios. Si el PDF se guarda como archivo, el paquete debe incluir sus archivos ficticios o explicar su regeneración desde datos y trazos ficticios. Una base sin las imágenes privadas no reproduce el catálogo documental.

Otro compañero instala desde la guía, genera su APP_KEY y contraseñas locales, crea sus bases y carga la demo. Registra diferencias y corrige la guía. El ZIP del producto final contiene únicamente material ficticio. Dependencias opcionales y ficheros grandes se documentan; conservar licencias. No se entrega una carpeta con .env, contraseñas o datos reales.
