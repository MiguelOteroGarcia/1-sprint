# Diccionario de datos y contratos

FM-WEB-01 · Recurso de trabajo del alumno · Versión didáctica 1.0.

Usa este documento para acordar nombres de campos, escribir migraciones y comprobar que pantalla, servidor y pruebas hablan de los mismos datos. El guion funcional de Fundación fija las reglas del piloto. Los tamaños, nombres técnicos y rutas de este recurso concretan una implementación educativa; no son requisitos jurídicos ni clínicos aprobados por el cliente.

El núcleo de 370 horas incluye identidad, agenda y documentos. En 500 horas se añaden resultados de tres tipos fijos. Todos los ejemplos son ficticios. Una captura del laboratorio no demuestra persistencia, permisos ni concurrencia del servidor.

## 1. Convenciones que evitan confusiones

| Término | Significado y convención |
| --- | --- |
| Usuario | Persona atendida. En la receta SQL se almacena en `personas`; la FK técnica es `persona_id`. En pantalla se escribe «usuario». |
| Cuenta de personal | Persona que inicia sesión. Tabla `users` del framework; `personal_id` apunta a esa tabla. No se registra públicamente. |
| ID | Clave interna `bigint`, generada por la base. No identifica por sí sola un permiso. |
| Código | Texto visible estable y único, hasta 40 caracteres. El servidor lo genera; U001 es un caso de semilla, no un número de fila. |
| FK | Clave foránea: solo admite un ID existente de la tabla indicada. Borrar un padre con historial se restringe. |
| Nulo | Ausencia de dato. En JSON se escribe `null`; en la base, NULL; en CSV, celda vacía. No significa cero. |
| Texto vacío | Se recortan espacios exteriores; un opcional vacío se normaliza a nulo. No se elimina contenido significativo de nombres. |
| Fecha | `date`, representada como `AAAA-MM-DD`; no contiene una hora. |
| Instante | `timestamptz`; intercambio ISO 8601 con desplazamiento, por ejemplo `2030-06-10T10:00:00+02:00`. |
| Decimal | `numeric(9,3)`: hasta nueve cifras totales y tres decimales. No redondear silenciosamente una entrada que exceda el formato. |
| Versión | Entero positivo. Cambiar una versión histórica crea otra fila; no reescribe la anterior. |

La receta de backend contiene un SQL reducido para ensayar la última plaza. Su booleano `confirmada` no representa los tres estados de usuario: el modelo completo utiliza `estado`. Su `servicio` textual pasa a `servicio_id` cuando se incorpora la tabla de servicios; `sesiones.abierta` se representa con `sesiones.estado`. Adapta también esas lecturas del servicio mediante migraciones y pruebas, sin mantener dos campos contradictorios como autoridades paralelas.

Los archivos del laboratorio usan códigos legibles como `usuario: "U001"` o `centro: "C01"`. El seeder busca sus IDs por código y carga las FK. No introduce el texto U001 en una columna `bigint` ni supone que U001 tendrá siempre el ID 1.

## 2. Identidad y personal

### 2.1 Personas atendidas: `personas`

| Campo | Tipo | Nulo | Regla / ejemplo |
| --- | --- | --- | --- |
| id | bigint, PK | No | Generado por base. |
| codigo | varchar(40), UNIQUE | No | U001, U002; estable aunque cambie el contacto. |
| nombre | varchar(100) | No | Alba; texto ficticio no vacío. |
| apellidos | varchar(150) | No | Ejemplo Norte. |
| fecha_nacimiento | date | No | 1990-04-12; rechazar fecha futura respecto al reloj de prueba. |
| tipo_atencion | varchar(24) | No | `adulto` o `menor_representado`, elegido explícitamente. |
| correo | varchar(180) | Sí | alba@example.test; validar formato si se aporta. |
| telefono | varchar(40) | Sí | Valor ficticio de ensayo; no UNIQUE. |
| estado | varchar(16) | No | `pendiente`, `confirmado`, `inactivo`. |
| sexo | varchar(40) | Sí | Texto de demostración, sin inferencias. |
| poblacion | varchar(100) | Sí | Población ficticia. |
| codigo_postal | varchar(12) | Sí | `01001`; siempre texto. |
| procedencia | varchar(100) | Sí | Texto opcional. |
| especialidad | varchar(100) | Sí | Texto opcional. |
| created_at, updated_at | timestamptz | No | Asignados por servidor. |

Para un adulto basta correo o teléfono. Para menor representado basta el contacto de su representante; no exijas contacto propio al menor. Un teléfono coincidente genera aviso privado a recepción, no fusión ni bloqueo. No se recogen dirección ni profesión. La fecha de nacimiento no decide automáticamente quién firma.

### 2.2 Representantes y vínculo explícito

