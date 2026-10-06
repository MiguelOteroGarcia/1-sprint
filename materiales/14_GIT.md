# Git y revisión de cambios

FM-WEB-01 · Recurso del alumno. Practica primero con un archivo de documentación. El tiempo de aprendizaje y revisión pertenece a las tareas del sprint, no a una actividad extra. La guía supone Git instalado y una copia del proyecto; si no arranca, utiliza el protocolo de ayuda y practica la revisión de un diff preparado.

## 1. Acuerdo del equipo

Una rama contiene un cambio pequeño. Un commit guarda una versión identificable. Una pull request (PR) propone integrar una rama y reúne revisión y pruebas. `main` conserva la versión compartida; no se usa como borrador personal. La persona autora pide revisión a otra; la revisión puede ser asíncrona.

No hace falta una licencia de pago de GitHub para el flujo básico de repositorio y PR. Las funciones avanzadas de protección pueden depender del plan: si no están disponibles, el grupo aplica el acuerdo de revisión y limita quién integra. [Planes oficiales de GitHub](https://docs.github.com/es/get-started/learning-about-github/githubs-plans). Otro alojamiento Git acordado puede servir; no cambies de plataforma por tu cuenta.

No guardes `.env`, credenciales, bases con datos reales ni firmas personales. Sí se versionan configuración de ejemplo sin secretos, migraciones, datos sintéticos, pruebas y dependencias fijadas según la guía técnica.

## 2. Tu primer cambio

1. Acepta la invitación al repositorio acordado y comprueba que puedes leerlo. Clónalo con la URL proporcionada; no copies una URL de ejemplo como si fuera la real. Abre su carpeta en el editor.
2. Lee el README y ejecuta el arranque establecido. Guarda el resultado y la versión. Para aprender Git basta editar documentación; no improvises instalaciones alternativas.
3. En la terminal dentro del repositorio ejecuta `git status`. Antes de cambiar de rama debe indicar que no hay cambios pendientes. Si aparecen archivos tuyos sin guardar, revisa con tu pareja cómo conservarlos; no los borres para continuar.
4. Actualiza y crea una rama. En el ejemplo se usa `main`; sustituye ese nombre si el equipo ha acordado otro.

```text
git switch main
git pull --ff-only
git switch -c alumno01/guia-arranque
```

5. Añade a `docs/aprendizaje.md` una instrucción de arranque que hayas comprobado. Si el archivo no existe, créalo. Revisa el cambio antes de guardarlo en Git.

```text
git diff -- docs/aprendizaje.md
git status
git add docs/aprendizaje.md
git diff --cached
git commit -m "Documenta una comprobacion del arranque"
git push -u origin alumno01/guia-arranque
```

`git diff` no muestra contenido de archivos nuevos sin seguimiento; ábrelos y revisa `git diff --cached` después de añadirlos. Cambia solo los archivos que pertenecen a tu tarea. Si `pull --ff-only` falla, conserva el mensaje y pide revisión del estado; no fuerces ni descartes cambios.

6. Abre una PR hacia `main`. Comprueba los archivos y copia la plantilla siguiente. Pide revisión al compañero asignado, que responderá en su horario. [Ejercicio oficial Hola mundo](https://docs.github.com/es/get-started/using-github/hello-world).

```text
Tarea del roadmap:
Problema y resultado esperado:
Cambio realizado y archivos:
Cómo reproducir la prueba:
Resultado observado y versión/commit:
Caso inválido comprobado, o por qué no aplica al cambio documental:
Límites o comprobaciones pendientes:
Autor y revisor:
```

## 3. Cómo revisar una PR junior

Lee primero el objetivo y su regla. Revisa el diff: ¿cada cambio ayuda al resultado?, ¿faltan mensajes o validaciones?, ¿se ha añadido algo ajeno? Ejecuta el caso con las instrucciones recibidas y contrasta esperado/observado. Para un cambio funcional comprueba también un dato inválido, un acceso no permitido o un conflicto relevante. No uses «parece bien» como evidencia.

Comenta con precisión: archivo, comportamiento, ejemplo y propuesta pequeña. Si no sabes resolverlo, describe qué no entiendes. El autor responde o corrige en la misma rama; el nuevo commit actualiza la PR. Quien integra comprueba que las correcciones han sido revisadas y las pruebas siguen pasando. No apruebes tu propio trabajo como si fuera revisión independiente.

Tras integrar, actualiza tu copia con `git switch main` y `git pull --ff-only`, siempre con el estado limpio. Conserva el enlace de PR y commit en la evidencia. No necesitas borrar ramas para demostrar el ejercicio.

## 4. Resolver un conflicto con ayuda

Git puede combinar cambios en zonas distintas. Si dos ramas modifican la misma zona, puede pedir que alguien decida el contenido final. [Manual Pro Git en español: ramificar y fusionar](https://git-scm.com/book/es/v2/Ramificaciones-en-Git-Procedimientos-B%C3%A1sicos-para-Ramificar-y-Fusionar).

Practica en tu rama de ejercicio, con los cambios ya guardados y `git status` limpio. Actualiza la referencia del servidor e incorpora la base:

```text
git fetch origin
git merge origin/main
```

Si hay conflicto, Git indica los archivos. Abre uno y busca:

```text
<<<<<<< HEAD
texto de tu rama
=======
texto de la rama que incorporas
>>>>>>> origin/main
```

Habla por escrito con el otro autor sobre la regla que debe conservarse. Escribe el contenido final correcto y elimina los marcadores; no elijas «todo lo mío» sin leer. Repite la prueba y revisa `git diff`. Después añade solo los archivos resueltos, revisa lo preparado y crea el commit de resolución cuando `git status` indique que corresponde. Envía la rama con `git push` y solicita nueva revisión.

Si no comprendes el conflicto, detente en la integración y registra la ayuda; puedes continuar con un caso de prueba o documentación independiente. Para cancelar un intento de fusión recién iniciado desde estado limpio, pide a tu pareja revisar `git status` y utilizar `git merge --abort`. No uses `reset --hard`, `clean -fd` ni push forzado como solución automática.

Un conflicto en un archivo binario o en dependencias necesita criterio técnico: no se resuelve pegando ambos contenidos. Escálalo con los dos commits y la instrucción que lo produjo. [Referencia oficial de conflictos](https://docs.github.com/en/pull-requests/reference/merge-conflicts).

## 5. Pruebas antes de integrar

Ejecuta el comando de pruebas del README técnico del proyecto. No copies un comando de otro stack. Registra versión, preparación y resultado. Si una prueba falla, identifica si el fallo ya estaba en la base o lo introduce tu cambio con ayuda del revisor. No borres la prueba para conseguir una salida verde.

Para una pantalla: caso válido, campo inválido y navegación por teclado. Para un permiso: cuenta permitida y denegada, incluyendo petición directa al servidor. Para agenda: plaza, cancelación, cambio fallido y, donde corresponda, el ensayo de concurrencia de la guía técnica. Para documentos: identidad, rúbrica vacía y recuperación. Selecciona los casos que afectan a tu cambio; no hace falta repetir todo el proyecto por una errata.

La PR debe incluir el resultado observado. «Lo comprobó la IA» sin ejecución accesible no acredita la prueba. Si aún no se ejecutó, indícalo y mantén la tarjeta en revisión.

## 6. Ayuda cuando falla Git

Incluye rama actual, `git status`, comando exacto, error y archivos implicados, sin secretos. No pegues tokens ni enlaces privados con credenciales. Si no tienes acceso al alojamiento, conserva commits locales y entrega el diff por el canal de aula autorizado para revisión; se integrará cuando se recupere el acceso. No compartas una contraseña para desbloquear a un compañero.

Consulta complementaria: [recursos oficiales de aprendizaje de GitHub](https://docs.github.com/en/get-started/start-your-journey/git-and-github-learning-resources). Incluye una serie de vídeos para principiantes; el texto de esta guía permite completar la práctica sin verlos. Fuentes consultadas el 24/09/2026. Los comandos son un procedimiento propuesto para el repositorio del alumnado, no un registro de ejecución realizada.
