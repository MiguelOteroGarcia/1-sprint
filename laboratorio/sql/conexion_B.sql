\set ON_ERROR_STOP on
BEGIN ISOLATION LEVEL READ COMMITTED;
\ir comprobar_entorno.sql
SET LOCAL lock_timeout = '60s';
\echo 'B espera aqui si A aun mantiene su bloqueo. Confirma o revierte A en menos de 60 segundos.'
SELECT id FROM fm_laboratorio_plazas.ensayo_sesiones WHERE id = 1 FOR UPDATE;
INSERT INTO fm_laboratorio_plazas.ensayo_reservas(sesion_id, etiqueta)
SELECT 1, 'B'
WHERE (SELECT count(*) FROM fm_laboratorio_plazas.ensayo_reservas WHERE sesion_id = 1)
    < (SELECT capacidad FROM fm_laboratorio_plazas.ensayo_sesiones WHERE id = 1);
COMMIT;
SELECT clock_timestamp() AS b_fin, count(*) AS reservas, max(etiqueta) AS ganador
FROM fm_laboratorio_plazas.ensayo_reservas WHERE sesion_id = 1;
