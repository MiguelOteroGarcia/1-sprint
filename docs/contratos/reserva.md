# Contrato de Servicio: Reserva Pública

- **Proyecto:** FM-WEB-01 · Módulo de Agenda y Reservas (Itinerario 500 h)
- **Ruta de Endpoint:** `POST /reservas`
- **Canal:** Público (`online`)
- **Autores:** Pareja Frontend (redacción de contrato) y Pareja Backend (contraste de diccionario)

---

## 1. Definición y Campos Pactados (Paso 1)

El formulario público recoge los datos de la sesión elegida y el prerregistro de la persona atendida. El intento se identifica de forma unívoca mediante una clave de `operacion` emitida previamente por el servidor.

### 1.1 Estructura del Payload de Entrada (`POST /reservas`)

| Campo | Tipo técnico | Obligatorio | Regla de validación / Diccionario |
| :--- | :--- | :--- | :--- |
| `sesion_id` | `bigint` (entero) | Sí | Sesión existente, abierta y con hora de inicio futura respecto al reloj del servidor. |
| `operacion` | `varchar(64)` | Sí | Token único emitido por el servidor al formulario para control de idempotencia y doble clic. |
| `prerregistro.nombre` | `varchar(100)` | Sí | Texto ficticio no vacío; espacios exteriores recortados. |
| `prerregistro.apellidos` | `varchar(150)` | Sí | Apellidos ficticios completos. |
| `prerregistro.fecha_nacimiento` | `date` (AAAA-MM-DD) | Sí | Fecha pasada respecto al reloj de prueba. No determina por sí sola quién firma. |
| `prerregistro.tipo_atencion` | `varchar(24)` | Sí | Restringido a `adulto` o `menor_representado`. |
| `prerregistro.correo` | `varchar(180)` | Condicional | Formato de email sintético válido (`@example.test`). Obligatorio si no hay teléfono. |
| `prerregistro.telefono` | `varchar(40)` | Condicional | Cadena de texto de contacto. No lleva restricción UNIQUE (se admite contacto compartido). |
| `prerregistro.representante` | `objeto / null` | Sí | `null` si `tipo_atencion: "adulto"`. Obligatorio si es `menor_representado`. |

> **Nota de seguridad del contrato:** El cliente jamás envía `persona_id`, `canal`, `estado`, `payload_hash` ni identificadores de centro. El servidor deduce centro y servicio directamente de la sesión bajo transacción.

---

## 2. Distinción Crítica: Solicitud de Reserva vs. Identidad Pendiente

1. **Solicitud de Reserva Registrada (`estado: "reservada"`):**
   * Bloquea la sesión en PostgreSQL (`lockForUpdate`), comprueba aforo y descuenta la plaza.
   * Genera un código de comprobante visible (ej. `RV-DEMO-001`).
   * La plaza queda consumida en la sesión.

2. **Identidad Pendiente (`identidad: "pendiente"` / `persona_id: null`):**
   * La persona atendida **no** se inserta como usuario confirmado en la tabla `personas`.
   * Los datos quedan archivados en el campo `prerregistro` (JSONB) de la tabla `reservas`.
   * **Motivo funcional:** Recepción contrastará presencialmente la identidad en el centro para evitar duplicados o fusiones automáticas erróneas (ej. familiares que comparten teléfono).
   * **Restricción de negocio:** Una reserva con identidad pendiente **no puede firmar documentos** ni registrar resultados clínicos.

---

## 3. Matriz de Respuestas HTTP y Comportamiento del Formulario

| Estado HTTP | Código Negocio | Significado | Comportamiento del Formulario (Frontend) |
| :--- | :--- | :--- | :--- |
| **201 Created** | `RESERVADA` | Reserva registrada con éxito. Devuelve comprobante y clave privada. | Bloquea el formulario, muestra resumen y presenta la `clave_gestion` (visible solo una vez). |
| **200 OK (500h)** | `REPETIDA` | Reenvío idéntico (misma `operacion` y mismo `payload_hash`). | Muestra comprobante existente con `clave_gestion: null` y aviso de acudir a recepción si se extravió. |
| **409 Conflict** | `SIN_PLAZAS` | Capacidad máxima alcanzada en la sesión. | Notificación de sin plazas; conserva todos los campos escritos para seleccionar otra sesión. |
| **409 Conflict (500h)**| `OPERACION_REUTILIZADA`| Se reenvía la misma `operacion` pero con datos alterados. | Error de conflicto de formulario; solicita recargar para obtener un nuevo token. |
| **422 Unprocessable** | `DATOS_INVALIDOS` | Fallo de formato en campos (ej. email inválido o fecha futura). | Resalta los campos con error junto al input; conserva las entradas válidas previas. |

---

## 4. Límites de la Simulación Local (Paso 4)

La maqueta local de desarrollo (`laboratorio/index.html`) simula este contrato en memoria de navegador. A continuación se documenta la tabla de contraste exigida:

### Tabla de Contraste: Maqueta Local vs. Servidor Real

| Entrada de prueba | Respuesta simulada | Efecto esperado en interfaz | Límite real (Pendiente del Servidor) |
| :--- | :--- | :--- | :--- |
| **Caso Válido:** Sesión disponible, prerregistro completo y token de operación. | `201 Created` (`confirmada.json`) | Muestra comprobante `RV-DEMO-001`, reserva guardada y clave de gestión. | **Persistencia y Hash:** La maqueta pierde los datos al recargar. El backend debe persistir en PostgreSQL y almacenar únicamente el SHA-256 de la clave privada (`token_gestion_hash`). |
| **Caso Inválido:** Prerregistro sin teléfono ni correo o fecha de nacimiento futura. | `422 Unprocessable` (`error_formato.json`) | Inputs en rojo con mensajes contextuales; los campos válidos no se borran. | **Validación canónica:** La validación HTML5/JS del navegador es orientativa. El servidor Laravel debe validar de forma autoritativa con Form Requests. |
| **Caso Concurrente (Última plaza):** Dos envíos simultáneos sobre sesión con 1 plaza libre. | `201` en uno y `409` en otro (`sin_plaza.json`) | Un usuario confirma; el segundo recibe notificación de sesión llena sin perder sus datos. | **Concurrencia:** La maqueta monousuario no puede simular colisiones. Requiere `SELECT ... FOR UPDATE` en PostgreSQL para serializar transacciones y evitar sobreventa. |
| **Caso Idempotencia (500h):** Doble clic con misma `operacion` y mismo payload. | `200 OK` (`repetido.json`) | Muestra la cita ya creada sin duplicar reserva ni descontar una segunda plaza. | **Unicidad en BD:** La maqueta no controla duplicados en red. El servidor aplica restricción `UNIQUE` en la columna `operacion` y cotejo de `payload_hash`. |
| **Caso Autorización:** Intento de confirmar persona o firmar documento desde el formulario público. | `403 Forbidden` / No disponible | El formulario público no expone ni permite selectores de `persona_id` ni firma. | **Control de Acceso (RBAC):** Ocultar controles en HTML/CSS no protege el sistema. Solo endpoints autenticados de recepción con sesión de personal activa pueden confirmar identidades o gestionar firmas. |