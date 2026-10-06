<?php

use Illuminate\Support\Facades\Route;

// Añade solo esta declaración al routes/web.php del esqueleto recién creado.
// La apertura PHP y el use Route se conservan una sola vez en ese archivo.
Route::get('/demo-centros', function () {
    return view('demo-centros', [
        'centros' => [
            ['codigo' => 'C01', 'nombre' => 'Centro Norte'],
            ['codigo' => 'C02', 'nombre' => 'Centro Sur'],
        ],
    ]);
});
