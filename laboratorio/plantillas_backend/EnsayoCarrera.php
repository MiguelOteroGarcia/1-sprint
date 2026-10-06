<?php
namespace App\Console\Commands;

use App\Services\CrearReserva;
use App\Services\ConflictoReserva;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// SOLO entorno testing. Adaptar con el esquema reducido de la receta.
final class EnsayoCarrera extends Command
{
    protected $signature = 'fm:ensayo-plaza {sesion} {etiqueta} {--pausa=0} {--capacidad=1} {--operacion=}';
    protected $description = 'Ensayo de dos procesos sobre una sesion ficticia de fm_test';

    public function handle(CrearReserva $servicio): int
    {
        if (!app()->environment('testing') || DB::connection()->getDriverName() !== 'pgsql') {
            $this->error('Requiere APP_ENV=testing y pgsql.');
            return self::FAILURE;
        }
        $base = DB::selectOne('SELECT current_database() AS nombre, pg_backend_pid() AS pid');
        if ($base->nombre !== 'fm_test') {
            $this->error('Base incorrecta; no se modifica nada.');
            return self::FAILURE;
        }
        $sesionId = filter_var($this->argument('sesion'), FILTER_VALIDATE_INT);
        $pausa = filter_var($this->option('pausa'), FILTER_VALIDATE_INT);
        $capacidad = filter_var($this->option('capacidad'), FILTER_VALIDATE_INT);
        $operacion = $this->option('operacion') ?: (string) Str::uuid();
        if (!$sesionId || $pausa === false || $pausa < 0 || $pausa > 20
            || !$capacidad || $capacidad > 20 || !preg_match('/^[a-zA-Z0-9_-]{8,64}$/', $operacion)) {
            $this->error('ID positivo; pausa 0-20; capacidad 1-20; operacion de 8-64 letras, numeros, guion o guion bajo.');
            return self::FAILURE;
        }
        $this->line('Inicio '.date(DATE_ATOM).' PID='.$base->pid);
        try {
            $salida = DB::transaction(function () use ($servicio, $sesionId, $pausa, $capacidad, $operacion) {
                // Lectura simple: el ensayo NO debe suplir el bloqueo del servicio.
                $sesion = DB::table('sesiones')->where('id', $sesionId)->first();
                if (!$sesion || (int) $sesion->capacidad !== $capacidad) {
                    throw new \RuntimeException('La capacidad de la sesion ficticia no coincide con --capacidad.');
                }
                $datos = ['nombre' => 'Ensayo '.$this->argument('etiqueta'), 'correo' => 'ensayo@example.test', 'tipo_atencion' => 'adulto'];
                $resultado = $servicio->ejecutar($sesionId, null, $datos,
                    hash('sha256', json_encode($datos, JSON_THROW_ON_ERROR)),
                    $operacion, hash('sha256', random_bytes(32)));
                $this->line('Reserva escrita sin confirmar '.date(DATE_ATOM));
                // La transaccion exterior conserva los bloqueos que TOMO EL SERVICIO.
                if ($pausa) { sleep($pausa); }
                return $resultado;
            });
            $this->line(json_encode($salida, JSON_THROW_ON_ERROR));
        } catch (ConflictoReserva $error) {
            $this->line('Rechazo esperado: '.$error->codigo);
        }
        $this->line('Fin '.date(DATE_ATOM).' ocupadas='.DB::table('reservas')->where('sesion_id', $sesionId)->where('estado', '!=', 'cancelada')->count());
        return self::SUCCESS;
    }
}
