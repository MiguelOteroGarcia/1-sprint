# Plantillas de documentos de demostración

FM-WEB-01 · Recurso del alumno · Tres textos fijos para construir y comprobar el circuito de rúbrica y PDF.

Estos textos se han inventado para la práctica. No reproducen ni validan las cláusulas del cliente, no acreditan consentimiento de una persona real y no convierten una rúbrica en firma certificada. Las dudas jurídicas y sanitarias del cliente siguen fuera del ejercicio. Utiliza nombres, contactos, actividades y trazos ficticios.

El objetivo técnico es mostrar una identidad y un texto, recoger un trazo, guardar una copia estable y recuperarla con autorización. El núcleo de 370 horas incluye las tres plantillas. En 500 horas ADULTO-DEMO o MENOR-DEMO pueden ser una precondición didáctica de resultados; TALLER-DEMO nunca la sustituye.

## 1. Qué eliges antes de dibujar

| Plantilla | Usuario atendido | Firmante de demostración | Condiciones |
| --- | --- | --- | --- |
| ADULTO-DEMO | Tipo `adulto`, confirmado | El propio usuario ficticio | Reserva individual de su centro, reservada o atendida. |
| MENOR-DEMO | Tipo `menor_representado`, confirmado | Representante ficticio identificado aparte | Relación activa comprobada por recepción y reserva individual reservada o atendida. |
| TALLER-DEMO | Tipo `adulto`, confirmado | Cada asistente ficticio por separado | Reserva del taller, reservada o atendida; una serie documental por usuario/reserva, con versiones conservadas. |

El tipo se declara en los casos; no calcules una edad legal para decidir quién firma. En este piloto un menor representado no reserva taller: el servidor lo explica y no ocupa plaza. La decisión de admitir menores en talleres reales queda para el cliente. Una reserva cancelada o no presentada no admite una nueva firma.

Una persona que llegue sin reserva necesita que recepción cree una reserva presencial antes del inicio y con plazas. El documento no crea una plaza extra ni justifica saltarse el control de agenda. Recepción debe tener el centro asignado; administración por sí sola no obtiene permiso para leer o firmar documentos.

## 2. Cómo guardar y presentar los textos

Cada código tiene versiones publicadas inmutables. Empieza por versión 1. El servidor elige la versión vigente y el contexto autorizado. La interfaz muestra título, usuario, firmante, centro, servicio, sesión y texto completo antes del botón de confirmar.

Las expresiones entre dobles llaves son marcadores de datos controlados. Sustitúyelos solo con campos de la lista de este recurso; escapa sus valores al construir HTML. No evalúes código ni aceptes una plantilla HTML suministrada por el navegador. Un campo oculto con otro usuario o versión no cambia el contexto autorizado.

Guarda dos cosas distintas: el texto publicado con su versión y huella, y `texto_snapshot`, que contiene el texto final mostrado con los marcadores resueltos. El PDF usa ese snapshot, no vuelve a consultar el nombre actual o la última versión de la plantilla. Cambiar una ficha no reescribe documentos anteriores.

### 2.1 Campos permitidos en las plantillas

| Marcador | Origen y ejemplo ficticio |
| --- | --- |
| usuario_codigo | Código estable: U001. |
| usuario_nombre_completo | Nombre y apellidos de la ficha confirmada: Alba Ejemplo Norte. |
| representante_codigo | Código separado: R001; solo MENOR-DEMO. |
| representante_nombre_completo | Nombre y apellidos del representante validado: Rita Ejemplo Sur. |
| centro_codigo, centro_nombre | Centro de la reserva: C01, Centro Norte. |
| servicio_codigo, servicio_nombre | Servicio de la sesión: SV01, atención individual; o SV02, taller. |
| sesion_codigo | Sesión concreta: SE-DEMO-01. |
| sesion_inicio_local | Instante mostrado con zona/desplazamiento: 10/06/2030 10:00, Europe/Madrid, UTC+02:00. |
| reserva_codigo | Código de la reserva autorizada: RV-DEMO-001. |

La versión, fecha de firma y personal que registra aparecen en el encabezado o pie del documento y se asignan en servidor. No incluyas teléfono, correo, clave privada de reserva, credencial ni valores de pruebas en estos textos. Los códigos de ejemplo no son permisos de acceso.

