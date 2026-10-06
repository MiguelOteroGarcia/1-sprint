<?php
// EJERCICIO CON DEFECTO INTENCIONAL. Solo arrays; no usar en la aplicacion.
// Ejecutar: php CambiarReserva_fallido.php
$reserva = ['id' => 'RV001', 'sesion' => 'S001', 'estado' => 'reservada'];
$antes = $reserva;
function cambiarConDefecto(array &$reserva, string $destino, int $plazas): void
{
    $reserva['sesion'] = $destino; // Defecto: muta antes de comprobar.
    if ($plazas < 1) {
        throw new RuntimeException('SIN_PLAZA');
    }
}
try {
    cambiarConDefecto($reserva, 'S003', 0);
} catch (RuntimeException $error) {
    echo $error->getMessage(), PHP_EOL;
}
echo json_encode(['esperado' => $antes, 'observado' => $reserva], JSON_PRETTY_PRINT), PHP_EOL;
// Reordena la comprobacion antes de mutar. Repite con destino lleno y con hueco.
// Explica por que el servidor ademas necesita transaccion y bloqueos reales.
