# Guion funcional de Fundación · Usuarios, agenda y firma

FM-WEB-01 · Especificación del piloto educativo · Planes alternativos de 370 y 500 horas por alumno · Diez estudiantes DAW junior.

## 1. Resultado y límites del piloto

Construyes una aplicación web para registrar usuarios ficticios, reservar plazas y conservar documentos con rúbricas de demostración. Usa siempre «usuario» para la persona atendida y «centro» para el lugar de atención. Una cuenta de personal pertenece a quien trabaja con la aplicación; no es la ficha del usuario atendido.

El núcleo de 370 horas incluye dos centros de demostración, registro único, agenda propia, reservas online y presenciales, cancelación, cambio de cita y documentos de adulto, menor representado y taller. El plan de 500 horas incluye ese núcleo y el registro, consulta, corrección y exportación de tres tipos fijos de pruebas ficticias. No son dos aplicaciones ni se suman sus presupuestos.

Quedan fuera la conexión con Bookings, el correo externo, los pagos, las cuentas de las personas atendidas y el despliegue público. Tampoco se construyen diagnósticos, cálculos de salud, formularios de pruebas configurables ni una firma certificada. Todos los datos, nombres, contactos, medidas y rúbricas son inventados. No copies registros reales en código, pruebas, capturas o consultas de IA.

## 2. Autoridad y decisiones de demostración

La propuesta del cliente F7 pide registro, agenda y firmas como primera versión; las pruebas aparecen después. Los documentos F1–F6 sirven para conocer campos y recorridos. Sus textos presentan dudas que se conservan en las consultas al cliente; los estudiantes no resuelven cuestiones jurídicas ni sanitarias.

Este guion fija reglas didácticas para que puedas avanzar sin esperar esas respuestas. No afirma que el cliente haya aprobado cada campo, plazo o permiso. El coordinador registra cualquier cambio acordado antes de modificar datos o criterios. El encargo posterior de Antonio autoriza producir este roadmap tras leer el borrador v0.2.

Los datos mínimos, las sesiones predefinidas, la identificación explícita de un menor representado y el documento requerido para una prueba son decisiones de esta demostración. No se deducen derechos o edades legales. La rúbrica se ensaya con ratón y emulación táctil; una tableta real requiere comprobación posterior del coordinador o cliente.

## 3. Actores y permisos del producto

| Actor | Puede hacer | No puede hacer |
| --- | --- | --- |
| Visitante sin cuenta | Ver sesiones disponibles; reservar; gestionar su reserva mediante clave privada. | Buscar usuarios existentes, ver documentos o resultados, administrar sesiones. |
| Recepción | Buscar y confirmar usuarios; revisar posibles duplicados; gestionar reservas y firmas de sus centros asignados. | Consultar o exportar valores de pruebas; gestionar cuentas de personal. |
| Profesional | Consultar identidad mínima de usuarios de sus centros y comprobar documentos; en 500 registrar y consultar resultados. | Administrar cuentas, gestionar reservas de otros centros o exportar resultados de centros no asignados. |
| Administración | Preparar centros, servicios, sesiones y cuentas con roles y centros asignados. | Obtener documentos firmados o resultados por el mero hecho de administrar. |

El servidor comprueba cuenta, permiso y centro en cada operación. Ocultar un botón no basta. El profesional necesita un permiso explícito `exportar_resultados` para CSV. Las cuentas de demostración se crean con datos sintéticos; no existe registro público de personal. Una cuenta desactivada pierde acceso. Roles combinados requieren concesión expresa; no se asignan por defecto.

La ficha de usuario es común entre centros: recepción autorizada puede buscarla. Reservas, documentos y resultados pertenecen a un centro. Una cuenta de Centro Norte puede ver la identidad común de U001, pero no sus documentos o resultados de Centro Sur. El profesional ve solo código, nombre y tipo de atención cuando existe una reserva, documento o resultado de ese usuario en un centro asignado; no recibe contactos. El historial muestra solo registros de centros autorizados; no filtra nombres ni valores de otros centros.