| Tabla / campo | Tipo | Nulo | Regla |
| --- | --- | --- | --- |
| representantes.id | bigint, PK | No | Identidad del representante, independiente del menor. |
| representantes.codigo | varchar(40), UNIQUE | No | R001 en las semillas. |
| representantes.nombre, apellidos | varchar(100), varchar(150) | No | Valores ficticios separados. |
| representantes.correo, telefono | varchar(180), varchar(40) | Sí | Al menos uno para representar al usuario del caso. |
| representantes.created_at, updated_at | timestamptz | No | Fechas del servidor. |
| persona_representante.persona_id | FK → personas.id | No | Usuario atendido. |
| persona_representante.representante_id | FK → representantes.id | No | Representante de ese usuario. |
| persona_representante.activo | boolean | No | Permite conservar una relación anterior sin borrarla. |
| persona_representante.confirmado_por | FK → users.id | Sí | Nulo mientras recepción no haya comprobado el caso. |
| persona_representante.confirmado_at | timestamptz | Sí | Nulo hasta esa comprobación; se completa junto al autor. |

La pareja de FK es única. Antes de firmar MENOR-DEMO debe existir una relación activa, comprobada por recepción. Si hay más de una relación autorizada, recepción selecciona explícitamente al firmante. El documento conserva la relación e identidad utilizadas aunque después cambien los datos actuales.

### 2.3 Cuentas de personal y centros asignados

| Tabla / campo | Tipo | Nulo | Regla |
| --- | --- | --- | --- |
| users.id | bigint, PK | No | Cuenta autenticada; no es una ficha de usuario atendido. |
| users.codigo | varchar(40), UNIQUE | No | Código ficticio estable de personal: P01, P02; permite identificar autor sin exportar correo. |
| users.name, email | varchar(150), varchar(180) | No | Cuenta ficticia; email de acceso único. |
| users.password | texto protegido por framework | No | Guardar hash mediante el framework, nunca contraseña en claro. |
| users.estado | varchar(16) | No | `activo` o `inactivo`. Inactivo pierde acceso. |
| users.exportar_resultados | boolean | No | False por defecto; solo es útil con rol profesional. |
| personal_roles.personal_id | FK → users.id | No | Una fila por concesión expresa. |
| personal_roles.rol | varchar(20) | No | `recepcion`, `profesional` o `administracion`. |
| personal_centros.personal_id | FK → users.id | No | Cuenta con asignación. |
| personal_centros.centro_id | FK → centros.id | No | Centro autorizado. |

Cada pareja de campos de las tablas de asignación es única. Las semillas normales tienen un rol por cuenta. Combinar roles exige concesión expresa; administración no hereda acceso profesional ni a documentos por defecto. El permiso de exportar resultados no amplía los centros asignados.

## 3. Centros, servicios, sesiones y reservas

| Tabla / campo | Tipo | Nulo | Regla |
| --- | --- | --- | --- |
| centros.id / codigo / nombre | bigint PK / varchar(40) UNIQUE / varchar(100) | No | C01 Centro Norte; C02 Centro Sur. |
| servicios.id / codigo / nombre | bigint PK / varchar(40) UNIQUE / varchar(100) | No | SV01 atención individual; SV02 taller. |
| servicios.duracion_minutos | integer | No | Positiva; ejemplos 30 y 60. |
| servicios.capacidad_predeterminada | integer | No | Positiva; ejemplos 1 y 3. |
| sesiones.id / codigo | bigint PK / varchar(40) UNIQUE | No | Sesión concreta, no patrón recurrente. |
| sesiones.centro_id | FK → centros.id | No | Centro de la actividad. |
| sesiones.servicio_id | FK → servicios.id | No | Servicio ofrecido en esa sesión. |
| sesiones.inicio, fin | timestamptz | No | Fin posterior al inicio. |
| sesiones.zona | varchar(50) | No | `Europe/Madrid` para la presentación de esta demo. |
| sesiones.capacidad | integer | No | Mayor que cero; la sesión conserva su capacidad propia. |
| sesiones.estado | varchar(12) | No | `abierta` o `cerrada`. |
| reservas.id / codigo | bigint PK / varchar(40) UNIQUE | No | Código de comprobante generado por servidor. |
| reservas.sesion_id | FK → sesiones.id | No | Determina centro y servicio actuales. |
| reservas.persona_id | FK → personas.id | Sí | Nulo en la reserva pública antes de confirmar identidad. |
| reservas.prerregistro | jsonb | Sí | Datos mínimos validados; obligatorio si no hay persona confirmada. |
| reservas.canal | varchar(12) | No | `online` o `presencial`, asignado por ruta de servidor. |
| reservas.estado | varchar(20) | No | `reservada`, `cancelada`, `atendida`, `no_presentada`. |
| reservas.operacion | varchar(64), UNIQUE | No | Clave de intento emitida por servidor y ligada al navegador. |
| reservas.payload_hash | char(64) | No | SHA-256 de campos normalizados del intento; calculado en servidor. |
| reservas.token_gestion_hash | char(64) | No | Huella de clave aleatoria privada; nunca guardar la clave original. |
| reservas.created_at, updated_at | timestamptz | No | Fechas del servidor. |

