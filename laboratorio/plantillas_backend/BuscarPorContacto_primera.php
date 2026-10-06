<?php
// EJERCICIO CON DEFECTO INTENCIONAL. Solo arrays ficticios; no conectar a rutas.
// Ejecutar: php BuscarPorContacto_primera.php
$personas = [
    ['codigo' => 'U003', 'nombre' => 'Familiar Tres', 'contacto' => 'familia@example.test'],
    ['codigo' => 'U004', 'nombre' => 'Familiar Cuatro', 'contacto' => 'familia@example.test'],
];
function buscarPrimera(array $filas, string $contacto): array
{
    foreach ($filas as $fila) {
        if ($fila['contacto'] === $contacto) {
            return [$fila]; // Defecto: pierde los otros candidatos.
        }
    }
    return [];
}
$observado = buscarPrimera($personas, 'familia@example.test');
echo json_encode(['esperado_codigos' => ['U003', 'U004'], 'observado' => $observado], JSON_PRETTY_PRINT), PHP_EOL;
// Tu cambio debe devolver ambos candidatos y [] para un contacto inexistente.
// El ensayo local no acredita permisos de una consulta de servidor.