## 4. Datos iniciales y vocabulario

Los recursos incluyen semillas sintéticas, contratos de mensajes, plantillas didácticas, casos de aceptación y un ejemplo que abre sin instalar un servidor. Ese ejemplo es una maqueta local; no demuestra persistencia compartida, permisos de servidor ni reservas simultáneas. Usa una base de pruebas separada para las verificaciones que borran datos.

Dos centros: C01 Centro Norte y C02 Centro Sur. Servicios: SV01 atención individual de 30 minutos y una plaza; SV02 taller de 60 minutos y tres plazas. Administración prepara sesiones concretas; no se programa un motor de disponibilidad recurrente, salas o turnos profesionales. Cada sesión pertenece a un centro y un servicio.

Casos de identidad: U001 persona adulta confirmada; U002 menor representado por R001; U003 y U004 adultos distintos con el mismo teléfono familiar. Los contactos usan dominios reservados para ejemplos y no se envían mensajes. Los códigos son estables y generados por el sistema, no el número de fila de una hoja.

## 5. Modelo mínimo y relaciones

| Entidad | Campos y relaciones esenciales |
| --- | --- |
| Usuario | ID interno, código visible único, nombre, apellidos, fecha de nacimiento, tipo de atención, contacto, estado y fechas de creación/cambio. |
| Representante | Identidad ficticia y contacto; relación explícita con usuario representado. No usar el contacto como identificador único. |
| Cuenta de personal | ID, credencial protegida por el framework, rol, centros asignados, estado y permiso de exportación. |
| Centro y servicio | Código estable y nombre. Servicio con duración y capacidad predeterminada; sesión con capacidad propia positiva. |
| Sesión | Centro, servicio, inicio y final como instantes, zona de presentación y capacidad; estado abierta/cerrada. |
| Reserva | ID, sesión, usuario opcional pendiente de confirmación, prerregistro, canal, estado, clave privada protegida y auditoría de cambios. |
| Plantilla | Código de tipo, versión, texto de demostración y huella. Las versiones publicadas no se sobrescriben. |
| Documento firmado | Usuario, representante si corresponde, reserva, centro, plantilla/versión, copia del texto, imagen, PDF, personal y fecha. |
| Resultado, solo 500 | Usuario, centro, tipo fijo, fecha, valores/unidades, documento de referencia, autor, versión y motivo de corrección. |
| Evento de cambio | Actor de personal o acción mediante clave, entidad, acción, instante y razón; sin contraseñas, claves privadas o imagen de firma. |

El tipo de atención es `adulto` o `menor_representado`, elegido en los casos didácticos. La fecha de nacimiento no decide automáticamente quién puede firmar. Se rechaza una fecha futura. Para el piloto son obligatorios nombre, apellidos y fecha de nacimiento; correo o teléfono basta como contacto. Para menor representado basta el contacto del representante, separado de la persona atendida; no se exige contacto propio del menor. Sexo, población, código postal, procedencia y especialidad son opcionales. El código postal es texto: conserva ceros iniciales. No se recogen dirección ni profesión en esta demostración.

## 6. Registro único y confirmación de identidad

Recepción busca por código o por nombre/contacto antes de crear. Un contacto coincidente muestra una advertencia privada con candidatos, no una fusión ni un bloqueo. Dos familiares pueden compartir teléfono. Una coincidencia exige comparación de nombre, fecha de nacimiento y contexto de la reserva por personal autorizado.

Una reserva pública crea un prerregistro asociado a esa reserva, no un usuario confirmado automáticamente. La respuesta pública es igual exista o no una persona parecida. Recepción decide entre crear usuario, vincular uno confirmado o mantener pendiente. Vincular antes de firmar asigna la reserva a un ID y registra la decisión. No copia ni mueve firmas o pruebas de otro usuario.