El centro de una reserva se obtiene por su sesión. Si se añade `centro_id` por comodidad, hay que impedir que contradiga esa relación. No añadas un contador de plazas mantenido por separado: cuenta `reservada`, `atendida` y `no_presentada` bajo el bloqueo de sesión. `cancelada` no ocupa.

El prerregistro contiene `nombre`, `apellidos`, `fecha_nacimiento`, `tipo_atencion`, `correo`, `telefono` y, para menor representado, `representante` con identidad/contacto. No admite un ID de persona elegida por el público. Tras vincular, conserva el origen y la decisión en auditoría; no copies firmas ni resultados a la persona seleccionada.

### 3.1 Cambios de estado

| Entidad | Acción | Condición y efecto |
| --- | --- | --- |
| Usuario pendiente | Confirmar | Recepción valida mínimos y genera/conserva código estable. |
| Usuario confirmado | Inactivar | Motivo y autor; mantiene historial. Bloquea nuevas reservas, firmas y pruebas. |
| Prerregistro | Crear o vincular | Recepción autorizada por centro; confirma persona o vincula una confirmada. Mantener pendiente no confirma. |
| Reserva reservada | Cancelar | Antes del inicio; libera una plaza una sola vez. Repetir devuelve el estado cancelado sin otro evento. |
| Reserva reservada | Reprogramar | Antes del inicio, destino disponible y sin documento firmado conservado; mismo ID, cambio atómico. |
| Reserva reservada | Marcar atendida/no presentada | Después del inicio; conserva ocupación histórica. |
| Documento pendiente | Firmar | Identidad confirmada y reserva reservada/atendida; PNG y PDF privados guardados y recuperables. |
| Documento firmado | Corregir | Anterior requiere revisión; nueva versión pendiente, motivo y captura nueva. |

Cancelar una reserva con documento firmado exige recepción antes del inicio; conserva el archivo y marca requiere revisión. Una nueva reserva necesita un documento nuevo. Una consulta GET nunca cancela, cambia o confirma. Si una acción falla, comprueba que no deja una fila parcial ni una relación cambiada.

Para el tiempo usa un reloj controlado. Una fecha/hora local ambigua requiere desplazamiento UTC explícito; una inexistente se rechaza. No sumes una hora fija para resolver el cambio estacional. Reubica las semillas futuras junto con el reloj, manteniendo el orden temporal de los casos.

## 4. Plantillas, documentos y auditoría

| Tabla / campo | Tipo | Nulo | Regla |
| --- | --- | --- | --- |
| plantillas.id / codigo | bigint PK / varchar(40) UNIQUE | No | ADULTO-DEMO, MENOR-DEMO o TALLER-DEMO. |
| plantilla_versiones.id | bigint, PK | No | Referencia exacta de texto. |
| plantilla_versiones.plantilla_id | FK → plantillas.id | No | Tipo de documento. |
| plantilla_versiones.version | integer | No | Positiva; pareja plantilla/versión única. |
| plantilla_versiones.texto | text | No | Texto didáctico completo e inmutable una vez publicado. |
| plantilla_versiones.huella_texto | char(64) | No | Hash de los bytes UTF-8 del texto publicado. |
| plantilla_versiones.publicada_at | timestamptz | Sí | Nulo mientras sea borrador. No firmar un borrador. |
| documentos.id / codigo | bigint PK / varchar(40) UNIQUE | No | Identifica una versión documental concreta. |
| documentos.persona_id | FK → personas.id | No | Usuario confirmado al crear la firma. |
| documentos.representante_id | FK → representantes.id | Sí | Obligatorio en MENOR-DEMO; nulo para adulto/taller. |
| documentos.reserva_id | FK → reservas.id | No | También obligatorio en taller; una serie por asistente/reserva, con versiones conservadas. |
| documentos.centro_id | FK → centros.id | No | Debe coincidir con la reserva en el momento de firmar. |
| documentos.plantilla_version_id | FK → plantilla_versiones.id | No | Versión publicada elegida en servidor. |
| documentos.actual | boolean | No | Solo una versión actual por reserva; índice único parcial donde actual=true. |
| documentos.captura_hash | char(64) | Sí | Huella del PNG validado y snapshot para reconocer reenvío del mismo ID. |
| documentos.version | integer | No | Versión del documento; distinta de versión del texto. |
| documentos.anterior_id | FK → documentos.id | Sí | Nulo en la primera versión; apunta a la que se corrige. |
| documentos.texto_snapshot | text | No | Copia exacta del texto mostrado y confirmado. |
| documentos.identidad_snapshot | jsonb | No | Código/nombre de usuario, firmante, centro, actividad y fecha mostrados. |
| documentos.estado | varchar(24) | No | `pendiente`, `firmado`, `requiere_revision`; mostrar «requiere revisión». |
| documentos.png_path, pdf_path | varchar(255) | Sí | Rutas privadas generadas en servidor; obligatorias al marcar firmado. |
| documentos.hash_pdf | char(64) | Sí | Se completa al guardar y comprobar el archivo. |
| documentos.personal_id | FK → users.id | No | Autor de la acción de recepción, asignado por sesión autenticada. |
| documentos.firmado_at | timestamptz | Sí | Nulo mientras no exista una firma guardada correctamente. |
| documentos.motivo_correccion | varchar(500) | Sí | Obligatorio si anterior_id no es nulo. |
| documentos.created_at, updated_at | timestamptz | No | Fechas de servidor. |