## 3. ADULTO-DEMO · Versión 1

Título visible: **Registro de participación individual de demostración**.

Texto íntegro que debes almacenar:

> SIMULACIÓN EDUCATIVA. DATOS Y RÚBRICA FICTICIOS.
>
> Este documento forma parte de una práctica de desarrollo web de Fundación. Registra el recorrido de la persona ficticia {{usuario_nombre_completo}}, con código {{usuario_codigo}}, en el centro {{centro_nombre}} ({{centro_codigo}}).
>
> La actividad de ejemplo es {{servicio_nombre}} ({{servicio_codigo}}), sesión {{sesion_codigo}}, prevista para {{sesion_inicio_local}}. La reserva de demostración es {{reserva_codigo}}. El usuario atendido y el firmante de este ejercicio son la misma persona ficticia.
>
> Antes de dibujar se revisan el nombre, el centro, la actividad y el texto completo. Si un dato no coincide con el caso preparado, se vuelve al registro para corregirlo antes de confirmar. El botón Borrar permite eliminar el trazo de ensayo y repetirlo.
>
> Al confirmar, la aplicación conserva una copia de este texto, sus datos de contexto, la versión de plantilla y la rúbrica de demostración. Esta práctica permite comprobar que el documento se recupera sin cambiar su contenido. No representa una autorización de uso real ni acredita identidad, consentimiento o validez jurídica.

Firmante mostrado: `{{usuario_nombre_completo}} · usuario {{usuario_codigo}}`. El representante queda nulo. La imagen pertenece al ensayo de este usuario y esta reserva; no se reutiliza en otro documento.

## 4. MENOR-DEMO · Versión 1

Título visible: **Registro individual con representante de demostración**.

Texto íntegro que debes almacenar:

> SIMULACIÓN EDUCATIVA. DATOS Y RÚBRICA FICTICIOS.
>
> Este documento forma parte de una práctica de desarrollo web de Fundación. El usuario atendido de este caso es {{usuario_nombre_completo}}, con código {{usuario_codigo}} y tipo de atención menor representado. La persona que realiza la rúbrica ficticia es {{representante_nombre_completo}}, con código de representante {{representante_codigo}}.
>
> El ejercicio corresponde al centro {{centro_nombre}} ({{centro_codigo}}), a la actividad {{servicio_nombre}} ({{servicio_codigo}}) y a la sesión {{sesion_codigo}}, prevista para {{sesion_inicio_local}}. La reserva de demostración es {{reserva_codigo}}.
>
> Antes de dibujar se comprueba que usuario y representante son dos identidades distintas y que la relación indicada coincide con el caso preparado por recepción. La fecha de nacimiento no se utiliza para deducir una regla jurídica de representación. Si la relación o los datos no coinciden, se vuelve al registro y no se confirma el documento.
>
> Al confirmar se conserva una copia del texto mostrado, la identidad del usuario atendido, la identidad del representante firmante y la rúbrica de ensayo. El documento permite comprobar técnicamente esa separación. No acredita parentesco, representación legal, consentimiento ni validez jurídica de ninguna persona real.

Firmante mostrado: `{{representante_nombre_completo}} · representante {{representante_codigo}} de {{usuario_codigo}}`. La pantalla mantiene visible al usuario atendido en un bloque separado. No sustituyas su nombre por el del representante ni crees una cuenta de personal para el representante.

## 5. TALLER-DEMO · Versión 1

Título visible: **Registro de asistencia a taller de demostración**.

Texto íntegro que debes almacenar:

> SIMULACIÓN EDUCATIVA. DATOS Y RÚBRICA FICTICIOS.
>
> Este documento forma parte de una práctica de desarrollo web de Fundación. Registra el caso individual del asistente ficticio {{usuario_nombre_completo}}, con código {{usuario_codigo}} y tipo de atención adulto. El propio asistente ficticio figura como firmante de este ejercicio.
>
> La actividad es {{servicio_nombre}} ({{servicio_codigo}}), sesión {{sesion_codigo}}, prevista para {{sesion_inicio_local}}, en el centro {{centro_nombre}} ({{centro_codigo}}). La reserva de esta persona es {{reserva_codigo}}. Este registro no incluye las identidades ni las rúbricas de otros asistentes.
>
> Antes de dibujar se revisan persona, reserva y sesión. Si falta la reserva o los datos no coinciden, se vuelve al registro; la firma no crea plazas adicionales. Cada asistente tiene su propio documento. Después de guardar, la aplicación prepara una pantalla y un lienzo vacíos para el siguiente caso.
>
> La copia conservada permite comprobar identidad, texto, versión y recuperación del documento de taller. Este documento no habilita el registro de resultados de pruebas del itinerario de 500 horas. No representa una autorización real de asistencia ni acredita consentimiento, identidad o validez jurídica.

