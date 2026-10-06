# Laboratorio SQL · Última plaza con dos conexiones

Ejercicio aislado de PostgreSQL 18. No prueba todavía el servicio Laravel. Aquí se entregan archivos y resultados esperados; su ejecución permanece NO-VERIFICADA en el equipo de redacción, que no dispone de `psql` en PATH.

Usa exclusivamente la base dedicada `fm_test`, con la cuenta de práctica `fm_app`, creada siguiendo Montaje del entorno de desarrollo. Los archivos comprueban el nombre de base y reservan el esquema `fm_laboratorio_plazas`; no leen ni cambian tablas de la aplicación. No uses este esquema para otros ejercicios. Si hay varias parejas en el mismo servidor, cada pareja necesita su propia instancia/puerto de pruebas o un turno exclusivo acordado; no reinicies el laboratorio de otra pareja.

## Preparación, una sola vez

1. Abre PowerShell en esta carpeta `sql` desde el Explorador. Ejecuta `Get-Location` y comprueba que contiene `preparar.sql`.
2. Comprueba con la pareja de integración el puerto de tu PostgreSQL. Estos comandos usan `55432`; sustituye solo ese número si instalaste otro. Si `psql` no está en PATH, usa la ruta completa de `psql.exe` del montaje.
3. Ejecuta el comando siguiente. `-X` evita cargar configuraciones personales de psql; `-W` pide la contraseña sin escribirla en el comando.

```powershell
psql -X -h 127.0.0.1 -p 55432 -U fm_app -d fm_test -W -f preparar.sql
```

Esperado: base `fm_test`, ID de conexión y una sesión con id=1/capacidad=1. Si el esquema ya existe, el archivo falla sin sustituirlo; averigua de quién es antes de continuar. La creación usa una transacción y no admite sobrescribir un esquema existente.

## Carrera, dos terminales que permanecen abiertas

1. Abre **dos** ventanas PowerShell en esta carpeta. En cada una ejecuta:

```powershell
psql -X -h 127.0.0.1 -p 55432 -U fm_app -d fm_test -W
```

2. En el prompt psql de la terminal A escribe el siguiente comando. No uses `psql -f conexion_A.sql`: al salir ese proceso perderías la transacción abierta que necesitas observar.

```text
\i conexion_A.sql
```

Esperado: conexión A identificada, `INSERT 0 1` y aviso de transacción ABIERTA. La sesión mantiene bloqueada la fila. En el prompt puede aparecer `*` indicando transacción activa.

3. Sin cerrar A, en la terminal B escribe:

```text
\i conexion_B.sql
```

Esperado: base igual e ID de conexión distinto. B se detiene en la lectura `FOR UPDATE`; guarda la observación y vuelve a A en menos de 60 segundos.

4. En A escribe:

```sql
COMMIT;
```

Esperado en B: continúa, indica `INSERT 0 0`, confirma y muestra `reservas=1`, `ganador=A`. La nueva lectura del conteo, en aislamiento READ COMMITTED, observa la reserva confirmada por A. Una captura de dos pestañas de navegador no acredita esta espera del motor.

5. Conserva base, dos IDs de conexión, espera y resultados de inserción; añade la consulta final siguiente. No captures contraseñas.

```sql
SELECT count(*) AS reservas, max(etiqueta) AS ganador
FROM fm_laboratorio_plazas.ensayo_reservas WHERE sesion_id = 1;
```

## Variante con retirada y repetición

Termina primero ambas transacciones con COMMIT o ROLLBACK. En una terminal psql ejecuta `\i reiniciar.sql`; esperado: cero reservas. Este archivo solo borra las filas de las reservas del esquema de este laboratorio, sin reiniciar identidades ni tocar tablas del producto.

Repite A y B. Mientras B espera, escribe `ROLLBACK;` en A. Ahora la inserción de A desaparece; esperado en B: `INSERT 0 1`, `reservas=1`, `ganador=B`. Explica por qué un intento no confirmado no consume la plaza.

Si B supera 60 segundos, su sentencia falla y la transacción queda abortada: escribe `ROLLBACK;` en B y termina también A. Si A permanece inactiva más de cinco minutos, PostgreSQL puede cerrar esa conexión; abre otra y repite desde cero. No interpretes un timeout como rechazo por falta de plazas. Tras cualquier error dentro de una transacción, ejecuta ROLLBACK antes de reintentar.

## Retirar únicamente el ejercicio

Tras cerrar las dos transacciones, ejecuta `\i retirar.sql`. Comprueba previamente que la pareja ha acabado y que el esquema sigue reservado solo para este ejercicio. El archivo verifica base y marca de laboratorio y elimina las dos tablas y su esquema sin CASCADE. Si apareció una dependencia ajena, falla y revierte: no añadas CASCADE para forzarla.

## Evidencia y siguiente paso

Tu evidencia debe distinguir lo esperado de lo obtenido, indicar versión PostgreSQL, base y conexiones y mostrar ambos recorridos. El resultado A/B depende de quién bloqueó primero; ejecutando el orden de esta guía debe ganar A con COMMIT y B con ROLLBACK. Un recuento final distinto exige revisar y repetir.

Después, construye el comando CLI de ensayo del servicio de reserva Laravel y compite con dos procesos que usen conexiones distintas. El éxito del laboratorio SQL no sustituye esa prueba del código integrado ni acredita permisos, token, idempotencia o firma.

Referencia de los comandos `\i`, `\ir` y parada por error: [psql de PostgreSQL 18](https://www.postgresql.org/docs/18/app-psql.html).
