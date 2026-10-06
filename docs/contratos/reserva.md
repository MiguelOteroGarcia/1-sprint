# Contrato de Servicio: Reserva Pública

- **Proyecto:** FM-WEB-01 · Módulo de Agenda y Reservas
- **Ruta de Endpoint:** `POST /reservas`
- **Canal:** Público (formulario web)
- **Autores:** Miguel Otero García (redacción) y Pareja Backend (contraste de diccionario)

---

## 1. Definición y Campos Pactados

El formulario público de reserva recoge los datos mínimos de la sesión elegida y el prerregistro de la persona atendida, identificando de manera unívoca el intento mediante una clave de operación emitida previamente por el servidor.

### 1.1 Estructura del Payload de Entrada (`POST /reservas`)

| Campo | Tipo técnico | Obligatorio | Regla de validación / Diccionario |
| :--- | :--- | :--- | :--- |
| `sesion_id` | `bigint` (entero) | Sí | Debe coincidir con un ID de sesión existente, abierta y futura. |
| `operacion` | `varchar(64)` | Sí | Token único emitido por el servidor para el navegador para controlar la idempotencia. |
| `prerregistro.nombre` | `varchar(100)` | Sí | Texto ficticio no vacío; sin espacios sobrantes. |
| `prerregistro.apellidos` | `varchar(150)` | Sí | Apellidos de demostración. |
| `prerregistro.fecha_nacimiento` | `date` (AAAA-MM-DD) | Sí | Fecha pasada respecto al reloj de prueba. |
| `prerregistro.tipo_atencion` | `varchar(24)` | Sí | Valor restringido: `adulto` o `menor_representado`. |
| `prerregistro.correo` | `varchar(180)` | Condicional | Formato con `@` y dominio válido; obligatorio si no se aporta teléfono. |
| `prerregistro.telefono` | `varchar(40)` | Condicional | Formato numérico/texto de contacto; obligatorio si no se aporta correo. |
| `prerregistro.representante` | `objeto / null` | Sí | `null` si `tipo_atencion` es `adulto`. Objeto con datos de contacto si es menor. |

> **Nota de seguridad del contrato:** El cliente jamás envía `persona_id`, `canal`, `estado`, `payload_hash` ni el identificador de centro. El servidor determina centro y servicio a través de `sesion_id`.

---

## 2. Distinción: Solicitud de Reserva Confirmada vs. Identidad Pendiente

Es una regla crítica del guion funcional separar la reserva de la plaza de la confirmación de la persona física:

1. **Reserva Confirmada (`estado: "reservada"`):**
   * Corresponde a la reserva de la plaza física en la sesión de agenda.
   * El servidor bloquea la sesión en PostgreSQL (`lockForUpdate`), descuenta una unidad del aforo disponible y emite un código público (ej. `RV-DEMO-001`).
   * La plaza queda ocupada para la sesión.

2. **Identidad Pendiente (`identidad: "pendiente"` / `persona_id: null`):**
   * La persona atendida **no** queda dada de alta como usuario confirmado en la tabla `personas`.
   * La información de contacto y filiación se almacena en el campo estructurado `prerregistro` (JSONB).
   * **Objetivo funcional:** Recepción contrastará presencialmente los datos en el centro físico para evitar duplicidades accidentales o fusiones indebidas si dos familiares comparten teléfono o correo.
   * Mientras la identidad siga en estado pendiente, **queda estrictamente prohibido** firmar documentos o asociar resultados clínicos.

---

## 3. Matriz de Estados de Respuesta HTTP y Comportamiento del Formulario

| Estado HTTP | Código Negocio | Descripción / Significado | Efecto en la Interfaz (Frontend) |
| :--- | :--- | :--- | :--- |
| **201 Created** | `RESERVADA` | Reserva registrada con éxito. Se muestra el comprobante y la clave de gestión privada (solo una vez). | El formulario se bloquea y muestra el resumen de cita y clave. |
| **409 Conflict** | `SIN_PLAZAS` | La sesión solicitada ha alcanzado su capacidad máxima en el servidor. | Notificación visual de aforo completo; conserva los datos escritos para elegir otra sesión. |
| **422 Unprocessable** | `DATOS_INVALIDOS` | Fallo de formato en campos (ej. correo sin formato o fecha futura). | Muestra error contextual bajo el campo erróneo; conserva el resto de entradas. |
| **200 OK (500h)** | `REPETIDA` | Reenvío del mismo formulario con la misma `operacion` y mismo `payload_hash`. | Devuelve la reserva previa sin consumir otra plaza ni revelar la clave privada original. |

---

## 4. Límites de la Simulación Local (Responsabilidades Pendientes del Servidor)

La maqueta local de desarrollo (`laboratorio/index.html`) simula este contrato mediante memoria volátil de JavaScript. De acuerdo con las especificaciones de arquitectura, el backend debe resolver obligatoriamente:

* **Persistencia Atómica:** La maqueta se borra al recargar la pestaña. En el backend, las inserciones se harán en las tablas `reservas` y `sesiones` de PostgreSQL mediante transacciones gestionadas por el framework.
* **Control de Concurrencia (Última Plaza):** El navegador no puede coordinar dos usuarios concurrentes. El backend utilizará `DB::table('sesiones')->lockForUpdate()` para serializar el recuento de plazas y evitar la sobreventa.
* **Seguridad Criptográfica de la Clave Privada:** La clave aleatoria de gestión (mínimo 32 bytes) solo se mostrará una vez en el JSON de creación. El servidor únicamente almacenará su hash (`token_gestion_hash`), imposibilitando su recuperación si el usuario no la anota.