No recibas rutas privadas, autor ni estado firmado desde campos ocultos del navegador. Obtén usuario, representante, centro, reserva y versión del contexto autorizado; cualquier ID recibido se contrasta de nuevo. Una imagen debe ser PNG real, no vacía, de hasta 1 MB y 1600 × 800 píxeles. Documento, imagen y PDF no se publican mediante una carpeta pública.

`eventos_cambio` conserva `id` (PK), `personal_id` (FK nullable), `actor_tipo` (`personal` o `clave_reserva`), `reserva_id` (FK nullable), `entidad` y `entidad_id`, `accion`, `instante`, `motivo` nullable y un resumen de IDs/estados anterior y nuevo. El actor personal exige cuenta; la acción por clave exige referencia de reserva. No almacena contraseña, clave privada, contacto completo, imagen ni valores de pruebas en un log general.

La auditoría del producto registra quién realizó una operación. La revisión del aprendizaje es otro registro: en el PDF de tarea identifica autor técnico, archivo/commit, caso, persona revisora, resultado y corrección. El revisor del código no se convierte por ello en firmante del documento ni en autor del resultado clínico ficticio. Ambos estudiantes deben poder explicar su contribución.

## 5. Resultados fijos de 500 horas

Usa una tabla `resultados` con `id`, `codigo`, `persona_id`, `centro_id`, `tipo`, `version`, `anterior_id`, `estado` (`vigente`/`sustituido`), `fecha`, `documento_id`, `autor_id`, `motivo_correccion`, `created_at` y `esquema_version`. Persona, centro, documento y autor son FK. `anterior_id` y motivo son nulos únicamente en la primera versión; el resto es obligatorio. Cada fila/version tiene ID único; el código de serie y versión forman una pareja única. La versión siguiente apunta a la anterior, sin sobrescribirla.

`tipo` es `bioimpedancia`, `bioquimica` o `densitometria`; `esquema_version` vale 1 en estos ejemplos. Guarda las medidas en tres tablas fijas, una por tipo, con `resultado_id` como PK y FK. No construyas un diseñador de formularios ni aceptes medidas desconocidas. Para bioquímica añade `codigo_postal` (varchar(12), nullable) y `fecha_informe` (date, nullable), como metadatos; no cuentan como medida aportada.

La fecha del resultado es obligatoria y no futura. Requiere al menos una medida no nula, usuario confirmado, profesional del centro y documento firmado de ese usuario/centro: ADULTO-DEMO o MENOR-DEMO según su tipo. Un documento requiere revisión o TALLER-DEMO no habilita resultados. La fecha del informe, cuando se aporte, debe ser una fecha válida; no inventes un umbral sanitario para ella.

### 5.1 Campos y unidades exactas del esquema 1

Todos los valores son `numeric(9,3)` nullable. Las unidades son constantes del esquema, no texto libre que decide el navegador. Se conserva también la unidad de cada valor en su versión histórica. Aquí `unidad_demo`, `porcentaje_demo` y `escala_demo` son etiquetas educativas; no certifican unidades de uso real.

