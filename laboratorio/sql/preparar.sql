\set ON_ERROR_STOP on
BEGIN;
DO $fm_guard$
BEGIN
    IF current_database() <> 'fm_test' THEN
        RAISE EXCEPTION 'Solo se permite fm_test; actual: %', current_database();
    END IF;
END;
$fm_guard$;
-- Sin IF NOT EXISTS: si el nombre está ocupado se detiene, no reutiliza datos.
CREATE SCHEMA fm_laboratorio_plazas;
COMMENT ON SCHEMA fm_laboratorio_plazas IS 'FM-LAB-PLAZAS-v1';
CREATE TABLE fm_laboratorio_plazas.ensayo_sesiones (
    id integer PRIMARY KEY,
    capacidad integer NOT NULL CHECK (capacidad > 0)
);
CREATE TABLE fm_laboratorio_plazas.ensayo_reservas (
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    sesion_id integer NOT NULL REFERENCES fm_laboratorio_plazas.ensayo_sesiones(id),
    etiqueta text NOT NULL CHECK (etiqueta IN ('A', 'B'))
);
INSERT INTO fm_laboratorio_plazas.ensayo_sesiones(id, capacidad) VALUES (1, 1);
COMMIT;
SELECT current_database() AS base, pg_backend_pid() AS conexion;
SELECT * FROM fm_laboratorio_plazas.ensayo_sesiones;
