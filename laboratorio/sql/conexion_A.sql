\set ON_ERROR_STOP on
BEGIN ISOLATION LEVEL READ COMMITTED;
\ir comprobar_entorno.sql
SET LOCAL lock_timeout = '60s';
SET LOCAL idle_in_transaction_session_timeout = '5min';
SELECT id FROM fm_laboratorio_plazas.ensayo_sesiones WHERE id = 1 FOR UPDATE;
INSERT INTO fm_laboratorio_plazas.ensayo_reservas(sesion_id, etiqueta)
SELECT 1, 'A'
WHERE (SELECT count(*) FROM fm_laboratorio_plazas.ensayo_reservas WHERE sesion_id = 1)
    < (SELECT capacidad FROM fm_laboratorio_plazas.ensayo_sesiones WHERE id = 1);
SELECT clock_timestamp() AS a_esperando_confirmacion;
\echo 'A conserva la transaccion ABIERTA. Ejecuta B en otra terminal y vuelve aqui para COMMIT; o ROLLBACK;.'