| Tipo | Campo | Etiqueta | Unidad | Regla de demostración |
| --- | --- | --- | --- | --- |
| bioimpedancia | altura | Altura | unidad_demo | Decimal finito; sin umbral sanitario. |
| bioimpedancia | peso | Peso | unidad_demo | Decimal finito; sin umbral sanitario. |
| bioimpedancia | grasa_pct | Grasa | porcentaje_demo | Entre 0 y 100. |
| bioimpedancia | grasa_visceral | Grasa visceral | unidad_demo | Decimal finito; sin umbral sanitario. |
| bioimpedancia | agua_pct | Agua | porcentaje_demo | Entre 0 y 100. |
| bioimpedancia | musculo | Músculo | unidad_demo | Decimal finito; sin umbral sanitario. |
| bioimpedancia | hueso | Hueso | unidad_demo | Decimal finito; sin umbral sanitario. |
| bioimpedancia | tmb | TMB | unidad_demo | Transcripción, sin cálculo. |
| bioimpedancia | imc | IMC | unidad_demo | Transcripción, sin cálculo. |
| bioquimica | vitamina_d | Vitamina D | unidad_demo | Decimal finito; sin umbral sanitario. |
| bioquimica | calcio | Calcio | unidad_demo | Decimal finito; sin umbral sanitario. |
| bioquimica | fosforo | Fósforo | unidad_demo | Decimal finito; sin umbral sanitario. |
| bioquimica | magnesio | Magnesio | unidad_demo | Decimal finito; sin umbral sanitario. |
| densitometria | t_score | T score | unidad_demo | Admite negativos. |
| densitometria | z_score | Z score | unidad_demo | Admite negativos. |
| densitometria | bqi | BQI | unidad_demo | Decimal finito; sin umbral sanitario. |
| densitometria | bua | BUA | unidad_demo | Decimal finito; sin umbral sanitario. |
| densitometria | sos | SOS | unidad_demo | Decimal finito; sin umbral sanitario. |
| densitometria | frax_cadera | FRAX cadera | porcentaje_demo | Entre 0 y 100; sin cálculo. |
| densitometria | frax_osteoporosis | FRAX osteoporosis | porcentaje_demo | Entre 0 y 100; sin cálculo. |
| densitometria | eva | EVA | escala_demo | Opcional; entre 0 y 10. |

Ejemplos de formato: `5.5` con `unidad_demo`, `0` con `porcentaje_demo`, `-1.25` para T/Z y `null` para un opcional ausente. Son valores de ensayo, no rangos de referencia. Rechaza infinito, texto no numérico, exceso de precisión y porcentaje 100.001. Un cero permitido cuenta como medida; no lo elimines mediante una comprobación booleana de «vacío».

## 6. Actores, rutas y respuestas

Las rutas siguientes son el contrato de implementación del equipo. Laravel puede servir HTML Blade y formularios con CSRF; los JSON muestran datos y estados para pruebas o peticiones con `Accept: application/json`. Un POST HTML correcto redirige al GET de resultado y uno inválido devuelve el formulario con errores y entradas permitidas. No hace falta crear una API separada.

| Acción / método y ruta | Quién | Entrada y resultado |
| --- | --- | --- |
| GET /sesiones | Público | Filtros centro/servicio/fecha; sesiones, hora y plazas orientativas; nunca usuarios. |
| POST /reservas | Público | Sesión, operación y prerregistro; crea reserva reservada con identidad pendiente. |
| POST /gestion/acceso | Público con clave privada | Código de reserva y clave; crea sesión de gestión limitada a esa reserva. Mensaje genérico si falla. |
| GET /gestion/reserva | Sesión de gestión de una reserva | Comprobante mínimo de esa reserva; no muta. No transporta la clave en la dirección. |
| POST /gestion/reserva/cancelar | Gestión autorizada | Confirmación y CSRF; cancelación según reglas. Si hay documento firmado, deriva a recepción. |
| POST /gestion/reserva/cambiar | Gestión autorizada | sesion_destino_id, confirmación y CSRF; mismo ID o error sin perder origen. |
| POST /personal/login, POST /personal/logout | Personal | Credenciales/sesión del framework; entrada regenera sesión, salida la invalida. |
| GET /personal/usuarios | Recepción | q por código/nombre/contacto; candidatos privados de identidad común. |
| POST /personal/usuarios | Recepción | Mínimos validados; nuevo código estable, aviso privado de coincidencia. |
| POST /personal/prerregistros/{reserva}/confirmar | Recepción del centro | decisión crear/vincular/pendiente; persona elegida se valida en servidor; auditoría. |
| POST /personal/reservas | Recepción del centro | Sesión e identidad autorizada; mismo servicio de reserva que público, canal presencial. |
| POST /personal/reservas/{reserva}/cancelar o /cambiar | Recepción del centro | Misma regla horaria y transaccional; sin excepción de plazas. |
| POST /personal/reservas/{reserva}/estado | Recepción del centro | atendida/no_presentada, solo tras inicio; conserva ocupación histórica. |
| POST /personal/reservas/{reserva}/restablecer-clave | Recepción del centro | Caso ficticio revisado; nueva clave anula la anterior, sin nueva reserva. |
| GET /personal/usuarios/{usuario}/identidad-minima | Profesional autorizado | Código, nombre y tipo; exige relación con reserva/documento/resultado de centro asignado, sin contactos. |
| GET /personal/centros/{centro}/configuracion | Administración del centro | Datos de configuración; recepción/profesional sin ese rol reciben denegación. |
| POST /personal/centros/{centro}/sesiones | Administración del centro | Servicio, inicio, fin, zona, capacidad; sesión concreta válida. |
| POST /personal/cuentas | Administración | Cuenta ficticia con roles/centros concedidos expresamente; sin alta pública. |
| GET /personal/reservas/{reserva}/firma | Recepción del centro | Contexto autorizado, texto completo, versión y firmante esperado. |
| POST /personal/reservas/{reserva}/documentos | Recepción del centro | PNG y contexto/version esperado; servidor revalida relaciones antes de guardar. |
| GET /personal/documentos/{documento}/pdf | Recepción o profesional del centro | PDF autorizado; administración sin rol adicional y visitantes no acceden. |
| POST /personal/documentos/{documento}/corregir | Recepción del centro | Motivo; anterior requiere revisión y nueva versión pendiente. |
| POST /personal/resultados | Profesional del centro, 500 | Tipo, fecha, documento y valores fijos; autor/versión asignados en servidor. |
| GET /personal/resultados | Profesional del centro, 500 | Filtros autorizados; otros autores del mismo centro son consultables. |
| POST /personal/resultados/{resultado}/corregir | Profesional del centro, 500 | Motivo y valores corregidos; nueva versión, anterior conservada. |
| GET /personal/ocupacion.csv | Recepción del centro | Agregado de sesiones autorizadas, sin identidad ni documentos. |
| GET /personal/resultados.csv | Profesional con exportar_resultados, 500 | Tipo y filtros; solo centros asignados y versiones vigentes. |

