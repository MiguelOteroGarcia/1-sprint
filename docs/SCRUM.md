# Registro de Ciclo Scrum y Gestión de Tareas

## 1. Tarjeta de Trabajo (Sprint Backlog)

* **ID del Roadmap:** FM-LAB-01
* **Título / Resultado pequeño:** Validación de campo de contacto y mejora de accesibilidad en formulario de alta.
* **Autor:** Miguel Otero García
* **Revisor asignado:** Compañero de pareja técnica (rol de revisión)
* **Estado:** En curso
* **Presupuesto estimado:** 100 minutos (imputados a la práctica técnica de laboratorio).
* **Entradas / Dependencias:** Archivo `laboratorio/index.html` y script `interfaz.js`. Reglas de validación acordadas en el guion funcional.
* **Criterios de "Terminado" (Definition of Done - DoD):**
  1. Etiqueta (`<label>`) actualizada con texto descriptivo, claro y accesible.
  2. Comprobación de caso válido: un contacto sintético correcto es procesado y permite avanzar.
  3. Comprobación de caso inválido/negativo: un contacto vacío o con formato erróneo muestra un mensaje de advertencia visual y bloquea el envío sin recargar la página.
  4. Revisión de código realizada por el compañero antes de fusionar.
  5. Evidencia contrastable registrada (capturas de pantalla legibles y commit localizable en Git).
* **Enlace a evidencia:** Commit en rama local / Pull Request pendiente de revisión.

---

## 2. Simulación de Notificación de Bloqueo (Pauta de 20 minutos)

> Contexto simulado: Tras 20 minutos investigando por qué la validación de formato bloquea correos con extensiones largas, se registra la incidencia y se solicita apoyo sin detener el flujo general de trabajo.

* **Tarea y alumno:** FM-LAB-01 - Miguel Otero García
* **Resultado que intento conseguir:** Validar que el campo acepte tanto teléfonos de 9 dígitos como correos electrónicos con formato estándar.
* **Versión / commit / rama:** Rama `feature/validacion-contacto`, commit `a1b2c3d`.
* **Pasos mínimos para reproducir:**
  1. Abrir `laboratorio/index.html` en el navegador.
  2. Introducir `usuario@fundacion.educacion` en el campo de contacto.
  3. Pulsar el botón de enviar o guardar.
* **Esperado:** Que el sistema reconozca el valor como válido y permita continuar.
* **Observado y error exacto:** La expresión regular rechaza el dominio `.educacion` y dispara el mensaje de error "Contacto no válido".
* **Qué he comprobado ya:** Se ha comprobado la sintaxis de la expresión regular en la consola del navegador y se ha probado con un dominio `.org` simple (este último sí valida correctamente).
* **Archivo o ejemplo relevante:** `laboratorio/interfaz.js` (función de escucha del evento submit).
* **Ayuda concreta que necesito:** Revisar la expresión regular o consensuar con el equipo si admitimos una validación más permisiva en cliente para no bloquear entradas válidas.
* **Actividad útil mientras se resuelve:** Maquetar el contenedor del mensaje de error con estilos CSS accesibles y redactar los casos de prueba del caso negativo en la documentación.
* **Persona avisada y momento:** Revisor de pareja / Integrador mediante mensaje escrito interno.

---

## 3. Diferencia entre Actividad Empezada y Comprobada

* **Actividad empezada (In Progress):** El desarrollador ha creado la rama de trabajo, ha escrito líneas de código o ha maquetado elementos en pantalla, pero no existe garantía de que cumpla las reglas de negocio ni se han testeado casos límite, accesibilidad o errores de entrada. No aporta todavía un incremento de valor observable ni seguro.
* **Actividad comprobada (Done):** El cambio se ha ejecutado efectivamente en el entorno correspondiente con datos válidos y con entradas erróneas o de acceso restringido. Se ha contrastado que el resultado coincide con lo acordado, el código ha superado una revisión técnica por pares y existe una evidencia verificable (commit, traza de ejecución o captura).