Firmante mostrado: `{{usuario_nombre_completo}} · asistente {{usuario_codigo}}`. Una sesión con tres plazas puede tener tres reservas y documentos diferentes, nunca una imagen de firma común. El número de documentos no modifica la ocupación.

## 6. Ejemplo completo de contexto y snapshot

Los siguientes nombres y códigos forman un caso de ensayo. No prueban que exista un PDF. Configura el reloj en 2030-06-10 y prepara la reserva antes de ejecutar. Los IDs internos se resuelven desde las semillas; no dependen de su número de fila.

Contexto de ADULTO-DEMO preparado antes de confirmar:

```json
{
  "documento_codigo": "DOC-DEMO-001",
  "documento_version": 1,
  "plantilla_codigo": "ADULTO-DEMO",
  "plantilla_version": 1,
  "estado": "pendiente",
  "usuario": {"codigo": "U001", "nombre": "Alba", "apellidos": "Ejemplo Norte", "tipo_atencion": "adulto"},
  "firmante": {"tipo": "usuario", "codigo": "U001", "nombre_completo": "Alba Ejemplo Norte"},
  "representante": null,
  "centro": {"codigo": "C01", "nombre": "Centro Norte"},
  "servicio": {"codigo": "SV01", "nombre": "atención individual"},
  "sesion_codigo": "SE-DEMO-01",
  "sesion_inicio": "2030-06-10T10:00:00+02:00",
  "zona": "Europe/Madrid",
  "reserva_codigo": "RV-DEMO-001",
  "personal_codigo": "P01",
  "firmado_at": null,
  "png_path": null,
  "pdf_path": null,
  "hash_pdf": null
}
```

Ejemplo de `texto_snapshot` final para ese contexto; conserva exactamente estos párrafos resueltos, sin marcadores pendientes:

> SIMULACIÓN EDUCATIVA. DATOS Y RÚBRICA FICTICIOS.
>
> Este documento forma parte de una práctica de desarrollo web de Fundación. Registra el recorrido de la persona ficticia Alba Ejemplo Norte, con código U001, en el centro Centro Norte (C01).
>
> La actividad de ejemplo es atención individual (SV01), sesión SE-DEMO-01, prevista para 10/06/2030 10:00, Europe/Madrid, UTC+02:00. La reserva de demostración es RV-DEMO-001. El usuario atendido y el firmante de este ejercicio son la misma persona ficticia.
>
> Antes de dibujar se revisan el nombre, el centro, la actividad y el texto completo. Si un dato no coincide con el caso preparado, se vuelve al registro para corregirlo antes de confirmar. El botón Borrar permite eliminar el trazo de ensayo y repetirlo.
>
> Al confirmar, la aplicación conserva una copia de este texto, sus datos de contexto, la versión de plantilla y la rúbrica de demostración. Esta práctica permite comprobar que el documento se recupera sin cambiar su contenido. No representa una autorización de uso real ni acredita identidad, consentimiento o validez jurídica.

Al guardar correctamente, el servidor completa instante real de ensayo, ruta privada de PNG, ruta privada de PDF y hash calculado del PDF. Solo entonces cambia a `firmado`. El hash del texto publicado, el hash del texto resuelto y el hash del PDF no son intercambiables: se calculan sobre contenidos distintos. No escribas una huella ficticia para hacer pasar el control.

## 7. Modelo de PDF de la aplicación

Construye una plantilla Blade A4 sencilla. El orden siguiente permite revisar todos los casos sin buscar campos en lugares diferentes:

