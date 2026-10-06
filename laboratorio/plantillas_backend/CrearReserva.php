<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;


final class CrearReserva
{
    public function ejecutar(
        int $sesionId,
        ?int $personaId,
        array $prerregistro,
        string $payloadHash,
        string $operacion,
        string $tokenGestionHash
    ): array {
        return DB::transaction(function () use (
            $sesionId, $personaId, $prerregistro, $payloadHash, $operacion, $tokenGestionHash
        ) {
            // Todas las operaciones de ocupacion bloquean esta misma fila.
            $sesion = DB::table('sesiones')->where('id', $sesionId)
                ->lockForUpdate()->first();
            if (!$sesion) {
                throw new ConflictoReserva('SESION_NO_DISPONIBLE', 'La sesion ya no esta disponible.');
            }
            // Reenvio del MISMO formulario: no crear una segunda reserva.
            $anterior = DB::table('reservas')->where('operacion', $operacion)->first();
            if ($anterior) {
                if ((int) $anterior->sesion_id !== $sesionId
                    || !hash_equals($anterior->payload_hash, $payloadHash)) {
                    throw new ConflictoReserva('OPERACION_REUTILIZADA', 'Recarga el formulario antes de continuar.');
                }
                return ['id' => (int) $anterior->id, 'creada' => false];
            }
            if (!$sesion->abierta || now()->greaterThanOrEqualTo($sesion->inicio)) {
                throw new ConflictoReserva('SESION_CERRADA', 'La sesion no admite nuevas reservas.');
            }
            $ocupadas = DB::table('reservas')->where('sesion_id', $sesionId)
                ->where('estado', '!=', 'cancelada')->count();
            if ($ocupadas >= $sesion->capacidad) {
                throw new ConflictoReserva('SIN_PLAZA', 'Ya no quedan plazas. Elige otra sesion.');
            }
            $id = (int) DB::table('reservas')->insertGetId([
                'sesion_id' => $sesionId,
                'persona_id' => $personaId,
                'prerregistro' => $personaId === null ? json_encode($prerregistro, JSON_THROW_ON_ERROR) : null,
                'payload_hash' => $payloadHash,
                'estado' => 'reservada',
                'operacion' => $operacion,
                'token_gestion_hash' => $tokenGestionHash,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return ['id' => $id, 'creada' => true];
        }, 3);
    }
}