Los estados del usuario son `pendiente`, `confirmado` e `inactivo`. No se borra un usuario con historial. Una alta duplicada confirmada por error se marca inactiva y se documenta; no hay fusión automática. Un usuario inactivo no admite nuevas reservas, firmas ni pruebas; su historial autorizado sigue consultable. El historial original permanece donde estaba. Las reservas pendientes pueden revisarse individualmente antes de confirmar, sin trasladar documentos ya firmados.

Prueba de cierre R01: U003 y U004 mantienen códigos distintos pese al contacto compartido. R02: una búsqueda pública no descubre usuarios. R03: una vinculación de prerregistro conserva autor, fecha y reserva; no permite firmar mientras la identidad siga pendiente.

## 7. Sesiones y representación del tiempo

La administración carga sesiones concretas mediante formulario o semillas. La fecha visible se expresa en `Europe/Madrid`; el almacenamiento usa instantes inequívocos y conserva zona. La duración y la capacidad son positivas. Una sesión cerrada o iniciada no admite nuevas reservas.

Las reservas `reservada`, `atendida` y `no_presentada` ocupan una plaza; `cancelada` no ocupa plaza. Las acciones de cancelar o cambiar solo se permiten antes del inicio y sobre estado reservada. Recepción usa la misma regla didáctica; no existe una excepción administrativa oculta. Después del inicio puede marcar atendida o no presentada, conservando la ocupación histórica.

La demo usa un reloj controlado en pruebas y fechas futuras reubicables. Las semillas documentan cómo desplazar las sesiones. Comprueba un instante antes y otro después del inicio. En cambio de hora, una hora local ambigua exige un desplazamiento UTC explícito; una hora local inexistente se rechaza. No resuelvas el problema sumando una hora fija a todas las fechas.

## 8. Reserva pública y presencial

El visitante selecciona centro, servicio y sesión. Ve las plazas disponibles como información orientativa: el servidor vuelve a comprobarlas al guardar. Escribe datos de contacto y de la persona atendida, revisa el resumen y confirma. Un menor representado requiere datos de un representante ficticio; recepción confirmará ese vínculo antes de firmar. La reserva presencial usa el mismo servicio de dominio y las mismas reglas, con una cuenta de recepción autorizada.

El servidor bloquea la fila de sesión durante una transacción, cuenta las ocupaciones válidas y crea la reserva solo si queda plaza. Todos los canales siguen esa ruta. Dos conexiones diferentes que compiten por la última plaza deben producir un éxito y un rechazo, nunca dos éxitos. Un servidor de desarrollo que procesa una petición cada vez no acredita este caso.

El botón deshabilitado evita clics accidentales, pero el servidor también acepta una clave de idempotencia por intento. Repetir el mismo intento con el mismo contenido devuelve la reserva existente, sin consumir otra plaza. Reutilizar esa clave de intento con datos distintos se rechaza. Si se perdió la primera respuesta, no se reconstruye la clave privada a partir de su huella: recepción la restablece, sin crear otra reserva. La restricción única se comprueba en la base. Si falla la transacción, no queda una reserva parcial ni se muestra confirmación.

La respuesta de éxito muestra código de reserva, centro, servicio y hora. Entrega una clave privada impredecible de al menos 32 bytes aleatorios y permite copiarla una vez. Solo se guarda su huella en el servidor; no aparece en logs. El código de usuario o de reserva, sin clave, no autoriza gestión. Sin correo externo, perder la clave conduce a recepción, que verifica el caso ficticio y emite otra anulando la anterior.

## 9. Cancelación, cambio y estados

La clave autoriza únicamente una reserva y deja de servir cuando esta no admite gestión. La consulta mediante GET no cambia nada. Cancelar y reprogramar requieren confirmación y petición de cambio protegida según el framework. Un mensaje de clave inválida no revela quién reservó.

Cancelar cambia reservada a cancelada en una transacción y libera una plaza. Repetir la cancelación no resta plazas otra vez. Reprogramar mantiene el mismo ID de reserva: bloquea sesiones de origen y destino en orden de ID, comprueba destino y actualiza dentro de una sola transacción. Si el destino está lleno, cerrado o ya iniciado, revierte todo y conserva la cita original.

