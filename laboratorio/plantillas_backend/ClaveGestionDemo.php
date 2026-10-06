<?php

namespace App\Support;

// Ejemplo aislado. El controlador aún debe autorizar reserva, centro y acción.
final class ClaveGestionDemo
{
    /** @return array{clave: string, huella: string} */
    public static function emitir(): array
    {
        $clave = bin2hex(random_bytes(32));

        return ['clave' => $clave, 'huella' => hash('sha256', $clave)];
    }

    public static function coincide(string $clave, string $huellaGuardada): bool
    {
        return hash_equals($huellaGuardada, hash('sha256', $clave));
    }
}
