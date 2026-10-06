\set ON_ERROR_STOP on
BEGIN;
\ir comprobar_entorno.sql
SET LOCAL lock_timeout = '5s';
-- Sin CASCADE: una dependencia inesperada cancela la retirada completa.
DROP TABLE fm_laboratorio_plazas.ensayo_reservas;
DROP TABLE fm_laboratorio_plazas.ensayo_sesiones;
DROP SCHEMA fm_laboratorio_plazas;
COMMIT;