La confirmación de cambio muestra nuevo centro/servicio/hora. Si cambia el centro, requiere revisar qué documento y personal corresponden. Una reserva con documento firmado no se reprograma. Si sigue reservada y aún no ha comenzado, recepción puede cancelarla: conserva el PDF y marca su documento requiere revisión. La nueva reserva es independiente y requiere otra firma; no hereda el documento anterior. Si ya comenzó, se conserva el historial y se rechaza cancelar o cambiar. Esta regla evita trasladar firmas entre actividades.

R04: dos peticiones reales compiten por una plaza. R05: cancelar dos veces libera exactamente una. R06: cambiar a un destino lleno conserva origen e ID. R07: un centro no autorizado y una clave incorrecta no modifican datos. R08: el inicio de la sesión de agenda, el límite horario y el cambio de hora tienen negativos repetibles.

## 10. Documentos y textos de demostración

Tres plantillas fijas: ADULTO-DEMO, MENOR-DEMO y TALLER-DEMO. Sus recursos contienen textos inventados y rotulados como simulación educativa, sin reproducir las cláusulas legales dudosas del cliente. Cada plantilla lleva versión; cambiar un texto genera otra versión y nunca altera una copia firmada.

Se exige usuario confirmado y reserva del centro autorizado en estado reservada o atendida. Cancelada y no presentada no admiten firma. ADULTO-DEMO lo firma el propio adulto; MENOR-DEMO identifica al usuario atendido y al representante firmante por separado. TALLER-DEMO se registra por asistente y sesión. El piloto solo admite adultos en talleres. La reserva de taller con tipo menor_representado se rechaza con una explicación, sin ocupar plaza. El caso de menor en taller queda para decisión posterior del cliente. Esta es una regla de la demo, no una afirmación legal.

Asistir sin reserva requiere que recepción cree una reserva presencial antes del inicio y con plazas. No se inventa una plaza extra ni se firma sobre un usuario sin vínculo con la sesión. El coordinador puede mostrar un error y la recuperación como parte de la demo.

## 11. Captura de la rúbrica

La pantalla muestra tipo de documento, usuario, firmante, centro, actividad y texto completo antes de confirmar. Usa eventos de puntero para ratón, lápiz y dedo; conserva proporción de la zona de dibujo al redimensionar. Los controles de borrar, repetir, volver y confirmar tienen etiquetas y funcionan con teclado, excepto el trazo manual en sí.

Una imagen vacía o un trazo inexistente se rechaza también en servidor. Aplica límites de tamaño y formato: PNG, hasta 1 MB y dimensiones máximas 1600 por 800 en esta demo. No confíes en el nombre del fichero ni aceptes contenido SVG o HTML como imagen. El servidor valida el contenido y guarda archivos fuera del directorio público.

Antes de persistir, vuelve a comprobar permiso, centro, identidad confirmada, representante, versión vigente y reserva en estado reservada o atendida. El usuario no puede cambiar esos IDs mediante el formulario. Tras guardar, la sesión de firma muestra un justificante mínimo y limpia el lienzo y datos antes del siguiente asistente. Volver con el navegador no debe mostrar el documento del anterior.

## 12. PDF, conservación y corrección

El PDF incluye texto completo, versión, usuario y firmante ficticios, actividad, fecha, centro y rúbrica. Se genera desde datos controlados, sin HTML remoto ni recursos externos. Su descarga pasa por un controlador autorizado; conocer una ruta o un ID no permite acceder sin permiso.

La captura usa el ID pendiente emitido por servidor. Bajo bloqueo, repetir el mismo ID y huella devuelve el documento existente; otro contenido exige corregir con nueva versión. Solo existe una versión actual por reserva, sin borrar anteriores. Solo se marca firmado cuando imagen, texto y PDF recuperable están guardados. Si falla la generación o el almacenamiento, se mantiene pendiente y se informa cómo reintentar; se limpian archivos temporales. La operación no debe dejar un estado firmado con un archivo ausente. Las pruebas inducen ese fallo de manera controlada.

