# Organización y capacidad del equipo

FM-WEB-01 · Guía práctica para diez estudiantes DAW junior · Planes alternativos de 370 y 500 horas por persona.

Elige el itinerario que te indique el coordinador y consulta tu columna P01–P10 en la matriz correspondiente. Las horas de ambos planes no se suman. Planificar una hora significa asignarle un trabajo; no demuestra que ese trabajo ya se haya ejecutado.

## 1. Localiza tu pareja, función y presupuesto

Los archivos [MATRIZ_CARGA_370.csv](MATRIZ_CARGA_370.csv), [MATRIZ_CARGA_500.csv](MATRIZ_CARGA_500.csv) y [ROTACION.csv](ROTACION.csv) contienen el reparto por tarea y persona. Abre los CSV con separador coma y codificación UTF-8. Busca el ID de la ficha, comprueba la columna `funcion` y lee solo las horas de tu persona. Un cero significa que esa fila no te asigna horas inicialmente.

El coordinador asocia los nombres reales a P01–P10 antes de arrancar. No copies nombres, contactos o credenciales en los datos de la aplicación. Cinco parejas de dos estudiantes realizan trabajo técnico:

| Función | Construcción y comprobación que aporta |
| --- | --- |
| Frontend | Formularios, mensajes, navegación, foco y recorrido conectado al servidor. |
| Backend | Validación, autorización, persistencia, transacciones y generación/recuperación de PDF. |
| Datos y experiencia | Diccionario, migraciones/semillas, consultas de integridad, textos y pruebas de comprensión. |
| Calidad | Casos reproducibles, pruebas automatizadas pequeñas, defectos y repetición de cierres. |
| Integración y Team Leader | Arranque, revisión e integración de código, migraciones, regresión y traspaso. |

Integración y Team Leader es una sola función compuesta y ocupa dos estudiantes. Ambos construyen y prueban. Solo hay un Team Leader operativo designado en la plataforma; la facilitación del equipo puede rotar internamente sin cambiar automáticamente los permisos de la plataforma. No existe una undécima plaza de alumno. El coordinador externo acompaña y decide prioridades, pero no sustituye las aportaciones técnicas de la pareja.

## 2. Cambia de función después de S3, S5 y S8

Las cuatro parejas P01–P08 cambian de función al iniciar S4, S6 y S9; P09–P10 permanece en Integración y Team Leader. Este cuadro reproduce `ROTACION.csv`:

| Pareja | S1–S3 | S4–S5 | S6–S8 | S9–S10 |
| --- | --- | --- | --- | --- |
| P01–P02 | Frontend | Backend | Datos y experiencia | Calidad |
| P03–P04 | Backend | Datos y experiencia | Calidad | Frontend |
| P05–P06 | Datos y experiencia | Calidad | Frontend | Backend |
| P07–P08 | Calidad | Frontend | Backend | Datos y experiencia |
| P09–P10 | Integración y Team Leader | Integración y Team Leader | Integración y Team Leader | Integración y Team Leader |

Antes del relevo deja versión, archivos, caso válido, caso negativo, comandos, incidencia pendiente y siguiente acción. La pareja receptora repite un caso desde esas instrucciones y registra dudas. Revisa el primer cambio de quien recibe; el tiempo de explicar, comprobar y corregir ya forma parte de las tareas del sprint.

Dentro de cada pareja alternad quien implementa y quien comprueba. Si una persona preparó las semillas, la otra ejecuta la consulta que demuestra su coherencia; después cambiáis en la siguiente pieza. La revisión no implica limitarse a mirar una pantalla.

## 3. Cuenta las horas una sola vez

Cada sprint contiene cinco tareas funcionales de pareja y una tarea individual de cinco horas para cada estudiante. Las diez tareas individuales suman 50 horas por persona dentro del total de 370 o 500. No son un añadido al final.

| Sprint | Horas por persona en 370 | Horas por persona en 500 | De esas horas, tarea individual |
| --- | ---: | ---: | ---: |
| S1 | 35 | 45 | 5 |
| S2 | 40 | 48 | 5 |
| S3 | 40 | 48 | 5 |
| S4 | 40 | 48 | 5 |
| S5 | 40 | 48 | 5 |
| S6 | 40 | 56 | 5 |
| S7 | 40 | 56 | 5 |
| S8 | 32 | 56 | 5 |
| S9 | 32 | 48 | 5 |
| S10 | 31 | 47 | 5 |
| Total | 370 | 500 | 50 |