El Team Leader de la plataforma de aprendizaje no es un rol de este producto. Administración de la aplicación tampoco significa acceso universal. Cada controlador aplica cuenta activa, permiso y centro antes de leer datos; un filtro solicitado se cruza con los centros autorizados. Nunca amplía ese conjunto.

### 6.1 Respuestas JSON de referencia

Una salida correcta contiene `ok: true` y `data`. Un error contiene `ok: false`, un código estable, mensaje y errores de campo cuando proceda. Se registran los siguientes estados de prueba:

| Estado HTTP del contrato JSON | Uso |
| --- | --- |
| 200 | Consulta/cambio correcto o repetición idempotente. |
| 201 | Reserva, usuario, documento o versión nueva creada. |
| 401 | Falta sesión válida de personal o gestión; mensaje sin datos. |
| 403 | Cuenta activa sin permiso para la operación o centro. |
| 404 | Recurso no disponible; no revelar mediante mensajes quién es su titular. |
| 409 | Conflicto: sin plaza, estado cambiado, destino inválido, versión antigua o operación reutilizada con otros datos. |
| 422 | Datos de entrada inválidos, fecha futura o PNG vacío/mal formado. |
| 503 | Fallo recuperable al generar/guardar PDF; documento sigue pendiente, sin archivo público. |

El controlador adapta `ValidationException` de la receta: errores de formato son 422; conflictos de plaza/estado del dominio se traducen a 409 en este contrato. En HTML conserva la misma explicación y efecto. No cambies estados esperados en una prueba sin actualizar el contrato compartido.

Petición pública a POST /reservas, con una operación de ejemplo emitida para ese navegador:

```json
{
  "sesion_id": 101,
  "operacion": "OPERACION_DE_ENSAYO_EMITIDA_POR_SERVIDOR",
  "prerregistro": {
    "nombre": "Alba",
    "apellidos": "Ejemplo Norte",
    "fecha_nacimiento": "1990-04-12",
    "tipo_atencion": "adulto",
    "correo": "alba@example.test",
    "telefono": null,
    "representante": null
  }
}
```

El cliente no envía persona_id, canal, estado, hash, autor ni centro alternativo. El servidor obtiene centro/servicio de la sesión y genera una clave privada de al menos 32 bytes aleatorios. La cadena siguiente es una etiqueta didáctica, nunca una clave reutilizable:

```json
{
  "ok": true,
  "data": {
    "reserva_codigo": "RV-DEMO-001",
    "estado": "reservada",
    "identidad": "pendiente",
    "centro": "C01",
    "servicio": "SV01",
    "inicio": "2030-06-10T10:00:00+02:00",
    "clave_gestion": "MOSTRAR_AQUI_LA_CLAVE_ALEATORIA_SOLO_UNA_VEZ"
  }
}
```

«Reserva confirmada» en la pantalla significa que se ha guardado `reservada`; no confirma la identidad. La respuesta pública es igual exista o no una persona parecida. Una respuesta pendiente del doble local de S1 significa operación sin resultado definitivo, no un quinto estado persistente de reserva.