Los estados son pendiente, firmado y requiere revisión. Un cambio no sobrescribe el documento: registra motivo, conserva el PDF anterior y crea una nueva versión pendiente. El anterior pasa a requiere revisión; el nuevo solo pasa a firmado tras otra captura y PDF correctos. Si falla, queda pendiente y ninguno habilita una prueba nueva. Un documento marcado requiere revisión no sirve como requisito para registrar una prueba nueva; el historial anterior permanece visible para personal autorizado. El recurso explica que esta trazabilidad técnica no certifica validez jurídica.

R09: firma vacía rechazada. R10: adulto, menor y taller vinculan firmante y usuario correctos. R11: un taller limpia datos entre asistentes. R12: PDF recuperable conserva exactamente el texto/versión y rechaza descarga sin permiso. R13: fallo de PDF no deja un registro firmado. R14: corregir conserva original y motivo.

## 13. Pruebas fijas del itinerario de 500 horas

La ampliación empieza después del recorrido completo de registro, agenda y firma del sprint 6. Antes de la ampliación se exige superar el núcleo; las tareas comunes pueden comenzar para corregirlo. Las tareas de registrar, consultar, corregir y exportar siguen presentes en ambos planes; en 370 trabajan sobre datos y documentos del núcleo, y en 500 añaden resultados. No crees una tarea dedicada a pruebas que quede vacía en 370.

Bioimpedancia: altura, peso, porcentaje de grasa, grasa visceral, porcentaje de agua, músculo, hueso, TMB e IMC. Bioquímica: vitamina D, calcio, fósforo y magnesio, junto con código postal y fecha del informe como metadatos. Densitometría: T score, Z score, BQI, BUA, SOS, FRAX cadera, FRAX osteoporosis y EVA. Los nombres provienen de F4–F6; no se calculan IMC, TMB o FRAX ni se interpretan resultados.

Cada esquema es fijo y versionado. Tipo, fecha y al menos una medida son obligatorios; las demás medidas son opcionales. Todos los decimales admiten hasta tres cifras decimales y nueve cifras totales. FRAX se trata como porcentaje de demostración y EVA es opcional. El diccionario didáctico aporta tipo, unidad de demostración y ejemplos. Las unidades no explicitadas por las fuentes quedan etiquetadas como `unidad_demo`, pendientes de confirmación para cualquier uso real. En la demo se admite decimal finito; porcentajes entre 0 y 100; EVA entre 0 y 10; el resto tiene controles de formato y tamaño, sin intervalos sanitarios. T y Z admiten valores negativos. No se exige completar una prueba inventando datos: los campos opcionales vacíos se guardan como nulos, nunca como cero.

Para registrar se exige usuario confirmado, cuenta profesional del centro y referencia a un documento firmado no marcado requiere revisión de ese usuario/centro: ADULTO-DEMO para adulto y MENOR-DEMO para menor representado. TALLER-DEMO no habilita resultados. El profesional puede consultar resultados de otros autores de sus centros autorizados; la autoría no concede acceso a otros centros. Es una precondición didáctica, no una evaluación de consentimiento. La fecha del resultado no es futura respecto al reloj de prueba. Los valores y unidades se validan en servidor; recepción y administración sin permiso profesional no pueden leerlos.

## 14. Historial, corrección y exportación

El historial separa reservas, documentos y, en 500, resultados. Muestra fecha, estado y versión; filtros por centro, usuario, tipo y periodo no alteran autorizaciones. Un estado vacío explica que no hay registros accesibles, sin revelar que existen otros ocultos.

Corregir un resultado crea una versión con autor, fecha, motivo y referencia a la anterior. La vista distingue vigente de sustituido; no sobrescribe valores históricos. La exportación ordinaria incluye solo versiones vigentes del filtro, y conserva un ID de versión para contrastarlas.

