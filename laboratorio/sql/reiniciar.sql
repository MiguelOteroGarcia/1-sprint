\set ON_ERROR_STOP on
BEGIN;
\ir comprobar_entorno.sql
SET LOCAL lock_timeout = '5s';
LOCK TABLE fm_laboratorio_plazas.ensayo_sesiones,
           fm_laboratorio_plazas.ensayo_reservas IN ACCESS EXCLUSIVE MODE;
DELETE FROM fm_laboratorio_plazas.ensayo_reservas;
COMMIT;
SELECT count(*) AS reservas_esperadas_cero FROM fm_laboratorio_plazas.ensayo_reservas;