Ejemplo: la tarea 5.7.1 asigna 70 h-equipo en el plan de 370. P05 y P06 tienen 35 horas cada uno para ella y otras cinco para su tarea individual: 40 horas por persona en S7. En el plan de 500, la misma tarea tiene 102 h-equipo: 51 por persona más cinco individuales, 56 en el sprint. Nadie debe apuntarse 70 o 102 horas por separado.

Como orientación, reparte cada tarea funcional en 25 % de aprendizaje/ensayo, 45 % de construcción, 20 % de comprobación/corrección y 10 % de coordinación/traspaso. Ajusta ese reparto dentro del presupuesto. Aprender, pedir ayuda, revisar un cambio y redactar evidencia consumen horas; no se facturan otra vez como una tarea adicional invisible.

Si Calidad dedica 30 minutos a revisar un cambio de Backend, Calidad registra esos 30 minutos en su tarea asignada. Backend registra su propio tiempo de preparación o corrección, no los mismos minutos como si los hubiera trabajado. Si se necesita una reasignación mayor, el coordinador actualiza el reparto manteniendo total por persona y por sprint; no añade horas a ambos perfiles.

Las fechas y franjas reales se acuerdan con el coordinador. Las horas de esta tabla no presuponen jornadas iguales ni que todos estén conectados a la vez. Al acabar tu sesión anota tiempo real y restante; si el trabajo no cabe, comunica qué criterio sigue pendiente antes de ampliar funcionalidades.

## 4. Trabaja en sesiones asíncronas cortas

1. Lee al empezar el último estado de tu pareja y las peticiones de revisión. Atiende primero una revisión que desbloquee a otra persona.
2. Selecciona una pieza comprobable de la tarea: un campo, una transición o una consulta. Mantén una tarjeta principal en curso por persona.
3. Acordad entrada, estados y respuesta antes de conectar interfaz y servidor. Si usas un doble, identifícalo como simulado.
4. Construye la pieza, ejecuta caso válido y caso negativo y anota salida. Abre revisión cuando ya se pueda comprobar algo pequeño.
5. Antes de salir, deja cuatro datos: versión/archivo, qué comprobaste, qué falta y quién puede continuarlo. Tu pareja responde al iniciar su siguiente sesión.

El objetivo y los cambios de prioridad quedan por escrito. Una reunión voluntaria se resume para quien no pudo asistir. El tablero puede ser un Markdown o una hoja compartida; no necesita una cuenta de pago. Usa por hacer, en curso, en revisión y terminado, con bloqueo visible. Una maqueta permite avanzar, pero no cierra persistencia, permisos ni integración.

## 5. Pide ayuda y cambia de actividad sin esconder el bloqueo

Dedica hasta 20 minutos a una hipótesis concreta usando [Ayuda y registro de evidencias](09_EVIDENCIAS.md). Si no avanzas, deja incidencia con acción/comando, esperado, obtenido, archivo y ayuda necesaria. No esperes a agotar el tiempo si hay un riesgo para datos compartidos. Tras esos 20 minutos, deja registrada la incidencia y solicita apoyo; si la pareja no resuelve, escala al coordinador.

El primer montaje del entorno tiene un límite de intento de 45 minutos en [Montaje del entorno de desarrollo](04_MONTAJE.md). Puedes pedir ayuda antes; 45 minutos no es una espera obligatoria ni un permiso para ocultar el bloqueo hasta entonces. Al alcanzar ese límite continúa con la maqueta, los contratos o los casos mientras Integración y Team Leader y el coordinador resuelven la condición técnica.

| Causa | Acción comprobable ahora | Trabajo útil mientras se resuelve |
| --- | --- | --- |
| Comando o extensión ausente | Registra comando, ruta, versión y mensaje; sigue diagnóstico de montaje. | Abre `laboratorio/index.html` y prepara entrada válida/inválida. |
| Servidor de otra pareja pendiente | Compara nombres de campo y respuesta del contrato. | Construye estados visuales con respuesta simulada o prueba el servicio disponible. |
| No coincide el diccionario | Identifica campo, ejemplo anterior/nuevo y efecto. | Prepara ambos casos y pide decisión antes de cambiar el contrato. |
| No hay revisor conectado | Deja cambio pequeño con pasos y salida esperada. | Revisa otra tarjeta o mejora una prueba; el coordinador reasigna si persiste. |
| Falta cuenta de IA o cuota | Usa la respuesta preparada de la guía. | Contrasta y corrige el ejemplo sin contratar nada. |
| Falta tableta real | Anota la condición no disponible. | Ensaya ratón/emulación, identificándolos; la prueba física queda pendiente. |
| Falla el núcleo al terminar S6 | Registra el caso y pide prioridad al coordinador. | Corrige registro, agenda o firma antes de activar resultados de 500 h. |