En 370 recepción exporta ocupación agregada por sesión de sus centros: centro, servicio, sesión, inicio, capacidad, reservadas, atendidas, no presentadas, canceladas y libres. No incluye nombre, contacto, código de usuario, documentos ni claves privadas. En 500, el permiso `exportar_resultados` habilita CSV por tipo de prueba con columnas estables del diccionario. La variante sin nombre conserva código de usuario e ID de versión: sigue siendo identificable dentro del sistema y no se presenta como anónima. Nombres y apellidos se omiten realmente del archivo, no solo de la pantalla.

CSV UTF-8 con coma como delimitador, punto decimal y cabecera fija; el recurso explica cómo abrirlo en una hoja de cálculo. Escapa delimitadores, comillas y saltos. Las celdas de texto que empiezan por signos de fórmula se neutralizan para que se abran como texto. Un valor numérico negativo válido sigue siendo número: no confundir protección de texto con cambiar medidas. Ningún CSV contiene claves privadas, credenciales o imágenes de firma.

R15: unidades y nulos se conservan y negativos permitidos no se rechazan. R16: recepción no consulta resultados por URL directa. R17: una corrección conserva original y autor. R18: filtros y permisos coinciden entre vista y CSV; la variante sin nombre no contiene esos campos.

## 15. Contratos entre parejas

Antes de implementar un módulo se acuerdan campos, estados, ejemplos y respuestas de error en el recurso de contratos. Frontend trabaja contra respuestas de ejemplo y estados vacíos, carga, error y éxito. Backend implementa el mismo contrato, manteniendo los nombres de campo. Datos y experiencia prepara esquemas, semillas, textos y pruebas de comprensión. Calidad crea pruebas repetibles y datos límite. Integración mantiene arranque, rutas, migraciones, revisión y unión de cambios.

Un cambio de contrato se registra antes de programarlo: motivo, campos, ejemplo anterior/nuevo y pruebas afectadas. No se rompen dependencias silenciosamente. Los dobles y semillas permiten avanzar de forma aislada; no cuentan como integración real. Cada sprint termina ejecutando el recorrido con el backend integrado disponible en esa etapa.

## 16. Apoyo junior y resolución de bloqueos

Empieza por un ejemplo pequeño, cambia una cosa y comprueba su efecto antes de abordar el caso completo. Los recursos incluyen ejemplo inicial, diccionario, recetas, casos positivos/negativos y plantilla de evidencia. Si falta una tecnología, usa la ruta sin instalación para UI, contratos y datos mientras se resuelve el entorno; las tareas del servidor no se dan por terminadas con la maqueta.

Tras 20 minutos sin progreso, registra comando/acción, resultado esperado, resultado obtenido y prueba mínima. La pareja revisa; si no puede resolverlo, deja una petición concreta al coordinador y toma una subtarea independiente del mismo sprint. El siguiente punto de control revisa el incidente. Las horas consumidas siguen dentro del presupuesto; no se añaden jornadas invisibles.

El coordinador debe comprobar entorno y asignar un apoyo técnico antes de comenzar. La falta persistente de equipo, permisos o acompañamiento se registra como condición no disponible; no se promete un servidor o una persona inexistentes. La planificación ofrece continuidad de trabajo, no una garantía de acceso que todavía no se ha probado.

## 17. Organización y contribución individual

Cinco parejas estables trabajan en Frontend, Backend, Datos y experiencia, Calidad e Integración y Team Leader. Las cuatro parejas de Frontend, Backend, Datos y experiencia y Calidad rotan tras S3, S5 y S8. La pareja de Integración y Team Leader permanece estable: la plataforma asigna sus tareas al Team Leader operativo, que coordina la aportación técnica de ambos. Dentro de cada pareja alternan construcción y revisión; las prácticas individuales cubren otras capacidades. Dentro de cada pareja alternan quien implementa y quien comprueba. La función Integración y Team Leader incluye dos estudiantes y un único Team Leader operativo designado en plataforma. La facilitación interna rota sin prometer cambios automáticos de permisos de plataforma; no es una undécima persona ni sustituye al coordinador externo.