1. Encabezado: «SIMULACIÓN EDUCATIVA», título, código de documento, versión de documento y código/versión de plantilla.
2. Contexto: usuario atendido, firmante y su tipo, centro, servicio, sesión con fecha/zona y reserva.
3. Texto: `texto_snapshot` completo con párrafos legibles. Si continúa en otra página, conserva el orden sin recortar.
4. Rúbrica: PNG ficticio con proporción conservada y etiqueta del firmante correcto.
5. Pie: instante de firma, cuenta de personal que registró la acción y recordatorio de demostración educativa.

Para el ejemplo adulto, el encabezado será «DOC-DEMO-001 · documento v1 · ADULTO-DEMO v1»; el contexto mostrará U001 y firmante U001. Para MENOR-DEMO debe mostrar U002 y firmante R001 por separado. Para taller identifica también la sesión y la reserva de ese asistente.

El PDF de la aplicación es distinto del PDF de evidencia de la tarea. El primero conserva el documento y su trazo; el segundo explica código, casos y resultados al revisor. No impongas al documento una reducción de texto para cumplir el máximo de tres páginas de la evidencia.

Usa texto escapado, recursos locales y rutas privadas generadas en servidor. No cargues imágenes externas o HTML libre. La descarga pasa por un controlador que comprueba cuenta, permiso y centro. Conocer DOC-DEMO-001 o el ID interno no permite recuperar el archivo sin esa autorización.

## 8. Secuencia de captura, guardado y recuperación

1. Abre el contexto autorizado. Comprueba identidad confirmada, centro, reserva y plantilla; muestra el texto completo y el firmante.
2. Ensaya confirmar sin trazo. El navegador informa del vacío, pero también debes enviar una imagen vacía al servidor en la prueba: debe rechazarla.
3. Dibuja un trazo ficticio con ratón, bórralo y repite. Conserva proporción al redimensionar; identifica emulación táctil como emulación, no como tableta real.
4. Envía el PNG. El servidor valida contenido real, hasta 1 MB, máximo 1600 × 800 y ausencia de vacío/imagen transparente. No confía en `tieneFirma` ni en la extensión.
5. Vuelve a comprobar permiso, persona, representante, versión y reserva justo antes de persistir. Si otra petición la canceló mientras se dibujaba, rechaza el guardado sin documento firmado.
6. Genera el PDF desde el snapshot y conserva imagen/PDF fuera de público. Solo marca firmado cuando los archivos y metadatos están guardados; compensa un fallo de base retirando únicamente archivos nuevos.
7. Recupera mediante la ruta autorizada y compara el PDF con el contexto original. Reinicia la aplicación y vuelve a descargarlo; conservar una captura de pantalla no demuestra recuperación.
8. En taller pulsa siguiente y limpia datos/lienzo; retrocede con el navegador y comprueba que no reaparece la información del asistente anterior. No reutilices el estado de la página anterior para el nuevo caso.

Si falla la generación de PDF, queda pendiente y se explica cómo reintentar. Limpia los temporales nuevos sin borrar versiones anteriores. Base y disco no comparten una transacción automática: un registro firmado apuntando a un archivo ausente incumple el criterio de cierre.

## 9. Versiones y correcciones

Cambiar el texto publicado crea plantilla v2 y conserva v1. Los documentos firmados con v1 mantienen texto, identidad y archivo anteriores. Para un documento nuevo, el servidor usa la versión publicada vigente. Si el navegador presenta una versión antigua mientras se dibuja, informa del cambio y exige revisar el texto vigente antes de confirmar.

Corregir un documento firmado exige motivo. Marca el anterior `requiere_revision`, conserva su PDF y crea documento versión 2 en estado pendiente con referencia al anterior. Prepara el nuevo contexto, muestra otra vez el texto y captura otra rúbrica; solo entonces podrá quedar firmado. Si falla, queda pendiente y ninguno de esos dos documentos habilita una prueba nueva. Los resultados históricos ya existentes permanecen conservados.

Ejemplo: DOC-DEMO-001 v1 quedó vinculado a una actividad errónea por el ensayo de un defecto. Conserva ese original, registra «Corrección de actividad de demostración» y prepara la versión siguiente mediante la operación autorizada. No edites a mano el PDF ni traslades la imagen a otra reserva. Si la reserva necesita cambiar, aplica la regla de agenda: un documento firmado conservado impide reprogramarla.

