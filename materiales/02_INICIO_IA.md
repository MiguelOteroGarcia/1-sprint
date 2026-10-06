# Inicio práctico con IA

FM-WEB-01 · Recurso del alumno · 90 minutos estimados dentro de la acogida del sprint 1. Puedes repartirlos en varias sesiones. La falta de cuenta, cuota o herramienta no impide continuar: al final de esta guía tienes una respuesta preparada para trabajar sin IA.

## 1. Qué vas a aprender

Preparar el contexto de un proyecto, pedir una ayuda pequeña, revisar una respuesta y explicar qué has comprobado. Utiliza datos inventados. El ejercicio no requiere una suscripción, una tarjeta, una API ni instalar un agente. Si ya tienes acceso autorizado a una herramienta, puedes aprovecharlo.

Un chat responde a mensajes. Un proyecto o un Gem conserva instrucciones y materiales para reutilizarlos. Un agente de IDE puede leer o cambiar archivos y ejecutar comandos según sus permisos. No son lo mismo: conocer tu proyecto no significa que haya probado el código, y una cuenta web no acredita acceso a otra herramienta.

## 2. Preparar un proyecto con contexto — 20 minutos

1. Dentro de tu copia del proyecto crea `docs/ia/CONTEXTO.md`, `ACUERDOS.md` y `TAREA.md`. Copia las plantillas siguientes y escribe tu nombre o identificador de alumno donde corresponda.
2. Abre la herramienta web que tengas disponible. Si permite proyectos o instrucciones persistentes, crea un espacio llamado «Fundación · práctica». Si solo permite chat, pega los tres textos en una conversación nueva.
3. En Gemini, la ruta documentada es barra lateral → Gems → Nuevo Gem; escribe nombre e instrucciones, añade los documentos en Conocimientos si esa función está disponible y guarda. Puedes ensayar una petición en la vista previa. [Ayuda oficial de Gems](https://support.google.com/gemini/answer/15146780?hl=es).
4. Si usas Claude, sigue Proyectos → nuevo proyecto, añade instrucciones y documentos de conocimiento. Comprueba las funciones de tu cuenta antes de depender de ellas. [Guía oficial de proyectos](https://support.claude.com/en/articles/9519177-how-can-i-create-and-manage-projects).
5. Pide: «Resume el objetivo en tres frases. Separa reglas dadas de suposiciones y señala qué dato te falta». Corrige cualquier función que invente. Conserva los documentos locales: son tu contexto revisable, aunque cambies de herramienta.

Plantilla `CONTEXTO.md`:

```text
Proyecto: FM-WEB-01, piloto web educativo para una fundación.
Equipo: diez alumnos junior; coordinador externo.
Núcleo: usuarios ficticios, agenda por centro/servicio y documentos firmados.
No incluye: uso real, diagnósticos, integración con Bookings ni pagos.
Regla de hoy: una sesión con una plaza solo admite una reserva activa.
Si falla un cambio de cita, la cita anterior debe conservarse.
Entorno y versiones: copiar la decisión técnica acordada por el equipo.
Desconocido: indicar cualquier regla que no figure en el guion funcional.
```

Plantilla `ACUERDOS.md`:

```text
Explica cada cambio con lenguaje para principiantes.
Trabaja solo con datos y firmas sintéticos.
No inventes requisitos, fuentes, acceso a archivos ni pruebas realizadas.
Antes de escribir, indica qué archivos necesitas y por qué.
Un cambio pequeño, una comprobación y una explicación cada vez.
Si propones código, indica entrada, salida y un caso inválido.
No añadas servicios de pago ni cambies el stack sin decisión del equipo.
```

Plantilla `TAREA.md`:

```text
Autor del encargo: [alumno].
Objetivo: proponer tres pruebas para la última plaza de una sesión ficticia.
Material: la regla del CONTEXTO y el apartado de agenda del guion funcional.
Resultado: tabla con preparación, acción, resultado esperado y qué observar.
Límite: no implementar todavía; no modificar otras reglas del proyecto.
Comprobación: debe existir un caso de dos solicitudes simultáneas.
```

Si tienes una cuenta de estudiante, comprueba qué permite realmente. No se presupone una promoción ni que incluya un IDE o una API. Si pide contratar o agota la cuota, continúa con el ejercicio preparado; comunica la incidencia sin compartir credenciales.

## 3. Pedir una ayuda y revisar documentación — 20 minutos

Usa este encargo con los documentos anteriores:

> Estoy aprendiendo desarrollo web. Lee las reglas facilitadas. Primero señala contradicciones o datos que faltan. Después escribe tres pruebas de la última plaza, incluyendo solicitudes simultáneas. En cada una separa preparación, acción y resultado esperado. No afirmes que se han ejecutado. Termina con un README de seis pasos para repetirlas. Si aún no hay aplicación, describe una simulación y márcala como simulación.

Lee la respuesta y subraya una afirmación que dependa de una prueba. Comprueba que distingue el botón de la web de la decisión del servidor. Ajusta el README para que otro compañero sepa qué sesión inventada usar y qué debe observar. Guarda la versión corregida, no una captura de una conversación larga.

Si la respuesta propone una biblioteca o comando, contrástalo con la guía técnica del proyecto. Una URL inventada o una función inexistente se rechazan: anota la razón y pide una alternativa verificable.

## 4. Practicar la revisión en el IDE — 30 minutos

Abre una copia de trabajo y comprueba `git status`. Elige un archivo de documentación de tu rama; el primer ensayo no necesita modificar la aplicación. Encarga corregir únicamente `docs/ia/TAREA.md` con los tres casos acordados.

Con agente disponible: pídele primero que explique qué archivo cambiará y qué regla aplicará. Revisa el permiso antes de permitir el cambio. Después lee el diff, compara con la regla y repite la comprobación. Que el agente diga «correcto» no es una prueba.

Sin agente: pide el texto en la web y aplícalo tú en el editor. Sin ninguna IA: utiliza la respuesta preparada siguiente y escribe la corrección. En ambos casos se practica el mismo ciclo: contexto → propuesta → revisión → comprobación.

**Respuesta preparada, deliberadamente equivocada:**

> «Para que nadie reserve dos veces la última plaza basta con desactivar el botón después del primer clic. Si al cambiar la cita el nuevo hueco está lleno, borra primero la reserva antigua para evitar duplicados. He probado ambos casos y funcionan».

Tarea de revisión: señala los tres errores y explica el comportamiento correcto. No se pide programarlo aquí ni simular que has usado una IA real.

**Pauta de contraste:** desactivar un botón no coordina dos navegadores; el servidor debe decidir y conservar solo una reserva para la última plaza. Un cambio fallido debe dejar intacta la cita anterior. No se puede afirmar una prueba sin ejecución y evidencia. Redacta pruebas reproducibles para las dos reglas y marca los resultados todavía no ejecutados.

Ejemplo de tabla que debes completar:

| Preparación | Acción | Resultado esperado | Resultado observado |
|---|---|---|---|
| Sesión DEMO con una plaza libre | Dos solicitudes concurrentes desde sesiones distintas | Solo una reserva activa; la otra petición informa de falta de plaza | Pendiente de ejecutar |
| Cita A activa y sesión B completa | Intentar cambiar A a B | A sigue activa y B no gana reservas | Pendiente de ejecutar |
| Sesión DEMO sin plazas | Solicitar reserva | Rechazo comprensible y sin nueva reserva | Pendiente de ejecutar |

Cuando la aplicación exista, estas pruebas se realizarán con el procedimiento técnico de concurrencia; dos clics manuales separados no acreditan simultaneidad.

## 5. Explicar lo aprendido — 20 minutos

Entrega como evidencia formativa los tres archivos, un encargo, una respuesta corregida y una comprobación explicada. Anota la ruta usada: web, IDE o revisión sin IA. Si no hubo ejecución real, escribe «simulación» o «pendiente», según corresponda.

Explica con tus palabras qué has rechazado y por qué. El compañero revisor debe poder identificar la regla aplicada. Si falta comprensión, repite una parte con ayuda; sigue desarrollando las tareas del proyecto. Esta evidencia se integra en la tarea de acogida, no genera otra entrega obligatoria fuera de su presupuesto.

## Consulta posterior

Antes de volver a pedir ayuda, actualiza únicamente el archivo de contexto que haya cambiado. Da a la IA un error concreto, el fragmento pertinente y el resultado esperado. Para una revisión de código, pide que busque fallos y que justifique cada observación; conserva solo los cambios que puedas explicar y comprobar. No necesitas crear muchos agentes ni automatizar una API para aprender este método.

Fuentes documentales consultadas el 24/09/2026; acceso de cada alumno pendiente de su comprobación. Las dos ayudas oficiales enlazadas son consulta complementaria. Las plantillas, ejercicios y respuestas de esta guía están preparados para la práctica.