En cada sprint hay una tarea de equipo por función y una tarea individual de cinco horas por persona. La tarea individual permite repetir y explicar una variante técnica sin depender del compañero. La evidencia compartida identifica aportaciones y pruebas; los diez estudiantes deben demostrar trabajo técnico, incluidos quienes investigan, diseñan, prueban o facilitan.

Cada tarea funcional reserva aproximadamente 25 % para aprender/ensayar, 45 % para construir, 20 % para verificar/corregir y 10 % para coordinación/traspaso. Son márgenes de planificación ajustables dentro de la misma cifra. Una revisión cruzada intercambia tiempo entre parejas y se contabiliza una vez, no como trabajo gratuito de quien revisa.

## 18. Calidad y experiencia de uso

La interfaz se construye por etapas: estructura semántica, mensajes y formularios; después componentes consistentes, móvil y refinamiento. Usa dos anchuras de prueba, 360 y 1280 píxeles, más zoom del navegador. Formularios con etiquetas, foco visible, errores junto al campo y resumen, mensajes sin datos sensibles y confirmación clara antes de acciones que cambian estado.

Las pruebas incluyen teclado, texto largo, listas vacías, imagen ausente, petición rechazada, recarga y doble envío. Guardar un borrador útil o conservar campos ante error reduce pérdida de trabajo. No confundir una captura bonita con una prueba de concurrencia, permisos o persistencia.

## 19. Evidencias y aceptación

Cada ficha solicita un PDF de hasta tres páginas y 10 MB en el campo de evidencia de la tarea. Incluye versión o commit, caso y entrada, resultado esperado/obtenido, captura o fragmento de salida, contribución y corrección aprendida. Código, datos y comandos permanecen en el repositorio del equipo identificado por versión; no se sustituyen por pantallazos sin contexto.

Los criterios críticos son binarios: no sobreventa; no pérdida de cita ante cambio fallido; no exposición entre roles/centros; identidad y documento correctos; original conservado al corregir; reproducción desde instrucciones. Un extra visual no compensa un fallo de esos criterios. Se ofrece un ejemplo más pequeño y otra oportunidad de comprobación dentro del tiempo de corrección.

## 20. Entrega reproducible

Otra pareja instala la aplicación desde una versión etiquetada, dependencias bloqueadas tras instalación real, configuración de ejemplo y semillas sintéticas. Crea una base vacía, migra, carga datos, ejecuta pruebas y repite reserva/firma. Separa claves locales de archivos compartidos y documenta las cuentas ficticias.

El paquete de la aplicación incluye las imágenes de ejemplo permitidas y las firmas sintéticas necesarias para repetir la demostración; documenta cómo regenerarlas. Los PDF de demostración pueden regenerarse a partir de semillas y plantillas. Las rutas no dependen del ordenador del autor. Cierra con límites conocidos y pasos para continuar; no declara preparación para datos reales.

## 21. Trazabilidad y consultas pendientes

R01–R03 derivan de F7 registro y de los ejemplos F1/F3. R04–R08 concretan F7 agenda compartida. R09–R14 cubren F7 firmas y los tres ejemplos F1/F2/F3. R15–R18 cubren la segunda fase F7 y campos F4/F5/F6. Los controles de permisos, concurrencia, unidades y evidencia son decisiones técnicas y docentes, no textos literales del cliente.

Las consultas al cliente permanecen sobre finalidad de documentos, campos reales, representantes, centros, plazos, unidades, estudios y convivencia con Bookings. Para este MVP se usan las reglas didácticas anteriores y se marcan como tales. Una respuesta posterior se incorpora al guion, a las tareas afectadas y a sus pruebas; no se cambia solo la interfaz.

La matriz de trazabilidad vincula cada regla con construcción, comprobación y evidencia individual. Los agentes y Claude revisan contenido, capacidad y estructura documental. La aceptación del lector de la plataforma, la vista del alumno, el entorno de cada estudiante y la tableta real requieren sus propias comprobaciones; una revisión local no las sustituye.
