# Casos de aceptación y regresión

FM-WEB-01 · Guía del alumno para preparar, ejecutar y repetir comprobaciones con datos sintéticos.

La batería [08_CASOS_ACEPTACION.csv](08_CASOS_ACEPTACION.csv) contiene **61 casos**, QA-001–QA-061. Los 48 iniciales se han concretado con el guion actual y se han añadido 13 casos de fallo documental, ocupación, permisos y estados. **Todos están NO-VERIFICADOS como ejecución de la aplicación.** El texto de «resultado esperado» describe lo que debe ocurrir; no afirma que ya haya ocurrido.

Abre el archivo con UTF-8 y separador punto y coma. Ese separador corresponde a esta batería de casos. El CSV que debe producir la aplicación usa UTF-8, coma y punto decimal; no confundas los dos formatos.

## 1. Empieza por el caso que corresponde a tu tarea

Lee precondición, pasos, resultado esperado y evidencia. Añade versión, persona ejecutora, fecha real, entorno y salida observada a tu matriz de ejecución. Conserva el CSV como catálogo de casos; registra los resultados de cada versión en un archivo de tu equipo para no confundir pruebas de días o versiones diferentes.

En 370 h, construye y comprueba registro, agenda, documentos, historial documental y CSV de ocupación. En 500 h, mantén esos casos y añade resultados fijos. Si falla el recorrido nuclear al terminar S6, el coordinador prioriza su corrección antes de activar la ampliación: preparar datos o pantallas de resultados no acredita que esa ampliación esté terminada.

| Grupo | Casos de la batería | Cuándo y quién interviene |
| --- | --- | --- |
| Arranque y aprendizaje | QA-001–QA-003 | Desde S1; cada persona ejecuta la parte individual y la pareja verifica el entorno. |
| Identidad y registro | QA-004–QA-009, QA-057, parte de QA-059 | Al integrar registro; recepción y datos sintéticos. |
| Agenda y clave privada | QA-010–QA-021, QA-051, QA-058, parte de QA-059 | Al integrar reserva/cambio; dos procesos para última plaza. |
| Permisos del núcleo | QA-022–QA-023, QA-055–QA-056 | Desde el acceso de personal y cada vez que se añade una ruta. |
| Documentos y firma | QA-024–QA-032, QA-049, QA-052–QA-054, QA-060–QA-061 | Antes de dar por completo el núcleo de S6 y tras cualquier corrección. |
| Ocupación y uso | QA-043–QA-044, QA-050 | En ambos planes; exportación operativa, teclado y tamaños. |
| Tres pruebas fijas | QA-034–QA-042 y variante 500 de QA-043/QA-045 | Solo 500 h, después del núcleo; registro, historial y CSV de resultados. |
| Entrega y aportación | QA-045–QA-046 | Durante el trabajo y al reproducir la versión final. |
| Comprobaciones externas al ensayo local | QA-033, QA-047–QA-048 | Coordinador o responsable con dispositivo/plataforma disponibles; conserva NO-VERIFICADO hasta ensayar. |

QA-033 comprueba una tableta física identificada. Ratón o emulación no la cierran. QA-047 comprueba el acceso real como alumno a los materiales y QA-048 compara la práctica importada con el documento. Una revisión local de archivos no sustituye ninguna de esas tres comprobaciones ni certifica la plataforma.

## 2. Prepara datos pequeños y un entorno separado

Usa C01 Centro Norte y C02 Centro Sur; U001 adulto confirmado, U002 menor representado por R001, y U003/U004 distintos con contacto compartido. Las cuentas de personal se crean según el recurso de montaje: recepción, profesional y administración con centros y permisos explícitos. Una persona atendida no es una cuenta del personal.

Los datos iniciales están en `laboratorio/datos/usuarios.csv`, `representantes.json`, `prerregistros.json`, `sesiones.json`, `resultados.json` y `diccionario_pruebas.csv`. Son entradas sintéticas que debes adaptar al esquema acordado. No son una base de producción ni prueba de que el servidor esté implementado.

