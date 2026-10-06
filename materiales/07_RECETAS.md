# Recetas de backend y firma

Apoyo del especialista · 24/09/2026. Son ejemplos didácticos para adaptar y probar, no una aplicación terminada. El código de reserva se comprueba sintácticamente con el PHP disponible; su ejecución con Laravel 13 y PostgreSQL sigue NO-VERIFICADO. La firma gráfica es de demostración. Usa datos ficticios.

## 1. Antes de conectar una pantalla

Cada pareja escribe cuatro cosas en la ficha de la operación: entrada, comprobaciones servidor, salida válida y salida de error. Ejemplo: “Crear reserva”; entrada sesión seleccionada, datos ficticios y token de operación. En público el servidor valida un prerregistro sin buscar identidades existentes; en recepción resuelve el usuario autorizado. Comprueba centro/servicio, sesión futura y capacidad; devuelve el comprobante o un error sin crear otra fila.

Frontend puede construir ese mensaje con un resultado simulado. Backend implementa el contrato con datos ficticios. QA mantiene dos entradas reproducibles. Integración conecta ambos cuando los nombres de campos coinciden. El cambio de implementación no cambia a escondidas el contrato; se explica en la revisión.

No se confunden identidad y cuenta: `personas` representa a quien recibe la actividad; la tabla Laravel `users` representa al personal que entra en la aplicación. Un representante es una identidad asociada al menor y al documento; no recibe automáticamente una cuenta de personal.

## 2. Modelo SQL mínimo de la reserva

Ejemplo acotado para la práctica; el maestro define los demás campos. Crear mediante migraciones, no pegando SQL de creación cada vez que arranca el servidor. Los timestamps se guardan como instantes y se muestran en Europe/Madrid. El equipo debe escoger y probar una sola convención de almacenamiento.

```sql
CREATE TABLE centros (
    id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nombre varchar(100) NOT NULL
);
CREATE TABLE personas (
    id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    codigo varchar(40) NOT NULL UNIQUE,
    nombre varchar(100) NOT NULL,
    contacto varchar(180),
    confirmada boolean NOT NULL DEFAULT false
);
CREATE TABLE sesiones (
    id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    centro_id bigint NOT NULL REFERENCES centros(id),
    servicio varchar(80) NOT NULL,
    inicio timestamptz NOT NULL,
    capacidad integer NOT NULL CHECK (capacidad > 0),
    abierta boolean NOT NULL DEFAULT true
);
CREATE TABLE reservas (
    id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    sesion_id bigint NOT NULL REFERENCES sesiones(id),
    persona_id bigint REFERENCES personas(id),
    prerregistro jsonb,
    payload_hash varchar(64) NOT NULL,
    estado varchar(20) NOT NULL DEFAULT 'reservada'
        CHECK (estado IN ('reservada','cancelada','atendida','no_presentada')),
    operacion varchar(64) NOT NULL UNIQUE,
    token_gestion_hash varchar(64) NOT NULL,
    created_at timestamptz NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamptz NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX reservas_ocupacion ON reservas(sesion_id,estado);
```

Contacto no lleva UNIQUE: dos personas pueden compartirlo. El código de persona sí es único y lo genera el servidor. El token de gestión original se entrega en el comprobante; se guarda su hash, nunca se sustituye por el código de persona. `operacion` impide repetir una misma solicitud; no identifica a la persona ni concede permiso sobre documentos. La ocupación es el número de reservas no canceladas; no se guarda otro contador que pueda quedar desincronizado.

Los estados históricos atendida/no_presentada siguen ocupando la sesión pasada. El servidor rechaza cualquier reserva sobre una sesión vencida. Los cambios de estado tienen reglas explícitas; no aceptes un campo estado arbitrario desde el formulario público. Si en el guion hay sesiones por servicio con tabla propia, sustituye servicio textual por su FK y conserva la comprobación centro-servicio.

## 3. Una operación pequeña que respeta la última plaza

Receta para `app/Services/CrearReserva.php`, con Query Builder para hacer visibles las lecturas. Requiere las tablas anteriores, entradas ya validadas y autorización previa del controlador. `operacion` debe haber sido emitida por el servidor para ese formulario/sesión y estar ligada al mismo usuario de la operación; no basta aceptar cualquier token escrito en el POST.

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;