Repetir misma operación y contenido conserva esa reserva. La clave original no se reconstruye desde su hash; si se perdió la primera respuesta, se informa de recuperación en recepción:

```json
{
  "ok": true,
  "data": {
    "reserva_codigo": "RV-DEMO-001",
    "estado": "reservada",
    "repetida": true,
    "clave_gestion": null,
    "recuperacion": "Solicita a recepción el restablecimiento de la clave."
  }
}
```

Ejemplo de formato inválido, sin crear una fila parcial:

```json
{
  "ok": false,
  "error": {
    "codigo": "DATOS_INVALIDOS",
    "mensaje": "Revisa el contacto indicado.",
    "campos": {"prerregistro.correo": ["Indica un correo válido o un teléfono."]}
  }
}
```

Ejemplo de última plaza o cambio a destino lleno. En un cambio, el ID y la sesión original siguen iguales:

```json
{
  "ok": false,
  "error": {
    "codigo": "SIN_PLAZAS",
    "mensaje": "No quedan plazas en el destino. Tu cita actual se conserva.",
    "campos": {"sesion_destino_id": ["Elige otra sesión."]}
  }
}
```

Para una creación, el mismo código usa «No quedan plazas. Elige otra sesión», sin anunciar una cita previa inexistente. Una búsqueda sin resultados devuelve colección vacía, no un error. Solo recepción puede recibir candidatos:

```json
{
  "ok": true,
  "data": {
    "candidatos": [
      {"id": 3, "codigo": "U003", "nombre": "Celia", "apellidos": "Ejemplo", "fecha_nacimiento": "1980-02-03"},
      {"id": 4, "codigo": "U004", "nombre": "Dario", "apellidos": "Ejemplo", "fecha_nacimiento": "1982-05-06"}
    ],
    "aviso": "El contacto coincide. Compara los datos antes de elegir."
  }
}
```

Vincular, por recepción y con reserva autorizada: `{"decision":"vincular","persona_id":3}`. Mantener pendiente: `{"decision":"pendiente"}`. Crear: `{"decision":"crear"}` utiliza el prerregistro validado y los ajustes que recepción haya confirmado. Respuesta de vinculación: `{"ok":true,"data":{"reserva_id":201,"persona_id":3,"decision":"vincular","evento_id":901}}`. El evento guarda actor y fecha del servidor, no valores suministrados por el navegador.

Petición de resultado de densitometría, solo 500; el documento 301 debe existir y cumplir las precondiciones:

```json
{
  "persona_id": 1,
  "centro_id": 1,
  "tipo": "densitometria",
  "fecha": "2030-06-10",
  "documento_id": 301,
  "esquema_version": 1,
  "valores": {
    "t_score": {"valor": -1.25, "unidad": "unidad_demo"},
    "z_score": {"valor": null, "unidad": "unidad_demo"},
    "bqi": {"valor": null, "unidad": "unidad_demo"},
    "bua": {"valor": null, "unidad": "unidad_demo"},
    "sos": {"valor": null, "unidad": "unidad_demo"},
    "frax_cadera": {"valor": 0, "unidad": "porcentaje_demo"},
    "frax_osteoporosis": {"valor": null, "unidad": "porcentaje_demo"},
    "eva": {"valor": null, "unidad": "escala_demo"}
  }
}
```

Fija el reloj de esa prueba en 2030-06-10 o posterior: fuera de ese ensayo la fecha podría ser futura y debe rechazarse. El servidor valida unidades contra el esquema, completa opcionales ausentes como nulos y devuelve ID de versión y autor asignados. El profesional no puede enviar un centro no asignado ni reutilizar un documento de otra persona.

## 7. CSV con columnas estables

CSV UTF-8, coma como delimitador, punto decimal y una cabecera. Una fila por sesión en ocupación; una fila por versión vigente de resultado en 500. Escapa comillas duplicándolas y entrecomilla celdas que contengan coma, comilla o salto de línea. No uses el separador regional del ordenador como autoridad.

### 7.1 Núcleo 370: ocupación agregada

Cabecera exacta y ejemplo sintético:

```csv
centro,servicio,sesion,inicio,capacidad,reservadas,atendidas,no_presentadas,canceladas,libres
C01,SV02,SE-DEMO-01,2030-06-10T10:00:00+02:00,3,1,1,0,1,1
```

`centro`, `servicio` y `sesion` son sus códigos estables. `libres = capacidad - reservadas - atendidas - no_presentadas`. En el ejemplo hay dos plazas ocupadas; la cancelada no ocupa. No incluyas nombre, contacto, código de usuario, documento ni clave privada. Recepción solo exporta sesiones de centros asignados. Compara recuentos con la vista bajo los mismos filtros y el mismo instante de ensayo.