Antes de una prueba que reinicie datos, confirma la conexión efectiva a `fm_test`, separada de `fm_demo`. Revisa `.env.testing` y el nombre de base mostrado por el diagnóstico. Usa un directorio privado de archivos de prueba distinto de la demo. Si no puedes confirmar el destino, detén esa prueba y pide ayuda; puedes preparar entradas y esperado sin borrar nada.

Las sesiones se desplazan a fechas futuras respecto al reloj de prueba. Registra el instante de ese reloj y la zona Europe/Madrid. Para límite de inicio ejecuta una variante antes y otra desde el inicio. Para cambio de hora usa una hora inexistente y otra ambigua de una transición real de esa zona: la inexistente se rechaza y la ambigua exige desplazamiento UTC explícito. No arregles todas las fechas sumando una hora fija.

No necesitas datos reales para forzar fallos. Usa contactos reservados de ejemplo, firmas inventadas, identificadores de demo y un mecanismo de fallo de PDF preparado en la base de pruebas. No borres el PDF compartido para simular un error.

## 3. Ejecuta una comprobación en seis pasos

1. Identifica el caso, la versión y su regla. Escribe qué campo, estado o permiso vas a observar.
2. Carga solo los datos necesarios y comprueba la precondición. Guarda IDs y estado previo si la acción modifica registros.
3. Ejecuta exactamente los pasos. Registra petición o comando, cuenta sintética y salida; evita copiar credenciales o claves privadas.
4. Compara con el esperado y consulta el estado persistido o el archivo. Un mensaje de éxito no demuestra que exista un PDF ni que quede una sola reserva.
5. Si falla, registra una incidencia mínima con impacto y entrega el caso a la pareja responsable. Después del cambio repite ese caso y otro relacionado.
6. Pide a una persona distinta de la autora repetir el cierre y prepara el PDF de evidencia de la tarea, hasta tres páginas y 10 MB.

Usa los estados de comprobación con una condición y una fuente: **VERIFICADO** cuando la ejecución documentada satisface el esperado; **ROTO** cuando la ejecución contradice la regla; **NO-VERIFICADO** cuando falta ejecutar; **DESCONOCIDO** cuando no se dispone del dato necesario; **SUPERADO** para una evidencia sustituida por una versión posterior. «Simulado» describe el tipo de ensayo, no permite declarar verificado un servidor real.

En el tablero, marca bloqueante si hay sobreventa, pérdida de cita, exposición entre centros/roles, identidad documental incorrecta, pérdida de originales o imposibilidad de reproducir el núcleo. Una incidencia mayor impide otro comportamiento comprometido; una mejora permite usarlo pero merece ajuste. No compenses un bloqueo crítico con una ampliación visual.

## 4. Relaciona los casos con R01–R18

R01–R18 son reglas de cierre del guion. QA-001–QA-060 son casos concretos: varios casos pueden comprobar una misma regla.

| Regla | Qué debe observarse | Casos principales |
| --- | --- | --- |
| R01 | Contacto compartido sin fusionar identidades. | QA-005 |
| R02 | Búsqueda pública sin revelar registros internos. | QA-009 |
| R03 | Confirmación explícita y trazable, sin firma de identidad pendiente. | QA-006, QA-053, QA-057 |
| R04 | Dos procesos compiten por una plaza y solo uno la obtiene. | QA-011 |
| R05 | Cancelar dos veces libera una plaza una sola vez. | QA-013–QA-014 |
| R06 | Cambio fallido conserva origen, ID y plaza. | QA-015–QA-016 |
| R07 | Centro o clave no autorizados no leen ni modifican objetos protegidos. | QA-018, QA-020–QA-023, QA-056 |
| R08 | Estados, inicio y zona horaria tienen negativos repetibles. | QA-017, QA-019, QA-059 |
| R09 | Firma vacía rechazada también en servidor. | QA-024, QA-054 |
| R10 | Adulto, menor y taller conservan usuario y firmante correctos. | QA-025–QA-028, QA-052–QA-053 |
| R11 | Taller limpia datos y trazo entre asistentes. | QA-027 |
| R12 | PDF recuperable conserva texto/versión y acceso autorizado. | QA-030–QA-031, QA-060 |
| R13 | Fallo de PDF no deja un documento firmado. | QA-049 |
| R14 | Corrección conserva original y motivo. | QA-029 |
| R15, solo 500 | Unidades, nulos y negativos permitidos se conservan. | QA-036–QA-039 |
| R16, solo 500 | Recepción no lee resultados por petición directa. | QA-034–QA-035 |
| R17, solo 500 | Corregir conserva original, autor y referencia. | QA-040 |
| R18, solo 500 | Vista/CSV coinciden en filtros y permisos; variante sin nombre real. | QA-041–QA-043 |