La ayuda y la alternativa consumen el mismo presupuesto. Una respuesta pendiente del cliente no autoriza inventar una regla sanitaria o jurídica; usa el supuesto didáctico explícito y registra la consulta. Una ampliación no ejecutada conserva estado pendiente, aunque su preparación sea útil.

## 6. Acredita tu aportación durante los diez sprints

La tarea individual de cinco horas de cada sprint sirve para repetir una variante y explicar una decisión propia. Identifica archivo, entrada, prueba y resultado. No copies el PDF del compañero cambiando el nombre. La introducción a IA de 90 minutos y a Scrum de 60 minutos se incluye una sola vez en la tarea individual del primer sprint; quedan 150 minutos para diagnóstico y cambio del ejemplo.

En las tareas de equipo identifica qué construyó y qué comprobó cada integrante. Quien prepara datos explica una migración o consulta; quien diseña experiencia modifica y prueba un componente o mensaje; quien facilita identifica su cambio técnico y su verificación. El resultado permite revisar las competencias de la ficha; no certifica por sí solo una competencia oficial ni el dominio completo de una tecnología.

Para terminar una tarea, comprueba el comportamiento y un caso negativo, conserva evidencia, recibe revisión y actualiza las instrucciones afectadas. El PDF de cada ficha tiene hasta tres páginas y 10 MB y se sube al campo de evidencia de esa tarea. Los archivos técnicos y comandos permanecen en el repositorio identificado por versión. Consulta [Git y revisión de cambios](14_GIT.md) para preparar la revisión.

## 7. Copia una tarjeta y un acta de decisión

Ejemplo de tarjeta preparada; sus salidas están **NO-VERIFICADAS** hasta ejecutar:

```text
Tarea: 5.7.1 · Mejorar formularios y añadir campos de demostración
Itinerario: 370 h. Pareja: P05–P06. Presupuesto de tarea: 35 h por persona.
Pieza: el contacto inválido conserva el nombre y recibe foco.
Autora de esta pieza: P05. Revisor: P06.
Archivo y versión: completar con la ruta y versión realmente usadas.
Entrada: U001; correo sintético sin arroba; nombre ya escrito.
Esperado: rechazo junto al contacto, nombre conservado, foco en el error.
Obtenido: NO-VERIFICADO; todavía no se ha ejecutado.
Tiempo real P05/P06: completar; no copiar el presupuesto como tiempo consumido.
Bloqueo: respuesta del servidor pendiente, si ocurre.
Siguiente acción: comprobar contrato y preparar respuesta simulada etiquetada.
Cierre pendiente: conectar servidor, repetir y preparar evidencia.
```

Plantilla de acta; rellena fecha y decisión cuando ocurran:

```text
Fecha y participantes:
Tarea, versión y problema observado:
Datos de prueba y resultado:
Opciones consideradas y efecto sobre núcleo/ampliación:
Decisión del coordinador o acuerdo técnico dentro del alcance:
Campo, contrato, archivo y prueba que cambian:
Persona autora y revisora:
Horas ya consumidas y capacidad restante por persona:
Qué se deja pendiente y por qué:
Siguiente acción y punto de comprobación acordado:
Resultado de esa comprobación: NO-VERIFICADO hasta ejecutar.
```

El relevo final incorpora incidencias, manuales y versión reproducible. Anota lo observado sin certificar importación en plataforma, funcionamiento en dispositivos no ensayados o preparación para datos reales.


## 8. Asignación en plataforma

Las cuatro parejas de Frontend, Backend, Datos y experiencia y Calidad rotan tras S3, S5 y S8. P09–P10 permanece en Integración y Team Leader: designa a uno como Team Leader operativo, que recibe las tareas de esa función y coordina la aportación técnica de ambos. Dentro de cada pareja alternan construcción y revisión; las prácticas individuales cubren otras capacidades. Comprueba el reparto tras importar, porque responsable nominal y horas aportadas por dos personas son conceptos distintos.
