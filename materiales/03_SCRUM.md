# Scrum y colaboración del equipo

FM-WEB-01 · Recurso del alumno · Introducción de 60 minutos dentro de la acogida. Después se aplica durante los sprints, dentro de sus horas previstas. No necesitas una cuenta especial ni aprobar un examen para avanzar.

## 1. Lo que debes distinguir

Scrum propone aprender con resultados pequeños, inspeccionarlos y adaptar el trabajo. El Product Owner ordena el trabajo por valor; el Scrum Master ayuda a aplicar el marco; Developers reúne a quienes construyen el incremento, con distintas especialidades. Un sprint tiene un objetivo y produce un resultado utilizable. El backlog del producto reúne trabajo pendiente; el del sprint refleja la selección y cómo realizarla. La revisión examina el producto; la retrospectiva mejora cómo trabajáis. La definición de terminado permite saber qué reúne calidad suficiente. [Guía Scrum oficial en español, noviembre de 2020](https://scrumguides.org/docs/scrumguide/v2020/2020-Scrum-Guide-Spanish-European.pdf).

Aquí aprendéis esos conceptos y utilizáis una **adaptación educativa asíncrona inspirada en Scrum**. No se exige coincidir en una videollamada ni se presenta un mensaje de estado como cumplimiento literal del Daily Scrum. El calendario y las horas son los del roadmap. El coordinador externo acompaña; no cuenta entre los diez alumnos. La pareja de Integración asume un portavoz/facilitador rotativo, que también construye y prueba; no es una plaza adicional ni equivale automáticamente a Scrum Master o Product Owner.

## 2. Elegir un resultado — 15 minutos

Ejemplo de objetivo de producto: «Recepción puede registrar a una persona ficticia, reservar una cita y recuperar su documento firmado sin mezclar identidades».

Ejemplo de un sprint de registro: «Recepción crea y recupera una ficha válida y recibe ayuda cuando detecta un contacto compartido». El objetivo es una capacidad observable, no «terminar frontend y backend».

Ordena este backlog de ejemplo y justifica la selección:

| Elemento | Criterio observable | Dependencia |
|---|---|---|
| Crear una ficha de persona ficticia | Guarda campos acordados y muestra su código estable | Contrato de datos y entorno preparados |
| Buscar antes de crear | Encuentra el registro por los campos acordados | Datos de ejemplo y consulta básica |
| Avisar de contacto compartido | Permite revisar sin fusionar ni bloquear automáticamente | Regla del guion funcional |
| Rechazar un alta inválida | Señala el campo y conserva entradas útiles | Reglas de validación acordadas |
| Consultar sin permiso | La cuenta no autorizada no obtiene la ficha | Cuentas de prueba y autorización |

Selecciona lo que cabe en las horas del sprint. Si un elemento es demasiado grande, divídelo en acciones que puedas comprobar en una sesión de trabajo. Las horas sirven para planificar la práctica; los puntos de historia no son obligatorios.

## 3. Montar el tablero — 15 minutos

Usa Markdown, una hoja o la herramienta acordada. Columnas: por hacer, en curso, en revisión y terminado. Cada tarjeta tiene ID de tarea del roadmap, resultado pequeño, persona autora, persona revisora, horas previstas, dependencia, prueba y enlace a evidencia. Marca bloqueos sin esconder la tarjeta.

Ejemplo:

```text
ID del roadmap: copiar el ID real de la tarea de formulario.
Resultado: el alta avisa de fecha inválida y conserva el nombre escrito.
Autor: alumno asignado. Revisor: compañero designado.
Estado: en curso. Presupuesto: parte del asignado a esa tarea.
Entrada: nombres de campos y regla de fecha del guion.
Prueba: fecha imposible → no se guarda y aparece mensaje junto al campo.
Enlace: rama o PR. Bloqueo: ninguno / describir con siguiente acción.
```

Límite de trabajo: una tarjeta principal por alumno. Revisar una tarjeta ajena tiene prioridad sobre abrir una tercera función. Un rol señala dónde aporta más ayuda; no impide cambiar de actividad o aprender otra parte.

## 4. Revisar y mejorar — 20 minutos

Para este ejercicio, «terminado» exige: comportamiento acordado comprobado, un caso inválido o de acceso denegado, cambio revisado por otra persona, instrucciones actualizadas y evidencia localizable. Esta es la definición común; cada función conserva sus criterios particulares. No basta con que abra una pantalla.

Revisión: recorre una ficha de ejemplo y compara con el criterio. Si aún no hay aplicación, usa un boceto y señala que solo revisas el diseño. Decide qué está comprobado y qué debe volver al tablero. No presentes una simulación como funcionalidad construida.

Retrospectiva: registra un problema del proceso, una mejora pequeña, quién la probará y cuándo. Ejemplo: «Las revisiones llegan al final; desde el siguiente sprint abriremos PR al completar el primer caso y revisaremos una al iniciar nuestra sesión». Comprueba después si baja el trabajo esperando revisión.

## 5. Explicarlo — 10 minutos

Entrega objetivo, cinco tarjetas de ejemplo, criterio comprobado y mejora del proceso. Cada alumno explica una decisión propia. Sin compañeros conectados, revisa el ejemplo de esta guía y deja comentarios para su lectura posterior. Se puede recuperar el ejercicio sin detener las tareas principales.

## 6. Cómo colaboran diez personas sin horarios comunes

El coordinador y el grupo concretan nombres y reparto en la matriz del sprint. Son cinco parejas de dos personas, una por función. Cada persona produce y revisa trabajo técnico. Las cuatro parejas de Frontend, Backend, Datos y experiencia y Calidad rotan tras S3, S5 y S8. La pareja de Integración y Team Leader permanece estable: la plataforma asigna sus tareas al Team Leader operativo, que coordina la aportación técnica de ambos. Dentro de cada pareja alternan construcción y revisión; las prácticas individuales cubren otras capacidades. Rotar no significa abandonar una tarea a medias: se entrega estado, archivo, prueba y siguiente paso antes del relevo.

| Función de apoyo | Trabajo desde el inicio | Cuando otra parte todavía no está integrada |
|---|---|---|
| Frontend | Formulario pequeño y estado de error con datos ficticios | Pantalla conectada a un ejemplo local con el contrato acordado; integración pendiente visible |
| Backend | Regla de validación y persistencia de un caso | Prueba de servicio con datos sintéticos y respuesta acordada |
| Datos y experiencia | Campos, reglas, datos ficticios, recorrido y etiquetas | Preparar datos de prueba e implementar un componente accesible |
| Calidad | Casos válidos/negativos y pruebas automatizadas pequeñas | Probar la última pieza disponible, revisar teclado y reproducir un error |
| Integración | Arranque reproducible, conexión de piezas y facilitación rotativa | Revisar un cambio pequeño, mejorar la guía y preparar el ensayo de integración |

Las cinco funciones suman diez alumnos. Ayudarse entre funciones no añade plazas ni horas. La matriz individual evita asignar a cada perfil el presupuesto completo de una tarea compartida. Cada sprint tiene cinco tareas de equipo y una tarea individual de 5 h por alumno: sesenta tareas en diez sprints. En la individual del sprint 1, IA ocupa 90 min, Scrum 60 min y diagnóstico/variante los otros 150 min. Cada alumno produce un cambio técnico explicable y una revisión ajena; quien prepara datos o experiencia también programa y prueba.

Al empezar tu sesión lee cambios y peticiones de revisión. Antes de salir deja: qué comprobaste, enlace, qué falta y quién puede continuarlo. Un mensaje escrito de cuatro líneas suele bastar. La pareja revisa cuando inicia su siguiente sesión, sin necesidad de estar conectados a la vez. Si no puede atender, avisa y el grupo reasigna la revisión.

## 7. Planificar y contar el tiempo

Las ventanas de planificación, revisión y retrospectiva se anuncian por escrito, con tiempo para que todos participen en su horario. El grupo documenta el objetivo antes de desarrollar; el coordinador recoge decisiones pendientes. Una reunión voluntaria se resume para quienes no asistieron.

Ejemplo de reserva de tiempo por persona dentro de un sprint: 30 min para leer/proponer el plan, 5 min de actualización por jornada efectivamente trabajada, 30 min de revisión de producto y 15 min de retrospectiva. Para cinco jornadas son 100 min, ya descontados del tiempo de las tareas. Es una pauta ajustable, no horas extra ni duración obligatoria de los eventos oficiales de Scrum. La revisión de código y la resolución de errores se imputan a su tarea técnica, no se duplican aquí.

La introducción de 60 min se registra una sola vez. No se vuelven a sumar 60 min por sprint. Anota el tiempo real y contrástalo con el presupuesto individual del plan de 370 o de 500 h. Antes de ampliar funcionalidades, comprueba el núcleo y la capacidad restante. Si algo no cabe, el coordinador ayuda a reducir trabajo secundario o a solicitar una decisión de alcance; las horas no se alargan automáticamente.

Fuente oficial consultada el 24/09/2026. El tablero, los ejemplos y la cadencia asíncrona son decisiones educativas de esta práctica, no prescripciones de la Guía Scrum.


## Acuerdo final del proyecto

La función formal de integración se llama «Integración y Team Leader» y tiene dos estudiantes. Un único Team Leader operativo se designa en plataforma; la facilitación interna puede alternarse sin prometer cambios automáticos de permisos. Los periodos son S1–S3, S4–S5, S6–S8 y S9–S10; las cuatro parejas rotatorias traspasan tras S3, S5 y S8; Integración y Team Leader mantiene sus integrantes. Las tareas del propio Team Leader las contrasta Calidad y el coordinador valora el aprendizaje. Todas las reuniones o mensajes de revisión cuentan dentro de las horas de las tareas.