## 5. Comprueba los puntos que suelen dar falsos positivos

**Última plaza.** Sigue el ensayo con dos terminales de [Montaje del entorno de desarrollo](04_MONTAJE.md) y luego el servicio real de [Recetas de backend y firma](07_RECETAS.md). Registra solapamiento, salidas y una sola fila persistida. El ensayo SQL explica el bloqueo; no prueba automáticamente el servicio del equipo. Dos pestañas de un servidor que atiende en serie tampoco acreditan concurrencia.

**Repetición y cambio.** La misma clave de intento y contenido devuelve la reserva existente; cambiar su contenido se rechaza. Reprogramar conserva el ID y bloquea origen/destino en orden. Si el destino falla, el origen no cambia. Una reserva con documento firmado no se reprograma; cancelarla antes del inicio conserva PDF y marca requiere revisión. La nueva reserva es independiente y necesita otra firma.

**Firma y PDF.** Verifica usuario confirmado, centro, reserva reservada/atendida, tipo y firmante antes de guardar. PNG con trazo, hasta 1 MB y 1600×800, validado por contenido en servidor; no aceptes HTML/SVG por llamarse PNG. Solo hay estado firmado cuando texto, imagen y PDF recuperable están guardados. Tras corregir, el original conserva sus archivos y pasa a requiere revisión; la nueva versión queda pendiente hasta otra firma válida. Taller admite solo adultos en esta demo.

**Permisos.** Cambia el ID o centro en la petición, aunque el botón esté oculto. Administración no recibe documentos/resultados por gestionar cuentas. Profesional accede a identidad mínima relacionada con su centro sin contactos; autoría no abre otros centros. Desactivar una cuenta debe impedir su acceso. La clave de reserva se genera con al menos 32 bytes aleatorios, se entrega una vez y solo se guarda su huella; no la copies a la evidencia.

**Resultados en 500 h.** Bioimpedancia tiene nueve medidas; bioquímica cuatro más código postal y fecha del informe; densitometría siete más EVA. Tipo, fecha no futura y al menos una medida son obligatorios. Los opcionales vacíos son nulos; no son cero. Usa hasta nueve cifras totales y tres decimales finitos, porcentajes 0–100 y EVA 0–10; T/Z admiten negativos. Las unidades ausentes en fuente se etiquetan `unidad_demo`; no se calculan IMC, TMB o FRAX. Se exige profesional del centro y ADULTO-DEMO/MENOR-DEMO adecuado, firmado y sin revisión; TALLER-DEMO no habilita resultados.

**CSV nuclear.** Recepción exporta ocupación agregada por centro/sesión con centro, servicio, sesión, inicio, capacidad, reservadas, atendidas, no presentadas, canceladas y libres. Una sesión de capacidad 5 con 2 reservadas, 1 atendida, 1 no presentada y 1 cancelada tiene 1 libre. No hay nombre, contacto, código de usuario, documentos ni tokens. Este ejemplo de capacidad 5 es una sesión de prueba específica; no cambia la capacidad predeterminada del servicio.

**CSV de resultados en 500 h.** Exige profesional del centro y permiso `exportar_resultados`. Exporta solo versiones vigentes del filtro, por tipo, con unidades e ID de versión. La variante sin nombre elimina de verdad nombre y apellidos; conserva código de usuario e ID de versión y sigue siendo identificable. No la presentes como anónima.