### 7.2 Ampliación 500: resultados por tipo

Exporta un tipo por archivo. Las columnas comunes, en este orden, son `resultado_codigo`, `version_id`, `version`, `usuario_codigo`, `centro`, `tipo`, `fecha`, `documento_codigo`, `autor_codigo`, `esquema_version`. `version_id` es el ID único de la fila, para contrastar exactamente qué versión se exportó. `autor_codigo` identifica la cuenta ficticia de personal, sin correo ni credencial.

A continuación añade las columnas de la tabla siguiente en su orden. Por cada medida se exporta el valor y después su unidad. Un nulo deja vacía la celda de valor; la unidad conserva la etiqueta del esquema. El nombre de columna no cambia según los datos del filtro.

| Tipo | Columnas después de las comunes, en este orden |
| --- | --- |
| bioimpedancia | altura, altura_unidad, peso, peso_unidad, grasa_pct, grasa_pct_unidad, grasa_visceral, grasa_visceral_unidad, agua_pct, agua_pct_unidad, musculo, musculo_unidad, hueso, hueso_unidad, tmb, tmb_unidad, imc, imc_unidad |
| bioquimica | codigo_postal, fecha_informe, vitamina_d, vitamina_d_unidad, calcio, calcio_unidad, fosforo, fosforo_unidad, magnesio, magnesio_unidad |
| densitometria | t_score, t_score_unidad, z_score, z_score_unidad, bqi, bqi_unidad, bua, bua_unidad, sos, sos_unidad, frax_cadera, frax_cadera_unidad, frax_osteoporosis, frax_osteoporosis_unidad, eva, eva_unidad |

La variante sin nombre usa exactamente las columnas anteriores. La variante con nombre inserta `nombre` y `apellidos` después de `usuario_codigo`. En ambas se mantiene usuario_codigo: ninguna se presenta como anónima. No exportes versiones sustituidas en la descarga ordinaria. Recepción y administración sin rol profesional no pueden obtener el archivo, ni escribiendo la ruta directamente.

Ejemplo de prefijo de una fila de densitometría: `RES-DEMO-001,401,1,U001,C01,densitometria,2030-06-10,DOC-DEMO-001,P02,1`. Después van todas sus parejas valor/unidad; T score puede ser `-1.25,unidad_demo` y Z score nulo `,unidad_demo`. No recortes las columnas vacías del final.

Para texto que comience por `=`, `+`, `-` o `@`, o controles que puedan activar una fórmula, antepone un apóstrofo y trátalo como texto al importar. Aplica esa protección solo a campos de texto: `-1.25` en una medida numérica válida conserva su signo y tipo. Prueba además texto con coma, comillas y salto de línea. Para el código postal selecciona columna de texto en la hoja de cálculo y verifica que `01001` conserva el cero. Abre con UTF-8 y delimitador coma; no infieras que una vista correcta garantiza un CSV correcto.

## 8. Cómo cambiar un contrato y demostrar que funciona

Antes de cambiar un campo escribe en `docs/contratos/cambios.md`: tarea, motivo, contrato anterior, contrato nuevo, archivos y pruebas afectados, autor y persona revisora. Pide a la pareja consumidora que pruebe un válido y un inválido. Una aceptación oral sin ejemplo deja a otra pareja sin forma de reproducir el acuerdo.

Procedimiento de comprobación: carga semillas en fm_test, verifica nombres y FK, ejecuta una entrada válida, repite una inválida y consulta el estado posterior. Para una operación de cambio conserva antes/después. Para una exportación compara sus filas con la consulta autorizada y comprueba nulos, ceros, negativos y texto peligroso.

Ejemplo de evidencia: «Autor A adaptó la búsqueda por contacto en el commit indicado. Revisor B ejecutó U003/U004 y obtuvo dos códigos. Después solicitó la misma búsqueda sin sesión: no recibió candidatos. La corrección eliminó una selección automática de la primera fila». El PDF contiene entradas y salidas reales; código y pruebas permanecen en el repositorio.

Criterios observables: dos familiares mantienen IDs distintos; una denegación no devuelve datos; un cambio fallido conserva el origen; cada versión conserva autor y anterior; CSV y vista coinciden en filtros y permisos. Si falta servidor, registra el contrato y la simulación por separado: sigue pendiente ejecutar esos criterios en el backend.


## Reintento de documento

La captura usa el ID pendiente emitido por servidor. Bloquea sesión, reserva y documento; relee permisos y estado. Repetir el mismo ID/huella firmado devuelve el documento existente; contenido diferente exige corrección. Al corregir, la anterior pasa a actual=false y requiere revisión y la nueva nace pendiente actual=true; conserva anterior_id. Los archivos previos no se sobrescriben. Véase Recetas, apartado 10.