Una cancelación antes del inicio con documento firmado se realiza por recepción: la reserva queda cancelada, el documento requiere revisión y su PDF sigue disponible al personal autorizado. La nueva reserva, si se crea, es independiente y requiere otro documento. Después del inicio no se permite cancelar o cambiar para reconstruir el pasado.

## 10. Casos y criterios para demostrar el resultado

| Caso | Preparación y acción | Resultado esperado que debe observarse |
| --- | --- | --- |
| Vacío | PNG blanco y PNG transparente enviados al servidor. | Rechazo; no aparece documento firmado. |
| Adulto | U001 confirmado, reserva individual válida, ADULTO-DEMO. | Usuario y firmante U001; texto completo y PDF recuperable. |
| Representado | U002 confirmado, R001 validado y reserva individual. | PDF muestra U002 atendido y R001 firmante separados. |
| Taller consecutivo | U003 y U004 adultos, dos reservas de la misma sesión. | Dos documentos distintos; segundo lienzo y contexto empiezan limpios. |
| Taller de menor | Reserva de taller con menor_representado. | Rechazo comprensible sin plaza consumida; no crear una plantilla adicional. |
| Reserva no válida | Cancelada o no presentada al confirmar el trazo. | Servidor rechaza firma; no deja estado firmado. |
| Contexto alterado | Cambiar persona_id o centro_id enviado por el navegador. | Rechazo o resolución del contexto autorizado, sin documento de otra persona. |
| PDF fallido | Forzar un fallo controlado del generador en fm_test. | Pendiente y reintento posible; sin archivo nuevo huérfano ni original borrado. |
| Descarga indebida | Cuenta de C02 pide documento exclusivo de C01. | Denegación sin contenido del PDF. |
| Cambio de texto | Publicar v2 y abrir el PDF firmado con v1. | El original conserva exactamente su texto y versión. |
| Corrección | Crear documento v2 desde uno firmado con motivo. | Original conservado y requiere revisión; nuevo pendiente hasta otra firma. |
| Requisito de resultado | Intentar usar TALLER-DEMO para una prueba de 500 h. | Rechazo aunque el documento esté firmado. |

Los casos se preparan con relojes y datos controlados. Usa fm_test para pruebas destructivas; no fuerces fallos sobre los documentos de la demostración compartida. Un estado pendiente o una simulación útil no se presenta como prueba superada de recuperación real.

Modelo de evidencia de la tarea, integrado en el PDF que solicita su ficha:

| Dato | Ejemplo de contenido que debes completar |
| --- | --- |
| Autores y revisión | Persona A: captura y validación; persona B: PDF y ensayo de fallo; revisor: persona que repite la comprobación. |
| Versión | Commit y archivos de controlador, plantilla PDF y prueba; versión de texto utilizada. |
| Caso y entrada | U002/R001, reserva y centro ficticios; lienzo vacío y después trazo visible. |
| Esperado | Vacío rechazado; PDF válido separa usuario y representante y se recupera tras reinicio. |
| Observado | Estado HTTP, estado documental y fragmento/captura legible de la ejecución real. No rellenar antes de ejecutar. |
| Corrección | Defecto observado, modificación concreta y resultado de repetir el mismo caso. |

Criterios binarios de revisión: vacío rechazado también en servidor; persona/firmante/centro/texto coinciden; PDF recuperable tras reiniciar; descarga sin permiso denegada; fallo de PDF no deja firmado; corrección conserva original y motivo. Una apariencia cuidada no compensa fallar uno de estos controles.

## 11. Si algo no coincide

Compara primero el contexto del servidor, el texto mostrado y el snapshot guardado. Si solo falla el PDF, reduce la plantilla a texto y un PNG ficticio local y consulta Recetas de backend y firma; registra mensaje, archivo, esperado y observado para tu pareja y el coordinador.

Si falta una decisión del cliente, utiliza únicamente la regla didáctica explícita del guion y registra la consulta. No cambies textos para afirmar una autorización real. Si no hay tableta, continúa con ratón y emulación y deja la comprobación del dispositivo físico identificada como pendiente.


## Captura repetida

Usa el mismo ID pendiente emitido por servidor al reintentar. El servidor bloquea y compara la huella de captura: dos envíos iguales dejan un documento firmado; cambiar el contenido de uno firmado inicia corrección con nueva versión. El índice de versión actual y los pasos están en Diccionario y Recetas.