**Formato del CSV de la aplicación.** Abre UTF-8, coma y punto decimal, indicando como texto el código postal para conservar ceros iniciales. Escapa comillas, delimitadores y saltos. Los textos de centro/servicio que empiezan con signos de fórmula deben abrirse como texto; un T score negativo sigue siendo número. Registra la herramienta usada y compara el archivo bruto con su vista. Ningún CSV lleva secretos ni imágenes de firma.

## 6. Usa las herramientas que ya están disponibles

| Archivo o guía | Qué permite comprobar | Límite que debes registrar |
| --- | --- | --- |
| [laboratorio/index.html](laboratorio/index.html) | Etiquetas, validación local, estados y lienzo con navegador. | Maqueta sin persistencia ni permisos compartidos. |
| [laboratorio/reglas.js](laboratorio/reglas.js) | Una función pequeña con entradas conocidas. | Regla local; no acredita la validación del servidor. |
| [laboratorio/pruebas_reglas.js](laboratorio/pruebas_reglas.js) | Pruebas locales si ya tienes Node; ejecuta `node pruebas_reglas.js` desde `laboratorio`. | No instales Node para poder empezar; puedes recorrer ejemplos en navegador. |
| [Montaje del entorno de desarrollo](04_MONTAJE.md) | Entorno, base aislada, diagnóstico y ensayo SQL con dos conexiones. | Registra comandos y versiones realmente ejecutados. |
| [Recetas de backend y firma](07_RECETAS.md) | Adaptar transacciones, firma, archivos privados y recuperación. | Ejemplos para construir; no son funcionalidades ya entregadas por el equipo. |
| [Ayuda y registro de evidencias](09_EVIDENCIAS.md) | Plantilla de bloqueo y PDF con comparación antes/después. | Completa obtenido después de ejecutar. |

## 7. Copia una matriz de ejecución sin inventar resultados

Ejemplo rellenado con preparación concreta y resultados pendientes. No documenta una prueba realizada:

| Campo | Ejemplo preparado |
| --- | --- |
| Caso | QA-011 · Última plaza; R04. |
| Itinerario y tarea | Ambos; tarea de concurrencia de agenda o regresión del sprint actual. |
| Versión / fecha / ejecutor | NO-VERIFICADO: completar al iniciar la ejecución. |
| Entorno | Previsto: base `fm_test`, dos procesos independientes; disponibilidad NO-VERIFICADA. |
| Datos iniciales | Sesión sintética futura con capacidad 1; U001 y U003; claves de intento distintas. |
| Acción | Invocar servicio de reserva en dos procesos con solapamiento controlado. |
| Esperado | Una reserva persistida, un éxito y un rechazo sin plazas. |
| Obtenido | NO-VERIFICADO: no se ha ejecutado. |
| Evidencia | Pendiente: instantes, salidas de ambos procesos y consulta del recuento. |
| Incidencia / siguiente acción | Confirmar conexión a base aislada y preparar el comando de ensayo. |
| Revisor / repetición | Pendiente: otra persona repetirá el caso con la versión identificada. |

Para registrar un defecto copia: caso, versión, entorno, datos iniciales, pasos mínimos, esperado, obtenido, impacto, archivo/petición, persona responsable, siguiente acción y resultado de la repetición. No escribas una fecha futura como si fuera ejecución; acuerda el siguiente punto de revisión con el coordinador.

## 8. Pide ayuda con una entrada concreta

Tras hasta 20 minutos revisando una hipótesis, comparte el caso mínimo con tu pareja y con Integración y Team Leader. Registra y escala al coordinador a los 30 minutos sin progreso, como indica el guion. En el primer montaje, el límite de intento es 45 minutos: continúa con maqueta, datos o contratos mientras se resuelve el entorno, sin marcar la prueba real como hecha.

Si falta una condición para ejecutar, conserva NO-VERIFICADO y explica cuál. Una expectativa bien redactada es útil para construir, pero solo la ejecución y su evidencia permiten cerrar el criterio de la ficha.


La matriz TRAZABILIDAD.csv del paquete relaciona las reglas R01–R18 con construcción, comprobación y evidencia individual. QA-061 añade la captura repetida sobre un mismo documento pendiente.
