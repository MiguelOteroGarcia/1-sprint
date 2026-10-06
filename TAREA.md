Autor del encargo: Miguel Otero García.
Objetivo: proponer tres pruebas para la última plaza de una sesión ficticia.
Material: la regla del CONTEXTO y el apartado de agenda del guion funcional.
Resultado: tabla con preparación, acción, resultado esperado y qué observar.
Límite: no implementar todavía; no modificar otras reglas del proyecto.
Comprobación: debe existir un caso de dos solicitudes simultáneas.

## 1. Análisis crítico y refutación técnica de la propuesta

Se analiza la siguiente propuesta técnica generada:
> *"Para que nadie reserve dos veces la última plaza basta con desactivar el botón después del primer clic. Si al cambiar la cita el nuevo hueco está lleno, borra primero la reserva antigua para evitar duplicados. He probado ambos casos y funcionan."*

### Errores detectados y justificación:
1. **Falsa seguridad en cliente (Frontend vs. Backend / Concurrencia):** Desactivar el botón con JavaScript (`disabled = true`) solo protege contra el doble clic de un mismo usuario en su propia pestaña. No coordina navegadores independientes. Si dos personas solicitan la plaza a la vez, ambas peticiones llegan al servidor. El control de concurrencia debe resolverse en el backend y la base de datos (mediante transacciones ACID, bloqueos o control optimista).
2. **Violación de integridad de datos (Pérdida de reserva previa):** Si se borra la cita antigua antes de confirmar la nueva, y la nueva falla porque el hueco ya se ocupó, el usuario pierde su plaza original sin obtener la nueva. La operación de cambio debe ser atómica: si la nueva asignación no se consolida, la cita original debe quedar intacta.
3. **Falsa afirmación de prueba:** No se puede certificar que una prueba "funciona" sin ejecución real en un entorno integrado, sin trazas de red ni comprobación en base de datos.

---

## 2. Casos de prueba para la última plaza

| Preparación | Acción | Resultado esperado | Resultado observado |
| :--- | :--- | :--- | :--- |
| Sesión ficticia DEMO con exactamente 1 plaza libre. Dos clientes web listos. | Dos solicitudes de reserva concurrentes enviadas en el mismo instante desde sesiones distintas. | Solo una reserva queda confirmada y activa en el servidor. La otra petición recibe un rechazo controlado informando de falta de plazas, sin crear duplicados. | **Pendiente de ejecutar** (requiere backend y test de concurrencia). |
| Cita A activa para el usuario y sesión B con aforo completo (0 plazas). | El usuario intenta cambiar su cita actual A hacia la sesión B. | La petición es rechazada por falta de plazas. La cita A sigue activa y válida; la sesión B no incrementa sus reservas. | **Pendiente de ejecutar** (comportamiento atómico no verificado en servidor). |
| Sesión ficticia DEMO sin plazas disponibles (aforo completo). | El usuario solicita una reserva directa para dicha sesión. | Rechazo inmediato y legible sin cambios en base de datos ni registros huérfanos. | **Pendiente de ejecutar** (pendiente de integración con el servicio). |

---

## 3. Procedimiento para repetición (README)

1. Levantar el entorno de pruebas local con los datos semilla de sesiones.
2. Comprobar que la sesión de prueba `DEMO-01` tiene `plazas_disponibles = 1`.
3. Disparar dos peticiones HTTP POST simultáneas hacia el endpoint de reserva usando herramientas de carga o dos clientes automatizados.
4. Inspeccionar la base de datos: verificar que solo existe un registro de reserva y el aforo quedó en 0.
5. Verificar que el segundo cliente recibió un código de error HTTP adecuado (ej. 409 Conflict o 422 Unprocessable Entity) con mensaje descriptivo.
6. Probar el cambio hacia una sesión llena y verificar que la reserva previa no ha sido eliminada ni alterada.