final class CrearReserva
{
    public function ejecutar(
        int $sesionId,
        ?int $personaId,
        array $prerregistro,
        string $payloadHash,
        string $operacion,
        string $tokenGestionHash
    ): array {
        return DB::transaction(function () use (
            $sesionId, $personaId, $prerregistro, $payloadHash, $operacion, $tokenGestionHash
        ) {
            // Todas las operaciones de ocupacion bloquean esta misma fila.
            $sesion = DB::table('sesiones')->where('id', $sesionId)
                ->lockForUpdate()->first();
            if (!$sesion) {
                throw new ConflictoReserva('SESION_NO_DISPONIBLE', 'La sesion ya no esta disponible.');
            }
            // Reenvio del MISMO formulario: no crear una segunda reserva.
            $anterior = DB::table('reservas')->where('operacion', $operacion)->first();
            if ($anterior) {
                if ((int) $anterior->sesion_id !== $sesionId
                    || !hash_equals($anterior->payload_hash, $payloadHash)) {
                    throw new ConflictoReserva('OPERACION_REUTILIZADA', 'Recarga el formulario antes de continuar.');
                }
                return ['id' => (int) $anterior->id, 'creada' => false];
            }
            if (!$sesion->abierta || now()->greaterThanOrEqualTo($sesion->inicio)) {
                throw new ConflictoReserva('SESION_CERRADA', 'La sesion no admite nuevas reservas.');
            }
            $ocupadas = DB::table('reservas')->where('sesion_id', $sesionId)
                ->where('estado', '!=', 'cancelada')->count();
            if ($ocupadas >= $sesion->capacidad) {
                throw new ConflictoReserva('SIN_PLAZA', 'Ya no quedan plazas. Elige otra sesion.');
            }
            $id = (int) DB::table('reservas')->insertGetId([
                'sesion_id' => $sesionId,
                'persona_id' => $personaId,
                'prerregistro' => $personaId === null ? json_encode($prerregistro, JSON_THROW_ON_ERROR) : null,
                'payload_hash' => $payloadHash,
                'estado' => 'reservada',
                'operacion' => $operacion,
                'token_gestion_hash' => $tokenGestionHash,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return ['id' => $id, 'creada' => true];
        }, 3);
    }
}
```

Recorre el código con lápiz: BEGIN, bloquear, releer, contar, insertar, COMMIT. Ante excepción, la transacción revierte. Los tres intentos de transacción ayudan ante ciertos conflictos; no convierten un error de negocio en éxito. El controlador traduce errores de validación a mensajes del formulario y conserva las entradas permitidas. La inserción se hace una sola vez y usa parámetros del Query Builder. [Transacciones Laravel](https://laravel.com/docs/13.x/database#database-transactions).

Condiciones que el ejemplo NO resuelve por ti: validación de formatos, creación y confirmación de la persona, CSRF, permiso de personal, vínculo centro/servicio, token privado y protección contra abuso del endpoint público. Son trabajo visible de las tareas de registro/permisos/agenda. No conectes la receta a una ruta pública sin esas comprobaciones. La transacción no incluye enviar correos ni generar PDFs.

Prueba primero una reserva válida, luego una sesión llena y luego dos procesos independientes a la última plaza. Para probar el servicio concurrentemente, usa laboratorio/plantillas_backend/EnsayoCarrera.php siguiendo su LEEME; invoca ejecutar desde dos procesos con intentos ficticios distintos. Abre dos terminales conectadas a la misma base fm_test y ejecuta el comando en ambas; introduce una barrera o pausa controlada exclusivamente en la prueba para asegurar solapamiento y retírala del código de producto. Guarda el instante de inicio/fin de ambos procesos y el recuento final. El ensayo SQL de MONTAJE permite observar el bloqueo antes de escribir ese comando.

## 4. Cancelación, cambio y doble envío

Cancelación: autentica token privado o cuenta con permiso e identifica sesión sin mutar. Comienza transacción, bloquea sesión y después reserva y relee su vínculo. Si ya estaba cancelada, informa sin otro evento ni otra liberación de plaza. Para una transición nueva exige estado reservada y hora anterior al inicio después de obtener los bloqueos. Si cambió de sesión mientras esperabas, aborta y recarga. Cuando tiene un documento firmado, solo recepción autorizada del centro puede cancelarla antes del inicio: conserva el PDF y marca requiere revisión dentro del mismo cambio. La clave pública no sustituye ese permiso. La siguiente reserva no hereda la firma.

Cambio: recoge ID original y destino; bloquea las sesiones por ID ascendente para que todas las operaciones esperen en el mismo orden; bloquea reserva y comprueba de nuevo el origen. Confirma estado reservada, origen antes de inicio, ausencia de documento firmado conservado, permiso y que destino es compatible y futuro; cuenta ocupación del destino; si falta hueco lanza error antes de actualizar. Actualiza sesion_id dentro de la transacción. Fallo en destino significa conservar la cita original. El mismo destino es un caso sin cambio que se comunica, no otra reserva.

La pareja de backend define cuál es el mensaje ante un formulario antiguo. La pareja de interfaz prepara ese mensaje antes de tener la transacción. QA fuerza: dos cancelaciones, dos cambios de la misma cita y un cambio contra última plaza. El Team Leader no concede el cierre solo porque el recorrido feliz se vea en pantalla.

## 5. Documento firmado recuperable

Contrato mínimo: documento_id, persona_id, reserva_id obligatorio, también en taller, firmante y condición (adulto/representante), texto_version, texto_snapshot, fecha, centro_id, personal_id, png_path, pdf_path, hash_pdf y estado. Autor/fecha los asigna el servidor; no proceden de campos ocultos sin verificar. El texto_snapshot evita que un cambio posterior de plantilla modifique lo firmado. No uses el nombre del usuario como nombre de archivo ni aceptes rutas del navegador.

Secuencia de implementación:

1. Pantalla: obtiene del servidor la identidad/cita autorizada y la versión de texto fija; canvas permite borrar y confirmar. Si no hay trazo, avisa y conserva el resto del formulario.
2. Servidor: vuelve a autorizar cuenta, centro, usuario confirmado activo y reserva reservada o atendida. En menor individual exige representante vinculado; en taller solo adulto. Valida PNG real de hasta 1 MB y máximo 1600×800 y rechaza captura vacía. El booleano `tieneFirma` del cliente es solo ayuda visual.
3. Construye HTML con la plantilla Blade de PDF y el snapshot. Usa variables escapadas para textos; no inserta HTML libre del formulario. CSS A4 sencillo y PNG local; dompdf no tiene que resolver recursos externos.
4. Escribe PNG y PDF en una ruta privada generada por el servidor; comprueba resultado de escritura. En la transacción de confirmación vuelve a bloquear sesión y reserva en el orden común y revalida permiso, centro, identidad, representante, versión y estado. Solo entonces guarda metadatos como firmado; así una cancelación concurrente no se pierde. Si falla, deja pendiente y limpia archivos nuevos, sin tocar originales. Disco y base no comparten una transacción automática.
5. La ruta de lectura comprueba permiso y centro antes de devolver el PDF; administración no los obtiene por su rol administrativo. Si falta el archivo, muestra incidencia recuperable. Corregir registra motivo, conserva PDF anterior como requiere revisión y crea versión pendiente vinculada; solo otra captura y PDF correctos permiten marcarla firmada.

En dompdf se propone `isRemoteEnabled=false`, `isPhpEnabled=false`, `defaultFont=DejaVu Sans` y chroot limitado al directorio de recursos de PDF. GD procesa el PNG. El HTML de la interfaz puede usar layout responsive, pero el PDF utiliza su propia plantilla porque dompdf tiene límites de CSS. Mantener dompdf directo reduce una dependencia de integración; el equipo puede encapsularlo en un servicio pequeño. [Proyecto oficial](https://github.com/dompdf/dompdf).

Cierre de la prueba: PDF abre, contiene mismo texto/firmante/persona/centro, imagen no vacía, hash coincide, persiste tras reiniciar y una cuenta sin permiso no lo recupera. El hash es una comprobación de integridad de archivo; no certifica identidad ni validez jurídica. En el taller, el segundo participante empieza con pantalla y canvas limpios.

## 6. Evitar esperas entre roles

| Necesidad de la tarea | Mientras se completa una dependencia | Qué no se da por validado |
|---|---|---|
| Backend sin pantalla definitiva | Servicio y test con datos sintéticos/contrato aprobado | Usabilidad del formulario |
| Frontend sin servicio | Vista con respuesta simulada éxito/error y contrato fijo | Persistencia/autorización |
| Diseño sin backend | Prototipo HTML, teclado, etiquetas, móvil, estados | Funcionamiento real de reserva |
| Documentación sin respuesta del cliente | Tabla de supuestos didácticos, ejemplos ficticios y pregunta concreta | Regla clínica o jurídica real |
| QA sin incremento integrado | Entradas, resultados esperados, checklist y prueba del ejemplo | Prueba pasada del MVP |
| Integración sin pieza terminada | Migración pequeña, rama de ensayo y comprobación de contrato | Cierre de la tarea ausente |

Al integrar se sustituyen las simulaciones una a una y se repiten las mismas entradas contra servidor. Las respuestas simuladas se identifican y quedan fuera de la demo final del comportamiento comprometido. La revisión exige aportación individual: código/caso/diseño verificable, explicación y una comprobación repetida por otra persona.


## 7. Cómo usar la receta sin inventar identidad ni permisos

Esta receta reserva sobre sesiones ya existentes; no sustituye el controlador ni la EFP. En la entrada pública `persona_id` es null y se guarda un prerregistro validado. Recepción confirma o vincula después. El cliente nunca decide qué ID existente le pertenece. En la ruta de recepción, el servidor resuelve el ID autorizado y rechaza usuario inactivo.

El servidor emite la clave de operación y la vincula a la sesión de navegador. Calcula `payload_hash` sobre campos validados y normalizados en orden estable: sesión, canal y datos del prerregistro o ID resuelto. Repetir el intento con distinto hash devuelve conflicto; la misma petición conserva reserva. La clave privada original se muestra una vez: si se pierde la respuesta, la repetición no puede reconstruirla y deriva a recepción para restablecerla.

No aceptes `payload_hash` calculado por el navegador: incluye todos los campos relevantes y calcula la huella en el servidor. El índice único de `operacion` también protege carreras entre peticiones; si se produce un conflicto de unicidad, deja revertir la transacción y compara fuera de ella la operación existente y su contenido para devolver repetición válida o conflicto, sin crear otra fila. El ejemplo de `laboratorio/plantillas_backend/ClaveGestionDemo.php` emite una clave de 32 bytes aleatorios y conserva su huella; debes integrarlo con autorización y límites de estado, no usarlo como permiso autónomo.

Al cambiar de sesión usa el orden estable de bloqueos y vuelve a comprobar origen, destino, centro y fecha dentro de la transacción. Si existen documentos firmados conservados, rechaza el cambio; solo recepción autorizada puede cancelar esa reserva antes del inicio, conservando sus documentos como requiere revisión. La nueva reserva exige otra firma. Una consulta GET no cancela ni cambia una cita; los cambios requieren confirmación y protección CSRF del framework.

Para firmar, admite únicamente usuario confirmado activo y reserva reservada o atendida del centro autorizado. Revalida el estado bajo bloqueo antes de guardar; si otra petición canceló mientras se dibujaba, rechaza. En este piloto los talleres solo admiten adultos: rechaza `menor_representado` al reservar, antes de consumir plaza. El menor individual requiere representante vinculado y MENOR-DEMO. TALLER-DEMO no habilita resultados y un documento requiere revisión tampoco habilita pruebas nuevas.

La compensación de archivos requiere rutas generadas por el servidor y limpieza de los nuevos archivos si falla la base. No borres archivos de versiones anteriores. Si el PDF no se pudo generar, registra pendiente con error recuperable y permite reintento idempotente. Base de datos y disco no comparten una transacción automática.

## 8. Comprobación de PNG en el servidor

1. Limita el cuerpo antes de decodificar: PNG de hasta 1 MB. Rechaza bytes HTML, datos mal codificados y dimensiones superiores a 1600×800.
2. Decodifica con GD y comprueba tipo PNG real; no confíes en extensión ni en `tieneFirma` enviado por el navegador.
3. Recorre píxeles y busca al menos una cantidad mínima de puntos visibles distintos del fondo. Una imagen completamente transparente o blanca es vacía; dos imágenes de prueba resuelven ese caso.
4. Documenta el umbral técnico de demostración y prueba trazo visible, vacío, transparente, fichero mal formado y exceso de tamaño. No interpreta identidad ni certifica una firma.

## 9. Mini recorrido de Laravel para empezar sin copiar una aplicación completa

Después de crear el esqueleto oficial como explica Montaje del entorno de desarrollo, adapta `laboratorio/plantillas_backend/rutas_demo.php` y copia `demo-centros.blade.php` siguiendo su LEEME. La ruta GET `/demo-centros` devuelve primero un array fijo. Cuando hayas creado la migración y las semillas de centros, cambia la consulta a `DB::table('centros')->orderBy('nombre')->get()` y adapta la vista de arrays a objetos (`$centro->nombre`). Blade muestra valores con `{{ }}`, nunca HTML del usuario sin escapar.

Para un POST de creación utiliza un Form Request o `$request->validate(...)`, formulario con `@csrf` y mensajes `@error`. Conserva entradas permitidas con `old(...)`. Tras guardar, redirige a GET y confirma el resultado; no vuelvas a insertar por una recarga. Una validación del navegador complementa, pero no reemplaza, esa validación del servidor.

Para personal usa `Auth::attempt` con datos validados y `Hash::make` al crear cuentas de demostración; regenera la sesión al entrar. Al salir, cierra autenticación, invalida sesión y regenera token CSRF. Aplica middleware `auth` y una política que compruebe rol/centro antes de leer o modificar. No construyas un sistema de contraseñas propio.

Empieza una prueba de funcionalidad con una cuenta sembrada y una petición permitida; repite con otro rol y espera denegación. En las pruebas de concurrencia separa conexiones de base: un test que las serializa dentro de una sola transacción no demuestra dos intentos simultáneos.


## 10. Ejemplos ejecutables y respuestas de dominio

El paquete incluye `BuscarPorContacto_primera.php` y `CambiarReserva_fallido.php`. Son ejercicios con defecto intencional, aislados en arrays ficticios: muestran entrada, esperado y observado. Ejecútalos con PHP, corrige una copia y contrasta ambos casos. No se conectan a rutas del producto. El primero apoya 2.3.6; el segundo 3.5.6. El ensayo real de concurrencia utiliza `EnsayoCarrera.php`, después del laboratorio SQL y de adaptar el servicio al esquema del equipo.

CrearReserva devuelve `id` y `creada`. Genera la clave privada antes de llamar al servicio y guarda su huella en la nueva fila. Muestra esa clave únicamente si `creada` es true. Si es false, descarta la clave recién generada: devuelve el comprobante sin clave y ofrece recuperación por recepción; nunca muestres una clave cuyo hash no está guardado. Repetir el intento conserva la reserva.

`ConflictoReserva.php` distingue errores de dominio. Cópialo junto al servicio. El controlador JSON convierte el conflicto en HTTP 409 y el cuerpo acordado. Las validaciones de campos siguen devolviendo 422; adapta también su cuerpo al contrato. La versión HTML puede redirigir con errores conservando entradas permitidas, sin confundir una redirección con la respuesta JSON probada. Ejemplo de adaptación, dentro del controlador y después de autorización/validación:

```php
try {
    $resultado = $servicio->ejecutar($sesionId, $personaId, $datos, $payloadHash, $operacion, $tokenHash);
    // Construye data con el contrato y muestra clave solo si creada es true.
} catch (\App\Services\ConflictoReserva $e) {
    return response()->json(['ok' => false, 'error' => ['codigo' => $e->codigo, 'mensaje' => $e->getMessage()]], 409);
}
```

### Reintento de captura y corrección posterior

El servidor crea o recupera un documento pendiente autorizado antes de dibujar y entrega su ID. Una reserva tiene una serie documental activa; las correcciones son nuevas versiones de esa serie. Añade `actual` boolean, verdadero solo en la versión actual, y un índice único parcial de reserva donde actual=true. Antes de crear una nueva versión, bloquea sesión, reserva y documento en ese orden; marca la anterior actual=false y requiere revisión e inserta la nueva pendiente actual=true dentro de la misma transacción.

Para capturar, bloquea el documento pendiente dentro del mismo orden común, relee estado y permisos y calcula en servidor la huella de captura sobre PNG validado y snapshot. Un reenvío con el mismo ID/huella ya firmado devuelve el mismo documento; uno diferente pide iniciar la corrección, no sobrescribe. Dos envíos concurrentes no crean otra versión. Solo confirma firmado después de archivos recuperables; si falla, conserva pendiente y limpia temporales nuevos. La corrección editorial completa pertenece a S7; en S6 comprueba reintento, ausencia de duplicado y conservación del original.

```sql
CREATE UNIQUE INDEX documentos_una_actual_por_reserva
ON documentos (reserva_id) WHERE actual = true;
```

La migración se aplica sobre el esquema del equipo con la columna actual creada, no sobre las dos tablas reducidas del laboratorio SQL. El índice no sustituye bloqueo, autorización ni validación. Ensaya doble envío, fallo de PDF y nueva versión: una sola actual, anterior conservada y ninguna firma falsa.
