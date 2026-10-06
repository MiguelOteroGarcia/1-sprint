\set ON_ERROR_STOP on
DO $fm_guard$
BEGIN
    IF current_database() <> 'fm_test' THEN
        RAISE EXCEPTION 'Solo se permite fm_test; actual: %', current_database();
    END IF;
    IF NOT EXISTS (
        SELECT 1 FROM pg_namespace
        WHERE nspname = 'fm_laboratorio_plazas'
          AND obj_description(oid, 'pg_namespace') = 'FM-LAB-PLAZAS-v1'
    ) THEN
        RAISE EXCEPTION 'Falta el esquema identificado del laboratorio. Revisa preparar.sql.';
    END IF;
END;
$fm_guard$;
SELECT current_database() AS base, pg_backend_pid() AS conexion, clock_timestamp() AS instante;
