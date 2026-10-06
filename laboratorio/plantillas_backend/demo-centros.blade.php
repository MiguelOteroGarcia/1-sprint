<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Centros de demostración</title>
    <style>
        body { max-width: 48rem; margin: 2rem auto; padding: 0 1rem; font: 1.1rem/1.6 system-ui, sans-serif; }
        li { margin-bottom: .5rem; }
    </style>
</head>
<body>
    <main>
        <h1>Centros de demostración</h1>
        <p>Simulación educativa. Estos datos proceden de un array fijo; todavía no se consulta la base de datos.</p>
        <ul>
            @foreach ($centros as $centro)
                <li>{{ $centro['codigo'] }} · {{ $centro['nombre'] }}</li>
            @endforeach
        </ul>
    </main>
</body>
</html>
