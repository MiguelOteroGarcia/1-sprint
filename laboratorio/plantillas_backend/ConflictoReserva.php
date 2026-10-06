<?php
namespace App\Services;

final class ConflictoReserva extends \RuntimeException
{
    public function __construct(public string $codigo, string $mensaje)
    {
        parent::__construct($mensaje);
    }
}
