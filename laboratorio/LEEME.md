# Laboratorio inicial y datos sintéticos

## Arranque en cinco minutos

1. Extrae el ZIP en una carpeta propia. Abre `index.html` en un navegador actual.
2. Elige S001 y confirma con Persona Demo y persona@example.test. Verás una reserva local.
3. Repite S001: debe rechazar la petición por falta de plaza. Prueba un correo sin arroba y comprueba que no pierdes el nombre.
4. Pulsa «Comprobar trazo» con el lienzo vacío. Dibuja, comprueba y borra. Cambia el ancho del navegador y repite.
5. Abre `index.html` y `reglas.js` con un editor. Cambia una etiqueta o una regla pequeña; recarga y describe qué cambió.

Todo funciona con ficheros locales. No se requiere cuenta, red, Node, PHP, Composer ni PostgreSQL para esta primera práctica. No escribas nombres, contactos o rúbricas reales. Recargar borra el estado de la maqueta.

## Qué enseña cada archivo

| Archivo | Qué puedes cambiar y comprobar |
| --- | --- |
| index.html | Estructura, etiquetas, orden, errores y resumen. |
| estilos.css | Dos columnas o una, foco, tamaño táctil y legibilidad. |
| reglas.js | Validación, respuesta de capacidad simulada, formatos y reglas documentales. Funciones pequeñas sin navegador. |
| interfaz.js | Eventos, respuesta visible y lienzo de rúbrica. No controla permisos reales. |
| pruebas_reglas.js | Casos ejecutables de las funciones con Node, si ya está disponible. |
| datos y contratos | Ficheros de apoyo que se incluyen al empaquetar: identidades, sesiones, respuestas y diccionarios. |

Si tienes Node instalado, abre una terminal en esta carpeta y ejecuta `node pruebas_reglas.js`. El resultado esperado son once líneas OK y el resumen de once pruebas. No instales Node solo para empezar: puedes recorrer cada entrada/salida con el navegador y explicar la regla.

## Del ejemplo al producto

Esta maqueta no guarda datos entre equipos ni demuestra protección, idempotencia de servidor o carreras de reservas. En S2 preparas Laravel/PostgreSQL con la guía. En S3 usas prerregistros sembrados; en S4 la web los crea con una reserva real. Los contratos de respuesta permiten preparar interfaz y pruebas mientras otra pareja trabaja en el servidor.

Para la última plaza real usa dos conexiones distintas a PostgreSQL y la receta de transacción, no dos pulsaciones sobre este ejemplo. Para una firma real del piloto, el servidor valida PNG, estado, identidad y centro y genera un PDF privado; este lienzo solo te enseña los eventos.

## Primera evidencia

Anota el archivo cambiado, entrada válida/errónea, resultado esperado/obtenido y una explicación propia. Incluye una captura de cada caso en el PDF de la tarea, sin atribuir a este ejemplo pruebas de servidor que todavía no existen.

