# Ayuda y registro de evidencias

FM-WEB-01 · Recurso del alumno. Usa esta guía cuando te atasques y al preparar la evidencia de una tarea. El producto es de equipo; cada alumno debe poder explicar su contribución y repetir una comprobación.

## 1. Un bloqueo debe producir una siguiente acción

1. Lee el resultado esperado y reproduce el problema una vez con datos inventados. Anota qué hiciste, qué esperabas y qué ocurrió.
2. Dedica hasta 20 minutos a una comprobación concreta: guía de arranque, mensaje del error, nombre de campo, permiso o ejemplo resuelto. Evita repetir el mismo intento sin cambiar una hipótesis.
3. Si no avanzas, registra el bloqueo en la tarjeta y avisa por escrito a tu pareja y al responsable de integración de ese sprint. Incluye la plantilla siguiente. No necesitas esperar a que coincidan contigo.
4. Continúa con la alternativa prevista en tu tarea: datos de prueba, pantalla con ejemplo local, prueba de una regla ya acordada, revisión de otro cambio o actualización del README. Conserva visible la dependencia y no declares integrada la simulación.
5. Al iniciar su siguiente sesión, el compañero revisa y deja una propuesta comprobable. Si tampoco sabe, se escala al coordinador con lo ya intentado. El equipo reasigna la revisión si esa persona no está disponible.
6. Al recuperar la pieza, conecta el trabajo con el sistema real y repite la prueba de integración. Cierra el bloqueo solo cuando exista resultado observado.

Los 20 minutos son una pauta para pedir ayuda pronto, no un tiempo que haya que agotar. Un error de permiso o una duda que puede afectar a otro compañero se comunican enseguida. La ayuda y el cambio de actividad cuentan en las horas de la tarea; no amplían automáticamente el plan.

```text
Tarea y alumno:
Resultado que intento conseguir:
Versión / commit / rama:
Pasos mínimos para reproducir:
Esperado:
Observado y error exacto:
Qué he comprobado ya:
Archivo o ejemplo relevante:
Ayuda concreta que necesito:
Actividad útil mientras se resuelve:
Persona avisada y momento:
```

## 2. Alternativas para los bloqueos habituales

| Bloqueo | Qué puedes hacer ahora | Qué sigue pendiente |
|---|---|---|
| No funciona el entorno | Revisar el ejemplo y preparar un caso válido/inválido; registrar error y versiones | Ejecutar y comprobar en el entorno real |
| Backend todavía no responde | Construir la pantalla con datos del contrato y estados de error | Conectar, validar y probar contra el servidor |
| Pantalla todavía no está | Ejecutar el servicio o prueba automatizada con los datos pactados | Recorrido visual y accesibilidad |
| Falta una decisión del cliente | Usar el supuesto didáctico explícito del guion en una pieza reversible | Confirmación y ajuste antes de presentar uso real |
| No hay IA, cuota o licencia | Revisar la respuesta preparada de la guía IA | Uso real de la herramienta, si procede |
| Falta revisor conectado | Abrir PR con pasos reproducibles y revisar otra tarea | Revisión independiente antes de integrar |
| No hay tableta | Ensayar ratón y emulación táctil y anotar dispositivo | Prueba en tableta real por coordinador o cliente |

No inventes decisiones sanitarias o jurídicas para cerrar una duda. Trabajáis con datos y firmas sintéticos y reglas didácticas explícitas. El coordinador canaliza consultas al cliente.

## 3. Plantilla de evidencia: PDF de hasta tres páginas y 10 MB

Usa el formato indicado en la ficha. Cuando pida PDF, conserva texto seleccionable y capturas legibles; no fotografíes todo el código. El repositorio guarda código y pruebas; el PDF los localiza y explica. En una entrega compartida se identifica a cada participante; no se crean diez PDFs idénticos si la ficha admite uno de equipo.

**Página 1 · Qué hemos cambiado.**

```text
Proyecto: FM-WEB-01. Tarea: [ID y título]. Itinerario: 370 / 500 h.
Autores y aportación exacta de cada uno:
Persona revisora y parte revisada:
Regla de aceptación aplicada:
Cambio entregado: archivo / PR / commit / enlace accesible al revisor.
Versión y entorno de la prueba:
Una imagen o fragmento que ayude a entender el resultado:
```

**Página 2 · Cómo lo hemos comprobado.**

| Caso | Preparación y acción | Esperado | Observado | Evidencia |
|---|---|---|---|---|
| Válido | Datos y pasos reproducibles | Comportamiento acordado | Resultado real, fecha y versión | Prueba, captura o registro |
| Inválido o no permitido | Un dato erróneo, permiso denegado o conflicto pertinente | Rechazo sin efecto no deseado | Resultado real o pendiente | Prueba, captura o registro |

Ejemplo de prueba negativa: una cuenta sin permiso solicita el documento de otra persona por su URL. Se espera rechazo del servidor y ausencia del contenido del documento. Ocultar el botón no basta para esa prueba. No pegues datos reales ni credenciales en la captura.

**Página 3 · Qué sabemos y qué falta.**

```text
Explicación individual breve: qué decidí y por qué funciona.
Revisión recibida y corrección realizada:
Ayuda o IA utilizada y cómo contrasté la respuesta:
Limitación y siguiente comprobación, si existe:
Horas reales por persona, sin sumar dos veces la colaboración:
Estado de cada comprobación: realizada / fallida / pendiente / simulada.
```

No rellenes «realizada» antes de ejecutar. Un caso fallido útil y bien explicado muestra aprendizaje, pero no acredita terminado si incumple un criterio obligatorio. Corrige y vuelve a ejecutar la parte afectada; conserva el resultado final y la referencia al fallo.

## 4. Ejemplo de contribuciones que sí se pueden revisar

«Alumno 01: validó la fecha en el servidor y añadió el caso de fecha imposible; alumno 02: conservó los valores del formulario tras el error y comprobó teclado; alumno 03: preparó datos sintéticos y repitió ambos casos en otra copia. Revisor 04: detectó que el mensaje no señalaba el campo; se corrigió en el commit indicado».

Es un ejemplo de redacción, no evidencia de pruebas realizadas. «Todos hicimos todo» o diez nombres junto a una captura no permiten comprobar el aprendizaje individual.

## 5. Antes de enviar

Abre el PDF exportado y comprueba número de páginas, peso, legibilidad y ausencia de secretos. Revisa que el enlace al cambio y a los datos de prueba funcione para la persona revisora. Si el vídeo o repositorio requiere acceso, aporta también pasos y resultado dentro del PDF; una URL inaccesible no basta. El vídeo es opcional salvo que la ficha diga otra cosa.

Lee la ficha por última vez: entrega, criterios y dependencia. Registra la evidencia en la tarjeta del tablero y solicita revisión. Si falta un recurso del aula, comunica su nombre y sigue con la copia autorizada o el ejemplo preparado; no marques una prueba como hecha solo para cerrar la tarea.
